<?php

namespace App\Http\Controllers;
use App\Models\SmsSetting;
use App\Models\SmsServerSetting;
use App\Models\SmsOutbox;
use App\Models\CronJob;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentSMSNotificationInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ComponentSMSNotificationController extends Controller
{
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    private $slugs;

    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->slugs = 'components/sms-notifications';
        $this->slugs = 'business-permit/application';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('components.sms-notifications.index');
    }
    
    public function lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->componentMenuGroupRepository->listItems($request);
        $res = $result->data->map(function($sms) use ($statusClass, $actions, $canDelete) {
            $messages = wordwrap($sms->messages, 25, "<br />\n");            
            if ($canDelete > 0) {
                $actions .= ($sms->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            // $orderAction = '<a href="javascript:;" class="action-btn order-btn order-up bg-arrow btn m-1 btn-sm align-items-center" title="Order Up"><i class="ti-arrow-up text-white"></i></a><a href="javascript:;" class="action-btn order-btn order-down bg-arrow btn m-1 btn-sm align-items-center" title="Order Down"><i class="ti-arrow-down text-white"></i></a>';
            $slug = wordwrap(url('/'.$sms->slug), 25, "<br />\n");
            return [
                'id' => $sms->id,
                'type' => $sms->type ? $sms->type->name : '',
                'description' => '<div class="showLess" title="' . $sms->description . '">' . $description . '</div>',
                'icon' => $sms->icon,
                'slug' => '<div class="showLess" title="'.url('/'.$sms->slug).'">' . $slug . '</div>',
                'order' => $sms->order,
                'modified' => ($sms->updated_at !== NULL) ? date('d-M-Y', strtotime($sms->updated_at)).'<br/>'. date('h:i A', strtotime($sms->updated_at)) : date('d-M-Y', strtotime($sms->created_at)).'<br/>'. date('h:i A', strtotime($sms->created_at)),
                'status' => $statusClass[$sms->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$sms->is_active]->bg. ' p-2">' . $statusClass[$sms->is_active]->status . '</span>' ,
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
    
    public function new(Request $request)
    {   
        $this->is_permitted($this->slugs, 'create');
        return view('components.sms-notifications.new');
    }

    public function send(Request $request)
    {   
        $validate = $this->componentSMSNotificationRepository->validate();
        if ($validate > 0) {
            $setting = $this->componentSMSNotificationRepository->fetch_setting();
            $details = array(
                'type_id' => 1,
                'masking_code' => $setting->mask->code,
                'messages' => $request->message,
                'created_at' => $this->carbon::now(),
                'created_by' => Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
            foreach ($request->receipients as $receipient) {
                $this->componentSMSNotificationRepository->send($receipient, $message);
            }

            return response()->json([
                'data' => $request,
                'title' => 'Well done!',
                'text' => 'The request has been sucessfully sent.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        } else {
            return response()->json([
                'data' => $request,
                'title' => 'Oops!',
                'text' => 'Unable to send, the SMS Notification is disabled.',
                'type' => 'warning',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function send_later(Request $request)
    {   
        $validate = $this->componentSMSNotificationRepository->validate();
        if ($validate > 0) {
            $timestamp = $this->carbon::now();
            $details = array(
                'message_type_id' => 1,
                'messages' => $request->message,
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
            
            $job = $this->insertJobs([
                'description' => 'messaging', 
                'slugs' => url('/sms/messaging'), 
                'timestamp' => date('Y-m-d H:i:s', strtotime($request->schedule)),
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            ]);
            $details = array(
                'message_id' => $message->id,
                'cron_job_id' => $job->id,
                'schedule' => date('Y-m-d H:i:s', strtotime($request->schedule)),
                'created_at' => $timestamp,
                'created_by' => Auth::user()->id
            );
            $schedule = $this->componentSMSNotificationRepository->create_schedule($details);

            foreach ($request->receipients as $receipient) {
                $this->componentSMSNotificationRepository->send_later($receipient, $message);
            }

            return response()->json([
                'data' => $request,
                'title' => 'Well done!',
                'text' => 'The request has been sucessfully scheduled.',
                'type' => 'success',
                'class' => 'btn-brand'
            ]);
        } else {
            return response()->json([
                'data' => $request,
                'title' => 'Oops!',
                'text' => 'Unable to send, the SMS Notification is disabled.',
                'type' => 'warning',
                'class' => 'btn-brand'
            ]);
        }
    }

    public function search_user(Request $request)
    {   
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->search_user($request->user),
            'title' => 'Well done!',
            'text' => 'The user has been sucessfully searched.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function search_employee(Request $request)
    {   
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->search_employee($request->employee),
            'title' => 'Well done!',
            'text' => 'The employee has been sucessfully searched.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function search_taxpayer(Request $request)
    {   
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->search_taxpayer($request->taxpayer),
            'title' => 'Well done!',
            'text' => 'The taxpayer has been sucessfully searched.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function search_citizen(Request $request)
    {   
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->search_citizen($request->citizen),
            'title' => 'Well done!',
            'text' => 'The citizen has been sucessfully searched.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function sms_received(Request $request)
    {
        return response()->json([
            'data' => $request,
            'title' => 'Well done!',
            'text' => 'The sms has been sent.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function settings(Request $request)
    {           
        $permissions = (object) [
            'create' => $this->is_permitted('components/sms-notifications/settings', 'create'),
            'read' => $this->is_permitted('components/sms-notifications/settings', 'read'),
            'update' => $this->is_permitted('components/sms-notifications/settings', 'update'),
            'delete' => $this->is_permitted('components/sms-notifications/settings', 'delete')
        ];
        $schemes = ['0' => 'SMSC Default Alphabet', '1' => 'ASCII', '2' => 'Latin 1 (ISO-8859-1)', '8' => 'UCS2 (ISO/IEC-10646)'];
        $apps = SmsServerSetting::find(1);
        $masks = $this->componentSMSNotificationRepository->get_masks();
        $maskings = $this->componentSMSNotificationRepository->allMaskings();
        return view('components.sms-notifications.setting')->with(compact('schemes', 'masks', 'maskings', 'apps', 'permissions'));
    }

    public function find_setting(Request $request, $id)
    {
        $this->is_permitted('components/sms-notifications/settings', 'read');
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->find_setting($id),
            'title' => 'Well done!',
            'text' => 'The sms setting has been successfully found.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_setting(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('components/sms-notifications/settings', 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_setting($id, $details),
            'title' => 'Well done!',
            'text' => 'The setting has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore_setting(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('components/sms-notifications/settings', 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_setting($id, $details),
            'title' => 'Well done!',
            'text' => 'The setting has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function update_server_settings(Request $request)
    {   
        $details = array(
            'masking_id' => $request->masking_id,
            'is_enabled' => $request->get('enabled')
        );
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_server_settings($details),
            'title' => 'Well done!',
            'text' => 'The sms setting has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function settings_lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted('components/sms-notifications/settings', 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="Edit"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted('components/sms-notifications/settings', 'delete', 1);
        $result = $this->componentSMSNotificationRepository->settings_listItems($request);
        $res = $result->data->map(function($setting) use ($statusClass, $actions, $canDelete) {
            $app_key = wordwrap($setting->app_key, 25, "\n");
            $app_secret = wordwrap($setting->app_secret, 25, "\n");
            if ($canDelete > 0) {
                $actions .= ($setting->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="Remove"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $setting->id,
                'type' => $setting->type->name,
                'mask' => $setting->mask->name,
                'app_name' => $setting->app_name,
                'app_key' => '<div class="showLess" title="' . $setting->app_key . '">' . $app_key . '</div>',
                'app_secret' => '<div class="showLess" title="' . $setting->app_secret . '">' . $app_secret . '</div>',
                'modified' => ($setting->updated_at !== NULL) ? 
                '<strong>'.$setting->modified->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($setting->updated_at)) : 
                '<strong>'.$setting->inserted->name.'</strong><br/>'. date('d-M-Y h:i A', strtotime($setting->created_at)),
                'status' => $statusClass[$setting->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$setting->is_active]->bg. ' p-2">' . $statusClass[$setting->is_active]->status . '</span>' ,
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function update_setting(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted('components/sms-notifications/settings', 'update'); 
        $details = array(
            'type_id' => $request->type_id,
            'app_name' => $request->app_name,
            'app_key' => $request->app_key,
            'app_secret' => $request->app_secret,
            'passphrase' => $request->passphrase,
            'payload_url' => $request->payload_url,
            'dlr_url' => $request->dlr_url,
            'shortcode_mask' => $request->shortcode_mask,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_setting($id, $details),
            'title' => 'Well done!',
            'text' => 'The sms notification setting has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function store_setting(Request $request): JsonResponse 
    {   
        $this->is_permitted('components/sms-notifications/settings', 'update'); 
        $details = array(
            'type_id' => $request->type_id,
            'app_name' => $request->app_name,
            'app_key' => $request->app_key,
            'app_secret' => $request->app_secret,
            'passphrase' => $request->passphrase,
            'payload_url' => $request->payload_url,
            'dlr_url' => $request->dlr_url,
            'shortcode_mask' => $request->shortcode_mask,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->store_setting($details),
            'title' => 'Well done!',
            'text' => 'The sms notification setting has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function webhook(Request $request) 
    {
        $res = SmsOutbox::where(['transid' => $request->transid, 'msisdn' => $request->msisdn])->get();

        if (!($res->count() > 0)) {
            throw new NotFoundHttpException();
        } 

        $outbox = $res->first();
        $status = [
            8 => 'successful',
            1 => 'delivered',
            16 => 'failed',
            34 => 'expired',
            2 => 'undelivered'
        ];

        $details = array(
            'status' => $status[$request->status_code],
        );

        return response()
        ->json([
            'status' => 'success',
            'data' => $this->componentSMSNotificationRepository->update_sms($outbox->id, $details)
        ]);
    }

    public function flagship(Request $request)
    {   
        $timestamp = $this->carbon::now();
        $jobs = $this->findJobs($timestamp);
        if ($jobs->count() > 0) {
            foreach ($jobs as $job) {
                if (
                    ($job->is_repeated > 0 && 
                    (date('H:i', strtotime($job->timestamp)) ==  date('H:i', strtotime($timestamp))))
                    || 
                    ($job->is_repeated == 0)
                ) {
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $job->slugs);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                        $cron = CronJob::find($job->id);
                        if(!$cron) {
                            throw new NotFoundHttpException();
                        }
                        $cron->response = $err;           
                        $cron->update();
                    } else {
                        // $response = json_decode($response);
                        echo $response;
                        $cron = CronJob::find($job->id);
                        if(!$cron) {
                            throw new NotFoundHttpException();
                        }
                        $cron->response = $response;        
                        $cron->update();
                    }
                }
            }
        } else {
            return response()
            ->json([
                'success' => false,
                'message' => 'No jobs found',
                'timestamp' => $timestamp
            ]);
        }
    }

    public function messaging(Request $request) 
    {
        $job = $this->findJobs($this->carbon::now(), 'messaging');
        if ($job->count() > 0) {
            $job = $job->first();
            return response()
            ->json([
                'status' => true,
                'data' => $this->componentSMSNotificationRepository->send_now($job)
            ]);
        }
        return response()
        ->json([
            'success' => false
        ]);
    }
    
    public function sms_templates(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $groups = $this->componentSMSNotificationRepository->allGroupMenus();
        $modules = ['' => 'select a module'];
        $sub_modules = ['' => 'select a sub-module'];
        $types = $this->componentSMSNotificationRepository->allSmsTypes();
        $actions = $this->componentSMSNotificationRepository->allSmsActions();
        return view('components.sms-notifications.templates')->with(compact('groups', 'modules', 'sub_modules', 'types', 'actions'));
    }

    public function fetch_codex(Request $request)
    {
        $this->is_permitted('components/sms-notifications/templates', 'read');
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->fetch_codex(),
            'title' => 'Well done!',
            'text' => 'The module has been successfully reloaded.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function reload_module(Request $request)
    {
        $this->is_permitted('components/sms-notifications/templates', 'read');
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->reload_module($request->get('group')),
            'title' => 'Well done!',
            'text' => 'The module has been successfully reloaded.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function reload_sub_module(Request $request)
    {
        $this->is_permitted('components/sms-notifications/templates', 'read');
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->reload_sub_module($request->get('group'), $request->get('module')),
            'title' => 'Well done!',
            'text' => 'The sub module has been successfully reloaded',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function group_lists(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return response()->json([
            'group' => $this->componentSMSNotificationRepository->group_lists(),
            'type' => $this->componentSMSNotificationRepository->type_lists()
        ]);
    }

    public function sms_templates_lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted('components/sms-notifications/templates', 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted('components/sms-notifications/templates', 'delete', 1);
        $result = $this->componentSMSNotificationRepository->template_listItems($request);
        $res = $result->data->map(function($tmp) use ($actions, $canDelete, $statusClass) {
            $template = $tmp->template ? wordwrap($tmp->template, 25, "\n") : '';
            $application = $tmp->application ? wordwrap($tmp->application, 25, "\n") : '';
            $groups = $tmp->group ? wordwrap($tmp->group->name . ($tmp->module ? ' >> ' . $tmp->module->name : '') . ($tmp->sub_module ? ' >> '. $tmp->sub_module->name : ''), 25, "\n") : '';
            if ($canDelete > 0) {
                $actions .= ($tmp->is_active > 0) ? '<a href="javascript:;" class="action-btn remove-btn bg-danger btn m-1 btn-sm align-items-center" title="remove this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-trash text-white"></i></a>' : '<a href="javascript:;" class="action-btn restore-btn bg-info btn m-1 btn-sm align-items-center" title="restore this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-reload text-white"></i></a>';
            }
            return [
                'id' => $tmp->id,
                'groups' => '<div class="showLess" title="' . ($tmp->group->name . ($tmp->module ? ' >> ' . $tmp->module->name : '') . ($tmp->sub_module ? ' >> '. $tmp->sub_module->name : '')) . '">' . $groups . '</div>',
                'template' => '<div class="showLess" title="' . ($tmp->template ? $tmp->template : '') . '">' . $template . '</div>',
                'application_label' => '<div class="showLess" title="' . ($tmp->application ? $tmp->application : '') . '">' . $application . '</div>',
                'application' => $tmp->application,
                'sender' => $tmp->type->name,
                'modified' => ($tmp->updated_at !== NULL) ? date('d-M-Y', strtotime($tmp->updated_at)).'<br/>'. date('h:i A', strtotime($tmp->updated_at)) : date('d-M-Y', strtotime($tmp->created_at)).'<br/>'. date('h:i A', strtotime($tmp->created_at)),
                'status' => $statusClass[$tmp->is_active]->status,
                'status_label' => '<span class="badge badge-status rounded-pill ' . $statusClass[$tmp->is_active]->bg. ' p-2">' . $statusClass[$tmp->is_active]->status . '</span>' ,
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }

    public function store_template(Request $request): JsonResponse 
    {       
        $this->is_permitted($this->slugs, 'create');  
        $rows = $this->componentSMSNotificationRepository->validate_template($request->application);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot create a template with an existing application.',
                'label' => 'This is an existing application.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'application',
            ]);
        }

        $details = array(
            'group_id' => $request->group_id,
            'module_id' => $request->module_id,
            'sub_module_id' => $request->sub_module_id,
            'type_id' => $request->type_id,
            'application' => $request->application,
            'action_id' => $request->action_id,
            'template' => $request->template,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        );
        $group = $this->componentSMSNotificationRepository->create_template($details);

        return response()->json(
            [
                'data' => $group,
                'title' => 'Well done!',
                'text' => 'The template has been successfully saved.',
                'type' => 'success',
                'class' => 'btn-brand'
            ],
            Response::HTTP_CREATED
        );
    }

    public function find_template(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->find_template($id)
        ]);
    }

    public function update_template(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'update'); 
        $rows = $this->componentSMSNotificationRepository->validate_template($request->application, $id);
        if ($rows > 0) {
            return response()->json([
                'title' => 'Oh snap!',
                'text' => 'You cannot update a template with an existing application.',
                'label' => 'This is an existing application.',
                'type' => 'error',
                'class' => 'btn-danger',
                'column' => 'application'
            ]);
        }

        $details = array(
            'group_id' => $request->group_id,
            'module_id' => $request->module_id,
            'sub_module_id' => $request->sub_module_id,
            'type_id' => $request->type_id,
            'application' => $request->application,
            'action_id' => $request->action_id,
            'template' => $request->template,
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_template($id, $details),
            'title' => 'Well done!',
            'text' => 'The template has been successfully updated.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function remove_template(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 0
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_template($id, $details),
            'title' => 'Well done!',
            'text' => 'The template has been successfully removed.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function restore_template(Request $request, $id): JsonResponse 
    {   
        $this->is_permitted($this->slugs, 'delete'); 
        $details = array(
            'updated_at' => $this->carbon::now(),
            'updated_by' => Auth::user()->id,
            'is_active' => 1
        );

        return response()->json([
            'data' => $this->componentSMSNotificationRepository->update_template($id, $details),
            'title' => 'Well done!',
            'text' => 'The template has been successfully restored.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function tracking(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $statuses = ['successful' => 'Successful', 'failed' => 'Failed', 'delivered' => 'Delivered', 'undelivered' => 'Undelivered', 'expired' => 'Expired', 'all' => 'All Messagess'];
        return view('components.sms-notifications.tracking')->with(compact('statuses'));
    }

    public function tracking_lists(Request $request) 
    { 
        $actions = '';
        if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn resend-btn bg-secondary btn me-05 btn-sm align-items-center" title="resend this"><i class="la la-refresh text-white"></i></a>';
        }
        $canDelete = $this->is_permitted($this->slugs, 'delete', 1);
        $result = $this->componentSMSNotificationRepository->tracking_listItems($request);
        $res = $result->data->map(function($msg) use ($actions, $canDelete) {
            $message = ($msg->messages) ? wordwrap($msg->messages, 25, "\n") : '';
            return [
                'id' => $msg->id,
                'message' => '<div class="showLess" title="' . $msg->messages . '">' . trim(str_replace('\n', ' ', (str_replace('\r', ' ', $message)))) . '</div>',
                'contacts' => $msg->contacts,
                'successful' => $msg->successful,
                'failed' => $msg->failed,
                'delivered' => $msg->delivered,
                'undelivered' => $msg->undelivered,
                'expired' => $msg->expired,
                'sent_at' => date('d-M-Y H:i A', strtotime($msg->created_at)),
                'actions' => $actions
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            "sms" => array(
                "successful" => $this->componentSMSNotificationRepository->get_sms_count_via_status('successful'),
                "failed" => $this->componentSMSNotificationRepository->get_sms_count_via_status('failed'),
                "delivered" => $this->componentSMSNotificationRepository->get_sms_count_via_status('delivered'),
                "undelivered" => $this->componentSMSNotificationRepository->get_sms_count_via_status('undelivered'),
                "expired" => $this->componentSMSNotificationRepository->get_sms_count_via_status('expired')
            ),
            'data' => $res,
        ]);
    }

    public function resend(Request $request, $id)
    {
        $this->is_permitted($this->slugs, 'update');
        return response()->json([
            'data' => $this->componentSMSNotificationRepository->resend($id),
            'title' => 'Well done!',
            'text' => 'The sms notification has been successfully sent.',
            'type' => 'success',
            'class' => 'btn-brand'
        ]);
    }

    public function outbox(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('components.sms-notifications.outbox');
    }

    public function outbox_lists(Request $request) 
    { 
        $statusClass = [
            0 => (object) ['bg' => 'bg-secondary', 'status' => 'Inactive'],
            1 => (object) ['bg' => 'bg-info', 'status' => 'Active'],
        ];
        $actions = '';
        if ($this->is_permitted('components/sms-notifications/outbox', 'update', 1) > 0) {
            $actions .= '<a href="javascript:;" class="action-btn edit-btn bg-warning btn me-05 btn-sm align-items-center" title="edit this" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti-pencil text-white"></i></a>';
        }
        $canDelete = $this->is_permitted('components/sms-notifications/outbox', 'delete', 1);
        $result = $this->componentSMSNotificationRepository->outbox_listItems($request);
        $res = $result->data->map(function($outbox) use ($actions, $canDelete, $statusClass) {
            $transid = $outbox->transid ? wordwrap($outbox->transid, 25, "\n") : '';
            $messages = $outbox->message ? wordwrap($outbox->message->messages, 25, "\n") : '';
            return [
                'id' => $outbox->id,
                'transid' => '<div class="showLess" title="' . ($outbox->transid ? $outbox->transid : '') . '">' . $transid . '</div>',
                'messages' => '<div class="showLess" title="' . ($outbox->message ? $outbox->message->messages : '') . '">' . $messages . '</div>',
                'msisdn' => $outbox->msisdn,
                'type' => $outbox->message->type ? $outbox->message->type->name : '',
                'smsc' => $outbox->smsc,
                'modified' => ($outbox->updated_at !== NULL) ? date('d-M-Y', strtotime($outbox->updated_at)).'<br/>'. date('h:i A', strtotime($outbox->updated_at)) : date('d-M-Y', strtotime($outbox->created_at)).'<br/>'. date('h:i A', strtotime($outbox->created_at)),
                'status' => $outbox->status
            ];
        });

        return response()->json([
            'request' => $request,
            "recordsTotal"    => intval($result->count),  
			"recordsFiltered" => intval($result->count),
            'data' => $res,
        ]);
    }
}
