<?php

namespace App\Repositories;

use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsMessage;
use App\Models\SmsOutbox;
use App\Models\SmsSetting;
use App\Models\SmsPrefix;
use App\Models\SmsMasking;
use App\Models\User;
use App\Models\HrEmployee;
use App\Models\Client;
use App\Models\Citizen;
use App\Models\SmsSchedule;
use App\Models\SmsServerSetting;
use App\Models\SmsTemplate;
use App\Models\SmsType;
use App\Models\SmsAction;
use App\Models\MenuGroup;
use App\Models\MenuModule;
use App\Models\MenuSubModule;
use App\Models\SmsCodex;
use DB;

class ComponentSMSNotificationRepository implements ComponentSMSNotificationInterface 
{   
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    
    public function validate()
    {
        return SmsServerSetting::find(1)->is_enabled;
    }

    public function fetch_setting()
    {
        return self::SETTINGS();
    }

    private static function SETTINGS() 
    {
        $setting = SmsServerSetting::find(1);
        return SmsSetting::select(['sms_settings.*'])->where(['sms_settings.type_id' => 1])->first();
    }

    public function find($id) 
    {
        return SmsMessage::findOrFail($id);
    }

    public function create(array $details) 
    {
        return SmsMessage::create($details);
    }

    public function update($id, array $newDetails) 
    {
        return SmsMessage::whereId($id)->update($newDetails);
    }

    public function update_setting($id, array $newDetails)
    {
        return SmsSetting::whereId($id)->update($newDetails);
    }

    public function create_schedule(array $details) 
    {
        return SmsSchedule::create($details);
    }

