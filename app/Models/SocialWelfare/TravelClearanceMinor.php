<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\TMCFiles;
use App\Models\HrEmployee;
use DB;
use Auth;
use Carbon\Carbon;
use App\Traits\ModelUpdateCreate;

class TravelClearanceMinor extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_travel_clearance_minor';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function addData($postdata){
        $this->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getEditDetails($id){
        return $this->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return $this->where('id',$id)->update($columns);
    }
    public function claimant() 
    { 
        return $this->belongsTo(Citizen::class, 'cit_id', 'id'); 
    }
    public function companion() 
    { 
        return $this->belongsTo(Citizen::class, 'wtcm_companion_name', 'id'); 
    }
    public function father() 
    { 
        return $this->belongsTo(Citizen::class, 'wtcm_father_cit_id', 'id'); 
    }
    public function mother() 
    { 
        return $this->belongsTo(Citizen::class, 'wtcm_mother_cit_id', 'id'); 
    }
    public function files() 
    { 
        return $this->hasMany(TMCFiles::class, 'wtcm_id', 'id'); 
    } 
    public function minors() 
    { 
        return $this->hasMany(TMCMinors::class, 'wtcm_id', 'id'); 
    } 
    public function destinations() 
    { 
        return $this->hasMany(TMCDestinations::class, 'wtcm_id', 'id'); 
    } 
    public function prepared_by() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wtcm_prepared_by', 'id'); 
    }
    public function reviewed_by() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wtcm_reviewed_by', 'id'); 
    }
    public function approved_by() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wtcm_approved_by', 'id'); 
    }
    public function updateRelation($data)
    {
        if ($data->type == 'requirement') {
            $assist = TMCFiles::find($data->id);
            $assist->fwtm_is_active = $data->status;
            $assist->save();
        }
    }

    public function addRelation($data)
    {
        if (isset($data->destination)) {
            foreach ($data->destination as $key => $value) {
                if ($value['wtcmd_place']){
                    $add = TMCDestinations::updateOrCreate(
                        [
                            'wtcm_id' => $data->id,//assistance id
                            'id' => $key,//dependent id
                        ],
                        $value
                    );
                }
            }
        }
        if (isset($data->minors)) {
            foreach ($data->minors as $key => $value) {
                // dd($value);
                if ($value['cit_id']){
                    Citizen::updateData($value['cit_id'],$value['data']);
                    $add = TMCMinors::updateOrCreate(
                        [
                            'wtcm_id' => $data->id,//assistance id
                            'id' => $key,//dependent id
                        ],
                        [
                            'cit_id' => $value['cit_id'],//Name of Claimant
                        ]
                    );
                }
            }
        }
        if (isset($data->require)) {
            foreach ($data->require as $key => $value) {
                    $fileName = '';
                    $fileType = '';
                    $fileSize = '';
                    $filePath = '';
                if ($file = TMCFiles::where([['id',$key],['wtcm_id',$data->id],])->first()){ 
                    $fileName = $file->fwtm_name;
                    $fileType = $file->fwtm_type;
                    $fileSize = $file->fwtm_size;
                    $filePath = $file->fwtm_path;
                }
                if (isset($value['file'])){ 
                    $size = $value['file']->getSize() * .001;
                    $fileSize = round($size,2);
                    $fileName = $value['req_type'].$value['req_id']."-".time().'.'.$value['file']->getClientOriginalExtension();
                    $filePath = "uploads/socialwelfare/".$fileName;
                    $fileType = $value['file']->getClientOriginalExtension();
                    // $fileName = $value['file']->getClientOriginalName();
                    $value['file']->move(public_path('uploads/socialwelfare'), $fileName);
                }
                $add = TMCFiles::updateOrCreate(
                    [
                        'wtcm_id' => $data->id,//assistance id
                        'id' => $key,//file id
                    ],
                    [
                        'req_id' => $value['req_id'], // req id
                        'req_type' => $value['req_type'], // req id
                        'fwtm_name' => $fileName,//file name
                        'fwtm_type' => $fileType,//file type
                        'fwtm_size' => $fileSize, // file size
                        'fwtm_path' => $filePath,
                        'fwtm_is_active' => 1, // file size
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }
        }
        
    }

    public function getList($request){
		
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }
        $columns = array( 
            1 =>"minor.cit_fullname",
            2 =>"minor.cit_full_address",   
            3 =>"companion.cit_fullname",   
            4 =>"wtcm_relation_to_minor",   
            5 =>"wtcm_companion_date_of_birth",   
            6 =>"wtcm_is_active",   
        );
            $sql = $this->select('welfare_travel_clearance_minor.*','companion.cit_fullname')
                    ->leftJoin('citizens as companion', 'companion.id', '=', 'welfare_travel_clearance_minor.wtcm_companion_name');
            if(!empty($q) && isset($q)){
                $sql = $sql->where(function ($query) use($q) {
                            $query->orWhere(DB::raw('LOWER(minor.cit_full_address)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(companion.cit_fullname)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw('LOWER(wtcm_relation_to_minor)'),'like',"%".strtolower($q)."%");
                            $query->orWhere(DB::raw("DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), wtcm_companion_date_of_birth)), '%Y') + 0"),'like',"%".strtolower($q)."%");
                        });
            }
           /*  #######  Set Order By  ###### */
            if(isset($params['order'][0]['column']))
                $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
            else
                $sql->orderBy('welfare_travel_clearance_minor.id','DESC');
           /*  #######  Get count without limit  ###### */
            $data_cnt=$sql->count();
           /*  #######  Set Offset & Limit  ###### */
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
            $data=$sql->get();
            return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function approve()
    {
        $user = Auth::user()->hr_employee;        
        $this->updateData($this->id, ['wtcm_approved_by'=>$user->id,'wtcm_is_approve'=>1]);
        return $user->fullname;
    }

    // Transaction
    public function getTransactionAttribute() 
    { 
        $or = DB::table('cto_cashier')->select('*')->where('id', $this->wtcm_cashier_id)->first(); 
        return $or;
    }
    public function getTfocIdAttribute()
    {
        $validity = $this->wtcm_validity;
        switch ($validity) {
            case 1:
                $app_name = 'Social Welfare: Travel Clearance For Minor - 1 Year Validity';
                break;
            
            case 2:
                $app_name = 'Social Welfare: Travel Clearance For Minor - 2 Years Validity';
                break;

            default:
                $app_name = 'Social Welfare: Travel Clearance For Minor';
                break;
        }
        $data = DB::table('cto_forms_miscellaneous_payments')->select('tfoc_id')->where('fpayment_app_name', $app_name)->first();
        return ($data && $data->tfoc_id != 0) ? $data->tfoc_id : null;
    }
    public function getOrNumber()
    {
        $companion = $this->wtcm_companion_name;
        $data = ['' => 'Select OR'];
        $or = DB::table('cto_cashier as m')->leftJoin('cto_cashier_details as d','m.id','=','d.cashier_id')->select('m.id as id','m.or_no as or_no')->where([['m.payee_type', 2],['m.client_citizen_id',$companion],['d.tfoc_id',$this->tfoc_id]])->get();
        foreach ($or as $key => $value) {
                $data += [$value->id => $value->or_no];
        }
        return $data;
    }
    public function getOrNumberId($id)
    {
        $or = DB::table('cto_cashier as m')->leftJoin('cto_cashier_details as d','m.id','=','d.cashier_id')->select('m.total_amount as amount','m.cashier_or_date as or_date','m.id as wtcm_cashier_id','d.id as wtcm_cashierd_id','m.or_no as or_no')->where('m.id', $id)->first(); 
        return $or;
    }
}
