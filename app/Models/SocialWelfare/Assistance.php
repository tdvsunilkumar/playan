<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;
use DB;
use Auth;
use App\Traits\ModelUpdateCreate;
use App\Models\CboAllotmentObligationRequest;
use App\Models\CboAllotmentObligation;
use App\Models\CboAllotmentBreakdown;
use Carbon\Carbon;

class Assistance extends Model
{
    use ModelUpdateCreate;

    public $table = 'welfare_social_welfare_assistance';
    public $timestamps = false;
    protected $guarded = ['id'];
    // Policy
    public function getAssistanceAmountLimitAttribute()
    {
        $policy = Policy::where('wps_key','welfare_social_assistance_amount_limit')->first();
        return ($policy) ? $policy->wps_value:'';
    }
    public function getAllAssistanceType()
    {
        $data = ['' => 'Select Type'];
        $assistanceType = AssistanceType::active()->get();
        foreach ($assistanceType as $key => $value) {
            $data[$value->id] = $value->wsat_description;
        }
        return $data;
    }
    public function addData($postdata){
        $this->create($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function dependents() 
    { 
        return $this->hasMany(AssistanceDependent::class, 'wswa_id', 'id'); 
    } 
    public function requirements() 
    { 
        return $this->hasMany(AssistanceFile::class, 'wswa_id', 'id'); 
    } 
    public function claimant() 
    { 
        return $this->hasOne(Citizen::class, 'id', 'cit_id'); 
    }
    public function casestudy() 
    { 
        return $this->hasOne(CaseStudy::class, 'wswa_id', 'id'); 
    }
    public function request_letter() 
    { 
        return $this->hasOne(RequestLetter::class, 'wswa_id', 'id'); 
    }
    public function head() 
    { 
        return $this->belongsTo(Citizen::class, 'head_cit_id', 'id'); 
    }
    public function assistanceType() 
    { 
        return $this->belongsTo(AssistanceType::class, 'wsat_id', 'id'); 
    }
    public function socialWorker() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wswa_social_worker', 'id'); 
    }
    public function approver() 
    { 
        return $this->belongsTo(HrEmployee::class, 'wswa_approved_by', 'id'); 
    }
    public function getEditDetails($id){
        return $this->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return self::where('id',$id)->update($columns);
    }
    public function updateRelation($data)
    {
        if ($data->type == 'requirement') {
            $assist = AssistanceFile::find($data->id);
            $assist->fwa_is_active = $data->status;
            $assist->save();
        }
        elseif($data->type == 'dependent') {
            $assist = AssistanceDependent::find($data->id);
            $assist->wsd_is_active = $data->status;
            $assist->save();
        }
    }
    public function dependentCount()
    {
        return AssistanceDependent::all()->count();
    }

    public function fileCount()
    {
        return AssistanceFile::all()->count();
    }

    public function addRelation($data)
    {
        if (isset($data->dependent)) {
            foreach ($data->dependent as $key => $value) {
                if ($value['cit_id']){
                    // Citizen::updateData($value['cit_id'],$value['data']);
                    $add = AssistanceDependent::updateOrCreate(
                        [
                            'wswa_id' => $data->id,//assistance id
                            'id' => $key,//dependent id
                        ],
                        [
                            'wsd_cit' => $value['cit_id'],//dependent name
                            'cit_id' => $data['cit_id'],//Name of Claimant
                            'wsd_relation' => ($value['relation'])? $value['relation']:'', // dependant relation
                            // 'updated_at' => date('Y-m-d H:i:s'),
                            'wsd_is_active' => 1,
                        ]
                    );
                }
            }
        }
        if (isset($data->require)) {
            foreach ($data->require as $key => $value) {
                if ($value['req_id']){
                    $fileName = '';
                    $fileType = '';
                    $fileSize = '';
                    $filePath = '';
                    if ($file = AssistanceFile::where([['id',$key],['wswa_id',$data->id],])->first()){ 
                        $fileName = $file->fwa_name;
                        $fileType = $file->fwa_type;
                        $fileSize = $file->fwa_size;
                        $filePath = $file->fwa_path;
                    }
                    if (isset($value['file'])){ 
                        $size = $value['file']->getSize() * .001;
                        $fileSize = round($size,2);
                        $fileName = $value['req_type'].$value['req_id']."-".time().'.'.$value['file']->getClientOriginalExtension();
                        $filePath = "uploads/socialwelfare/".$fileName;
                        $fileType = $value['file']->getClientOriginalExtension();
                        $value['file']->move(public_path('uploads/socialwelfare'), $fileName);
                        // $fileName = $value['file']->getClientOriginalName();
                    }
                    $add = AssistanceFile::updateOrCreate(
                        [
                            'wswa_id' => $data->id,//assistance id
                            'id' => $key,//file id
                        ],
                        [
                            'wsr_id' => $value['req_id'], // req id
                            'wsr_type' => $value['req_type'], // req type
                            'fwa_name' => $fileName,//file name
                            'fwa_type' => $fileType,//file type
                            'fwa_size' => $fileSize, // file size
                            'fwa_path' => $filePath,
                            'fwa_is_active' => 1, // file size
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }
            }
        }
    }
    
    public function addCaseStudy($id, $request)
    {
        $family = isset($request['family']) ? $request['family'] : null;
        $treatment = isset($request['treatment']) ? $request['treatment'] : null;
        unset($request['family']);
        unset($request['treatment']);
        $request['wswa_id'] = $id;
        if ($request['id']) {
            $id = $request['id'];
            unset($request['id']);
            $case_study_id = CaseStudy::updateData($id,$request);
        } else {
            $case_study_id = CaseStudy::addData($request);
        }

        if ($family) {
            foreach ($family as $key => $value) {
                Citizen::updateData($value['cit_id'],$value['data']);
                CaseStudyFamily::updateOrCreate(
                    [
                        'wswsc_id' => $case_study_id,//assistance id
                        'id' => $key,//file id
                    ],
                    [
                        'wswscd_cit_id' => $value['cit_id'],//dependent name
                        'wswscd_relation' => ($value['relation'])? $value['relation']:'', // dependant relation
                        'wswscd_health_status' => ($value['health'])? $value['health']:'', // dependant relation
                        // 'updated_at' => date('Y-m-d H:i:s'),
                        // 'wswscd_is_active' => 1,
                    ]
                );
            }
        }
        if ($treatment) {
            foreach ($treatment as $key => $value) {
                // $value['wswsc_treatment_is_active'] = 1;
                CaseStudyTreatment::updateOrCreate(
                    [
                        'wswsc_id' => $case_study_id,//assistance id
                        'id' => $key,//file id
                    ],
                    $value
                );
            }
        }
    }

    public function sendRequestLetter($request)
    {
        try {
            RequestLetter::updateOrCreate(
                [
                    'wswa_id' => $request->wswa_id,//assistance id
                ],
                [
                    'wswart_body' => $request->wswart_body
                ]
            );
            return 'Request Letter Send';
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getList($request)
    {
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }
        $columns = array( 
            1 =>"cit_fullname",
            2 =>"cit_full_address",   
            3 =>"cit_age",   
            4 =>"wsat_description",   
            5 =>"wswa_amount",   
            6 =>"wswa_is_active",   
        );
        $sql = $this->select('welfare_social_welfare_assistance.*','cit_fullname','cit_full_address','cit_age','wsat_description')->join('citizens', 'citizens.id', '=', 'welfare_social_welfare_assistance.cit_id')->join('welfare_swa_assistance_type', 'welfare_swa_assistance_type.id', '=', 'welfare_social_welfare_assistance.wsat_id');
        if(!empty($q) && isset($q)){
            $sql =  $sql->where(function ($query) use($q) {
                        $query->where(DB::raw('LOWER(cit_fullname)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(cit_full_address)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(cit_age)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(wsat_description)'),'like',"%".strtolower($q)."%");
                        $query->orWhere(DB::raw('LOWER(wswa_amount)'),'like',"%".strtolower($q)."%");
                    });                
        }
    /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
            $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
            $sql->orderBy('welfare_social_welfare_assistance.id','DESC');

    /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    public function selectEmployee($request){
        $data = [];
        $q=$request->input('q');
        $brgy = HrEmployee::where('is_active', 1)->take(5)->get();
        if(!empty($q) && isset($q)){
            $brgy = HrEmployee::where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($q)."%")
            ->orWhere(DB::raw('LOWER(middlename)'),'like',"%".strtolower($q)."%")
            ->orWhere(DB::raw('LOWER(lastname)'),'like',"%".strtolower($q)."%")
            ->where('is_active', 1)
            ->take(10)
            ->get();
        }
        foreach ($brgy as $key => $value) {
            $data += [$value->id => $value->fullname];
        }
        return $data;
    }
    public function getEmployee($search="")
    {
        $page=1;
        if(isset($_REQUEST['page'])){
        $page = (int)$_REQUEST['page'];
        }
        $length = 20;
        $offset = ($page - 1) * $length;
        $sql = HrEmployee::where('is_active',1);
        if(!empty($search)){
            $sql->where(function ($sql) use($search) {
                    if(is_numeric($search)){
                        $sql->Where('id',$search);
                    }else{
                        $sql->where(DB::raw('LOWER(fullname)'),'like',"%".strtolower($search)."%");
                    }
            });
        }
        $sql->orderBy('fullname','ASC');
        $data_cnt=$sql->count();
        $sql->offset((int)$offset)->limit((int)$length);
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
    function getObrIdAttribute()//obr_id
    {
        $code = 'AICS';
        $obr = DB::table('cbo_obligation_types')->select('*')->where('code', $code)->first();
        return $obr ? $obr : []; 
        
    }
    function getFundCodeAttribute()//fund_code
    {
        $code = 101;
        $fund = DB::table('acctg_fund_codes')->select('*')->where('code', $code)->first();
        return $fund ? $fund : []; 
        
    }

    function getGlCodeAttribute()//gl_code
    {
        $code = 50299080;
        $fund = DB::table('acctg_account_general_ledgers')->select('*')->where('code', $code)->first();
        return $fund ? $fund : []; 
        
    }

    public function generateBudgetControlNo($year)
    {   
        $count  = CboAllotmentObligation::where('budget_year', $year)->count();
        $controlNo = $year.'-';
        if($count < 9) {
            $controlNo .= '0000' . ($count + 1);
        } else if($count < 99) {
            $controlNo .= '000' . ($count + 1);
        } else if($count < 999) {
            $controlNo .= '00' . ($count + 1);
        } else if($count < 9999) {
            $controlNo .= '0' . ($count + 1);
        } else {
            $controlNo .= ($count + 1);
        }
        return $controlNo;
    }
    
    function sendOBR() 
    {
        $now = Carbon::now();
        $timestamp = $now->toDateString();
        $obr = $this->obr_id;
        $user = Auth::user();
        $employee = $user->hr_employee;
        $allotment = CboAllotmentObligation::create([
            'obligation_type_id' => $obr->id,
            'budget_control_no' => $this->generateBudgetControlNo(date('Y')),
            'department_id' => $employee->acctg_department_id,
            'division_id' => $employee->acctg_department_division_id,
            'fund_code_id' => $this->fund_code->id,
            'employee_id' => $user->id,
            'designation_id' => $employee->hr_designation_id,
            'with_pr' => 0,
            'particulars' => 'To payment of AICS in the amount of...',
            'total_amount' => $this->wswa_amount,
            'address' => $this->claimant->cit_full_address,
            'budget_year' => date('Y'),
            'created_at' => $timestamp,
            'created_by' => $user->id
        ]);
        $request = CboAllotmentObligationRequest::create([
            'allotment_id' => $allotment->id,
            'status' => 'completed',
            'sent_at' => $timestamp,
            'sent_by' =>$user->id,
            'created_at' => $timestamp,
            'created_by' => $user->id
        ]);
        $alllotBrkdwn = CboAllotmentBreakdown::create([
            'allotment_id' => $allotment->id,
            // 'budget_breakdown_id' => $breakdown,
            'gl_account_id' => $this->gl_code->id,
            'amount' => $this->wswa_amount,
            'created_at' => $timestamp,
            'created_by' => $user->id
        ]);
        return $allotment;
    }
    public function approve($sequence)
    {
        $user = Auth::user()->hr_employee;        
        if ((int)$sequence === 1) {
            $this->update(['wswa_approved_by' => $user->id]);
        } elseif ((int)$sequence === 2) {
            $this->update(['wswa_second_approver_by' => $user->id]);
            $this->sendOBR();
        }
        $this->update(['wswa_approve_status' => $sequence]);
        return $user->fullname;
    }
    
}