    public function send_now($job)
    {
        $schedules = SmsSchedule::select([
            'sms_schedules.*'
        ])
        ->where([
            'cron_job_id' => $job->id,
            'is_done' => 0
        ])
        ->get();

        $done = 0;
        if ($schedules->count() >0) {
            $schedule = $schedules->first();
            $message = SmsMessage::find($schedule->message_id);
            $receipients = SmsOutbox::where('status', '!=', 'successful')->where(['message_id' => $schedule->message_id, 'is_active' => 1])->get();
            if (!empty($receipients)) {
                foreach ($receipients as $receipient) {
                    $done++;
                    $this->send($receipient->msisdn, $message, $receipient->id);
                }
            }
        }
        if ($done > 0) {
            $schedule = SmsSchedule::find($schedule->id);
            $schedule->is_done = 1;
            $schedule->update();
        }

        return true;
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            // 1 => 'code',
            // 2 => 'name',
            // 3 => 'description',
            // 4 => 'icon',   
            // 5 => 'slug',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = SmsMessage::select([
            'messages.*'
        ])
        ->leftJoin('messages_types', function($join)
        {
            $join->on('messages_types.id', '=', 'messages.message_type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('messages.id', 'like', '%' . $keywords . '%')
                ->orWhere('messages.messages', 'like', '%' . $keywords . '%')
                ->orWhere('messages_types.name', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function num_format($msisdn)
    {
        $pattern = array('/i/i','/l/i','/o/i','/[^\d]/','/^(\+63|63)/');
        $replace = array(1,1,0,'','0');
        $msisdn = preg_replace($pattern, $replace, trim($msisdn));
        return $msisdn;
    }

    public function getPrefix($number)
    {
        $msisdn = $this->num_format($number);
        $prefix = str_split($msisdn, 4);

        $result = SmsPrefix::select('network')->where('access', 'like', '%' . $prefix[0] . '%')->get();
        $network = 'auto';

        if ($result->count() > 0) {
            foreach ($result as $res) {
                $network = $res->network;
            }
        }

        return $network;
    }

    public function send($receipient, $message, $outboxID = 0)
    {   
        $network = [
            1 => 'globe',
            2 => 'smart',
            3 => 'sun',
            4 => 'dito'
        ];
        $receipient = (strlen($receipient) > 10) ?  $receipient : ltrim($receipient, $receipient[0]);
        $validate = $this->getPrefix($receipient);
        if ($validate != 'auto') {
            if (!($outboxID > 0)) {
                $outbox = SmsOutbox::create([
                    'message_id' => $message->id,
                    'msisdn' => $receipient,
                    'created_at' => $message->created_at,
                    'created_by' => $message->created_by
                ]);
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => self::SETTINGS()->payload_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{ 
                    \"app_key\": \"".self::SETTINGS()->app_key."\", 
                    \"app_secret\": \"".self::SETTINGS()->app_secret."\", 
                    \"shortcode_mask\": \"".self::SETTINGS()->mask->code."\", 
                    \"dcs\": \"".self::SETTINGS()->dcs."\", 
                    \"msisdn\": \"".$receipient."\", 
                    \"content\": \"".$message->messages."\" 
                }",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $response = json_decode($response);
                // DD($response);
                $outboxID = ($outboxID > 0) ? $outboxID : $outbox->id;
                $outbox = SmsOutbox::find($outboxID);
                if(!$outbox) {
                    throw new NotFoundHttpException();
                }
                if(!empty($response->telco_id)){
                    $outbox->smsc = $network[$response->telco_id];           
                    $outbox->transid = $response->transid; 
                    $outbox->timestamp = $response->timestamp; 
                    $outbox->msgcount = $response->msgcount; 
                    $outbox->telco_id = $response->telco_id;
                    $outbox->messageId = $response->messageId;
                    $outbox->update();
                }
            }
        }

        return true;
    }

    public function send_later($receipient, $message, $outboxID = 0)
    {   
        $network = [
            1 => 'globe',
            2 => 'smart',
            3 => 'sun',
            4 => 'dito'
        ];
        $receipient = (strlen($receipient) > 10) ?  $receipient : ltrim($receipient, $receipient[0]);

        $validate = $this->getPrefix($receipient);
        if ($validate != 'auto') {
            if (!($outboxID > 0)) {
                $outbox = SmsOutbox::create([
                    'message_id' => $message->id,
                    'msisdn' => $receipient,
                    'created_at' => $message->created_at,
                    'created_by' => $message->created_by,
                    'status' => 'pending'
                ]);
            }
        }

        return true;
    }


    public function search_user($keywords)
    {
        if ($keywords != '') {
            $users = User::rightJoin('hr_employees', function($join)
            {
                $join->on('hr_employees.user_id', '=', 'users.id');
            })
            ->where(function($q) use ($keywords) {
                if (!empty($keywords)) {
                    $q->where('users.name', 'like', '%' . $keywords . '%')
                    ->orWhere('hr_employees.mobile_no', 'like', '%' . $keywords . '%');
                }
            })
            ->whereRaw('LENGTH(hr_employees.mobile_no) = 11')->where('users.is_active', 1)->get();
        } else {
            $users = User::rightJoin('hr_employees', function($join)
            {
                $join->on('hr_employees.user_id', '=', 'users.id');
            })
            ->where(['users.is_active' => 1])
            ->whereRaw('LENGTH(hr_employees.mobile_no) = 11')->get();
        }
        return $users;
    }

    public function search_employee($keywords)
    {
        if ($keywords != '') {
            $employees = HrEmployee::
            where(function($q) use ($keywords) {
                if (!empty($keywords)) {
                    $q->where('fullname', 'like', '%' . $keywords . '%')
                    ->orWhere('mobile_no', 'like', '%' . $keywords . '%');
                }
            })->whereRaw('LENGTH(mobile_no) = 11')->where('is_active', 1)->get();
        } else {
            $employees = HrEmployee::where(['is_active' => 1])->whereRaw('LENGTH(mobile_no) = 11')->get();
        }
        return $employees;
    }

    public function search_taxpayer($keywords)
    {
        if ($keywords != '') {
            $taxpayers = Client::where(function($q) use ($keywords) {
                if (!empty($keywords)) {
                    $q->where('rpo_custom_last_name', 'like', '%' . $keywords . '%')
                    ->orWhere('rpo_first_name', 'like', '%' . $keywords . '%')
                    ->orWhere('rpo_middle_name', 'like', '%' . $keywords . '%')
                    ->orWhere('p_mobile_no', 'like', '%' . $keywords . '%');
                }
            })->whereRaw('LENGTH(p_mobile_no) = 11')->where('is_active', 1)->get();
        } else {
            $taxpayers = Client::where(['is_active' => 1])->whereRaw('LENGTH(p_mobile_no) = 11')->get();
        }
        return $taxpayers;
    }

    public function search_citizen($keywords)
    {
        if ($keywords != '') {
            $citizens = Citizen::where(function($q) use ($keywords) {
                if (!empty($keywords)) {
                    $q->where('cit_last_name', 'like', '%' . $keywords . '%')
                    ->orWhere('cit_middle_name', 'like', '%' . $keywords . '%')
                    ->orWhere('cit_first_name', 'like', '%' . $keywords . '%')
                    ->orWhere('cit_suffix_name', 'like', '%' . $keywords . '%')
                    ->orWhere('cit_mobile_no', 'like', '%' . $keywords . '%');
                }
            })->whereRaw('LENGTH(cit_mobile_no) = 11')->where('cit_is_active', 1)->get();
        } else {
            $citizens = Citizen::where(['cit_is_active' => 1])->whereRaw('LENGTH(cit_mobile_no) = 11')->get();
        }
        return $citizens;
    }

    public function update_sms($id, array $newDetails)
    {
        return SmsOutbox::whereId($id)->update($newDetails);
    }

    public function tracking_listItems($request)
    {   
        $columns = array( 
            0 => 'sms_messages.id',
            1 => 'sms_messages.messages',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'sms_messages.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $dateFrom  = $request->date_from;
        $dateTo    = $request->date_to;
        $status    = $request->status;

        $res = SmsMessage::select([
            'sms_messages.*',
            DB::raw('(SELECT COUNT(outbox2.msisdn) FROM sms_outbox AS outbox2 WHERE outbox2.message_id = sms_messages.id) as contacts'),
            DB::raw('(SELECT COUNT(outbox2.msisdn) FROM sms_outbox AS outbox2 WHERE outbox2.message_id = sms_messages.id AND outbox2.status = "successful") as successful'),
            DB::raw('(SELECT COUNT(outbox2.msisdn) FROM sms_outbox AS outbox2 WHERE outbox2.message_id = sms_messages.id AND outbox2.status = "failed") as failed'),
            DB::raw('(SELECT COUNT(outbox2.msisdn) FROM sms_outbox AS outbox2 WHERE outbox2.message_id = sms_messages.id AND outbox2.status = "delivered") as delivered'),
            DB::raw('(SELECT COUNT(outbox2.msisdn) FROM sms_outbox AS outbox2 WHERE outbox2.message_id = sms_messages.id AND outbox2.status = "undelivered") as undelivered'),
            DB::raw('(SELECT COUNT(outbox2.msisdn) FROM sms_outbox AS outbox2 WHERE outbox2.message_id = sms_messages.id AND outbox2.status = "expired") as expired')
        ])
        ->leftJoin('sms_types', function($join)
        {
            $join->on('sms_types.id', '=', 'sms_messages.type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('sms_messages.id', 'like', '%' . $keywords . '%')
                ->orWhere('sms_messages.messages', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        if (!empty($dateFrom) && !empty($dateTo)) {
            $res = $res->whereBetween('sms_messages.created_at', [$dateFrom, $dateTo]);
        }   
        if ($status != 'all') {
            $res = $res->whereIn('sms_messages.id', (function ($query) use ($status) {
                $query->from('sms_outbox')
                    ->select('message_id')
                    ->where('status','=', $status);
                })
            );
        }
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();
        return (object) array('count' => $count, 'data' => $res);
    }

    public function get_sms_count_via_status($status) 
    {
        return SmsOutbox::where(['status' => $status, 'is_active' => 1])->count();
    }

    public function resend($id)
    {   
        $message = SmsMessage::find($id);
        $receipients = SmsOutbox::where('status', '!=', 'successful')->where(['message_id' => $id, 'is_active' => 1])->get();
        if (!empty($receipients)) {
           foreach ($receipients as $receipient) {
                $this->send($receipient->msisdn, $message, $receipient->id);
            }
        }
        return true;
    }

    public function settings_listItems($request)
    {   
        $columns = array( 
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'description',
            4 => 'icon',   
            5 => 'slug',
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'order' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = SmsSetting::select(['sms_settings.*'])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('id', 'like', '%' . $keywords . '%')
                ->orWhere('code', 'like', '%' . $keywords . '%')
                ->orWhere('name', 'like', '%' . $keywords . '%')
                ->orWhere('description', 'like', '%' . $keywords . '%')
                ->orWhere('icon', 'like', '%' . $keywords . '%')
                ->orWhere('slug', 'like', '%' . $keywords . '%')
                ->orWhere('order', 'like', '%' . $keywords . '%');
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function get_masks()
    {
        return SmsMasking::where(['is_active' => 1])->get();
    }

    public function update_settings(array $newDetails)
    {
        return SmsServerSetting::whereId(1)->update($newDetails);
    }

    public function find_setting($id)
    {
        return SmsSetting::findOrFail($id);
    }

    public function allMaskings()
    {
        return (new SmsMasking)->allMaskings();
    }

    public function allGroupMenus()
    {
        return (new MenuGroup)->allGroupMenus();
    }

    public function allSmsTypes()
    {
        return (new SmsType)->allSmsTypes();
    }

    public function allSmsActions()
    {
        return (new SmsAction)->allSmsActions();
    }

    public function store_setting(array $details)
    {
        return SmsSetting::create($details);
    }

    public function reload_module($group)
    {
        return MenuModule::where(['is_active' => 1, 'menu_group_id' => $group])->get();
    }

    public function reload_sub_module($group, $module)
    {
        return MenuSubModule::select('menu_sub_modules.*')->leftJoin('menu_modules', function($join)
        {
            $join->on('menu_modules.id', '=', 'menu_sub_modules.menu_module_id');
        })
        ->where(['menu_sub_modules.is_active' => 1, 'menu_modules.menu_group_id' => $group, 'menu_sub_modules.menu_module_id' => $module])->get();
    }

    public function template_listItems($request)
    {
        $columns = array( 
            0 => 'sms_templates.id',
            1 => 'menu_groups.name',
            2 => 'sms_templates.application',
            3 => 'sms_types.name',
            4 => 'sms_templates.template'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'sms_templates.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $groups    = $request->get('groups');
        $types     = $request->get('types');

        $res = SmsTemplate::select(['sms_templates.*'])
        ->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'sms_templates.group_id');
        })
        ->leftJoin('menu_modules', function($join)
        {
            $join->on('menu_modules.id', '=', 'sms_templates.module_id');
        })
        ->leftJoin('menu_sub_modules', function($join)
        {
            $join->on('menu_sub_modules.id', '=', 'sms_templates.sub_module_id');
        })
        ->leftJoin('sms_types', function($join)
        {
            $join->on('sms_types.id', '=', 'sms_templates.type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('sms_templates.id', 'like', '%' . $keywords . '%')
                ->orWhere('sms_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_groups.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('menu_sub_modules.name', 'like', '%' . $keywords . '%')
                ->orWhere('sms_templates.application', 'like', '%' . $keywords . '%')
                ->orWhere('sms_templates.template', 'like', '%' . $keywords . '%');
            }
        });
        if ($types != 'all' && $types != 'null' && $types != 'undefined') {
            $res = $res->where('sms_types.id', '=', $types);
        }
        if ($groups != 'all' && $groups != 'null' && $groups != 'undefined') {
            $groups = explode(',', $groups);
            $res = $res->where('sms_templates.group_id', '=', $groups[0])->where('sms_templates.module_id', '=', $groups[1]);
        }
        $res   = $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function validate_template($application, $id = '')
    {   
        if ($id !== '') {
            return SmsTemplate::where(['application' => $application])->where('id', '!=', $id)->count();
        } 
        return SmsTemplate::where(['application' => $application])->count();
    }

    public function find_template($id) 
    {
        return SmsTemplate::findOrFail($id);
    }

    public function create_template(array $details) 
    {
        return SmsTemplate::create($details);
    }

    public function update_template($id, array $newDetails) 
    {
        return SmsTemplate::whereId($id)->update($newDetails);
    }

    public function group_lists()
    {
        $res = SmsTemplate::select([
            'sms_templates.*',
            DB::raw("CONCAT(menu_groups.name,' >> ',menu_modules.name) AS description")
        ])
        ->leftJoin('menu_groups', function($join)
        {
            $join->on('menu_groups.id', '=', 'sms_templates.group_id');
        })
        ->leftJoin('menu_modules', function($join)
        {
            $join->on('menu_modules.id', '=', 'sms_templates.module_id');
        })
        ->where(['sms_templates.is_active' => 1])
        ->groupBy(['sms_templates.group_id', 'sms_templates.module_id'])
        ->get();

        return $res;
    }

    public function type_lists()
    {
        return $res = SmsType::select('*')->where('is_active', '=', 1)->get();
    }

    public function outbox_listItems($request)
    {
        $columns = array( 
            0 => 'sms_outbox.id',
            1 => 'sms_outbox.transid',
            2 => 'sms_messages.messages',
            3 => 'sms_types.name',
            4 => 'sms_outbox.msisdn',
            5 => 'sms_outbox.smsc',
            6 => 'sms_outbox.status'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'sms_templates.id' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];
        $groups    = $request->get('groups');
        $types     = $request->get('types');

        $res = SmsOutbox::select(['sms_outbox.*'])
        ->leftJoin('sms_messages', function($join)
        {
            $join->on('sms_messages.id', '=', 'sms_outbox.message_id');
        })
        ->leftJoin('sms_types', function($join)
        {
            $join->on('sms_types.id', '=', 'sms_messages.type_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('sms_outbox.id', 'like', '%' . $keywords . '%')
                ->orWhere('sms_messages.messages', 'like', '%' . $keywords . '%')
                ->orWhere('sms_types.name', 'like', '%' . $keywords . '%')
                ->orWhere('sms_outbox.msisdn', 'like', '%' . $keywords . '%')
                ->orWhere('sms_outbox.smsc', 'like', '%' . $keywords . '%')
                ->orWhere('sms_outbox.status', 'like', '%' . $keywords . '%')
                ->orWhere('sms_outbox.transid', 'like', '%' . $keywords . '%');
            }
        });
        $res   = $res->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function fetch_codex()
    {
        return SmsCodex::where('is_active', '=', 1)->get();
    }
}