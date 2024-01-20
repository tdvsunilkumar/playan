<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\Citizen;
use App\Models\SocialWelfare\PWD;
use App\Models\CommonModelmaster;
use App\Models\Country;
use Illuminate\Validation\Rule;
use DB;
use Carbon\Carbon;
use File;
use App\Traits\ModelUpdateCreate;

class CitizenController extends Controller
{
    use ModelUpdateCreate;
    public $data = [];
    public $postdata = [];
    private $slugs;

    public function __construct(){

        $this->_Citizen = new Citizen(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'cit_last_name'=>'',
            'cit_first_name'=>'',
            'cit_middle_name'=>'',
            'cit_suffix_name'=>'',
            'cit_house_lot_no'=>'',
            'cit_street_name'=>'',
            'cit_subdivision'=>'',
            'brgy_id'=>'',
            'cit_brgy_name'=>'',
            'cit_gender'=>'',
            'ccs_id'=>'',
            'cit_date_of_birth'=>'',
            'cit_age'=>'',
            'cit_place_of_birth'=>'',
            'cit_blood_type'=>'',
            'cit_mobile_no'=>'',
            'cit_telephone_no'=>'',
            'cit_fax_no'=>'',
            'cit_tin_no'=>'',
            'country_id' => Country::where('is_default',1)->first()->id,
            'cit_nationality'=>'',
            'cit_email_address'=>'',
            'cea_id'=>'',
            'cit_height'=>'',
            'cit_weight'=>'',
            'cit_sss_no'=>'',
            'cit_gsis_no'=>'',
            'cit_pagibig_no'=>'',
            'cit_psn_no'=>'',
            'cit_is_active'=>1,
            'cit_philhealth_no'=>'',
            'cit_occupation'=>'',
            'irc_no'=>''
        );  
        $this->slugs = ['citizens','health-safety-citizens'];
    }

    public function isPermitted($slugs, $permission)
    {
        $permissions = [];
        foreach ($slugs as $value) {
            $permissions[$value] = $this->is_permitted($value, $permission,1);
        }
        $permits = array_search(true, $permissions);
        return $this->is_permitted($permits, $permission);
    }

    public function index(Request $request)
    {   
        $this->isPermitted($this->slugs, 'read');
        $isopen=$request->input('isopenAddform');
        return view('SocialWelfare.citizen.index',compact('isopen'));

    }
    public function uploadAttachment(Request $request){
        $id = $request->input('id');
        $arrCitizen = $this->_Citizen->getCitizenDetails($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/document_requirement_Citizen/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrCitizen)){
                    $arrJson = json_decode($arrCitizen->doc_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['doc_json'] = json_encode($finalJsone);
                $this->_Citizen->updateCitizen($id,$data);
                $arrDocumentList = $this->generateDocumentList($data['doc_json'],$id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson){
        $html = "";
        
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $html .= "<tr>
                  <td>".$val['filename']." </td>
                  <td><a class='btn' href='".asset('uploads/document_requirement_Citizen').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                   <td>
                        <div class='action-btn bg-danger ms-2'>
                            <a href='#' class='mx-3 btn btn-sm deleteDocument ti-trash text-white text-white ' rid='".$val['filename']."'></a>
                        </div>
                    </td>
                </tr>";
            }
        }
        return $html;
    }

    public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arrCitizen = $this->_Citizen->getCitizenDetails($id);
        if(isset($arrCitizen)){
            $arrJson = json_decode($arrCitizen->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement_Citizen/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_Citizen->updateCitizen($id,$data);
                    echo "deleted";
                }
            }
        }
    }
    public function getList(Request $request){
        // $this->isPermitted($this->slugs, 'read');
        $data=$this->_Citizen->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            // dd($row);
            if ($this->isPermitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/citizens/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Manage Citizen Details">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            
            if ($this->isPermitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->cit_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Citizen"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Citizen"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['cit_fullname']=$row->cit_fullname;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['birthdate']=Carbon::parse($row->cit_date_of_birth)->format('M d, Y');
            $arr[$i]['gender']=$row->gender;
    
            $arr[$i]['is_active']=($row->cit_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
            $i++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }

    public function store(Request $request){
        $id =  $request->input('id');
        if($request->input('id')>0){
            $this->isPermitted($this->slugs, 'update');
        }else{
            $this->isPermitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if(($request->input('id')>0 && $request->input('submit')=="")){
            $citid = $request->input('id');
        }
        if (isset($request->id)) {
            $citid = $request->id;
        }
        $barangays = array();
        if (isset($citid)) {
            $data = $this->_Citizen->getEditDetails($citid);
            foreach ($this->_commonmodel->getBarangay($data->brgy_id)['data'] as $val) {
                $barangays[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
            }
            $data['cit_brgy_name'] = Citizen::find($citid)->brgy_name();
            $data['cit_nationality'] = Citizen::find($citid)->nationality();
        }
        if ($request->field) {
            $data->field = $request->field;
        }
        $arrdocDtls="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $dataDetails =$this->_Citizen->getCitizenEdit($id);
            // dd($dataDetails);exit;

                if($dataDetails){
                    $arrdocDtls = $this->generateDocumentList($dataDetails->doc_json,$dataDetails->id);
                      // echo "<pre>"; print_r($arrdocDtls); exit;
                }else{
                    $arrdocDtls="";
                }
        } 
            // dd($request->post());
        if(isset($request->button)){
            unset($this->data['cit_brgy_name']);
            unset($this->data['cit_nationality']);
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['cit_is_active'] = 1;
            $suffix = $request->input('cit_suffix_name') ? ', '.$request->input('cit_suffix_name') : '';
            $this->data['cit_fullname']=$request->input('cit_first_name').' '.$request->input('cit_middle_name').' '.$request->input('cit_last_name').$suffix;
            $this->data['cit_modified_by']=\Auth::user()->creatorId();
            $this->data['cit_modified_date'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_Citizen->updateData($request->input('id'),$this->data);
                
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated  "; 
            }else{
                $this->data['cit_created_by']=\Auth::user()->creatorId();
                $this->data['cit_created_date'] = date('Y-m-d H:i:s');
                // $this->data['btype_status'] = 1;
                $request->id = $this->_Citizen->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            if ($request->field) {
                return json_encode([
                    'ESTATUS' => 0,
                    'field' => $request->field, 
                    'id' => $request->id,
                    'name' => $this->data['cit_fullname']
                ]);
            } else if ($request->field && $request->field2) {
                return json_encode([
                    'ESTATUS' => 0,
                    'field' => $request->field, 
                    'id' => $request->id,
                    'name' => $this->data['cit_fullname']
                ]);
            } else {
                // return json_encode([
                //     'ESTATUS' => 0,
                //     'id' => $request->id,
                //     'name' => $this->data['cit_fullname']
                // ]);
                return redirect()->back()->with('success', __($success_msg));
            }
        }
            // for select

            
            $nationality = Citizen::getNationality();
            $educ = config('constants.citEducationalAttainment');
            $civilstat = config('constants.citCivilStatus');
            
            
        return view('SocialWelfare.citizen.create',compact('data', 'educ', 'barangays', 'civilstat', 'nationality', 'arrdocDtls'));
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                "cit_last_name" => "required",
                "cit_first_name" => "required",
                // "cit_middle_name" => "required",
                "brgy_id" => "required",
                "cit_date_of_birth" => "required|date|before_or_equal:today",
                "cit_age" => "required",
                "cit_mobile_no" => "nullable|min:10|regex:/^[+]?[0-9\s-]*$/ ",
                "cit_telephone_no" => "nullable|regex:/^[+]?[0-9\s-]*$/",
                "cit_tin_no" => "nullable|min:17|max:17|regex:/^[0-9-]+$/",
               //"cit_email_address" => "nullable|email|unique:citizens",
				'cit_email_address'=>'nullable|regex:/(.+)@(.+)\.(.+)/i|unique:citizens,cit_email_address,'.$request->input('id'),
                "cit_philhealth_no" => "nullable|min:14|max:14|regex:/^[0-9-]+$/",
            ],[
                "cit_last_name.required" => "Last Name is required",
                "cit_first_name.required" => "First Name is required",
                // "cit_middle_name.required" => "/Middle Name is required",
                "brgy_id.required" => "Barangay is required",
                "cit_date_of_birth.required" => "Date is required",
				"cit_date_of_birth.before_or_equal" => "The date of birth must be a date before or equal to today.",
                "cit_age.required" => "Age is required",
                // "cit_mobile_no.required" => "Mobile Number is required",
				"cit_mobile_no.min" => "Mobile No. correct format",
				"cit_mobile_no.regex" => "Mobile No. correct format",
                "cit_telephone_no.regex" => "Invalid telephone number, Please try another",
                // "cit_telephone_no.required" => "Telephone Number is required",
                "cit_tin_no.min" => "Tin number must be 9 or 12 digits",
                "cit_tin_no.max" => "Tin number must be 9 or 12 digits",
                // "cit_email_address.required" => "Email Address is required",
				"cit_email_address.unique" => "This Email Address already exist",
				"cit_email_address.regex" => "Invalid Email Address",
                // "cit_philhealth_no.required" => "Philhealth number is required",
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        $suffix = $request->cit_suffix_name ? ', '.$request->cit_suffix_name : '';
            $fullname=$request->cit_first_name.' '.$request->cit_middle_name.' '.$request->cit_last_name.$suffix;
            
        $exist = $this->_Citizen->where('cit_fullname',$fullname)->first();
        if ($exist && !($request->id)) {
            $arr['field_name'] = 'cit_last_name';
            $arr['error'] = 'The First Name, Middle Name, Last Name and Suffix has already been taken';
            $arr['ESTATUS'] = 1;
        }
		if ($exist && ($request->id != $exist->id)) {
            $arr['field_name'] = 'cit_last_name';
            $arr['error'] = 'The First Name, Middle Name, Last Name and Suffix has already been taken';
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function ActiveInactive(Request $request)
    {
        $this->isPermitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('cit_is_active' => $is_activeinactive);
        $citizen = $this->_Citizen->updateData($id,$data);
        // dd($citizen);
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Citizen ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        echo json_encode($action);
    }

    public function getCitizens(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Citizen = $this->_Citizen->getCitizen($q);
        foreach ($Citizen['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->cit_fullname;
        }
        $data['data_cnt']=$Citizen['data_cnt'];
        echo json_encode($data);
    }
    public function getCitizenMunicipalOnly(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Citizen = $this->_Citizen->getCitizenMunicipalOnly($q);
        foreach ($Citizen['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->cit_fullname;
        }
        $data['data_cnt']=$Citizen['data_cnt'];
        echo json_encode($data);
    }

    public function getCitizen(Request $request)
    {
        $id = $request->id;
        $data = [];
        $Citizen = Citizen::find($id);
        $Citizen->age = $Citizen->age;
        $Citizen->human_age = $Citizen->age_human;
        $Citizen->gender = $Citizen->gender;
        $Citizen->educ = $Citizen->education();
        $Citizen->status = $Citizen->status();
        $Citizen->nationality = $Citizen->nationality();
        $Citizen->municipality = $Citizen->brgy->municipality->mun_desc;
        $Citizen->province = $Citizen->brgy->province->prov_desc;
        $Citizen->region = $Citizen->brgy->region->reg_region;
        $Citizen->brgy_name = $Citizen->brgy->brgy_name;
        $Citizen->municipality_id = $Citizen->brgy->municipality->id;
        $Citizen->province_id = $Citizen->brgy->province->id;
        $Citizen->region_id = $Citizen->brgy->region->id;
        // for pwd id
        $PWD = new PWD(); 
        $pwdid = $PWD->default_region.'-'.$PWD->default_province.$PWD->default_Municipal;
        $local_code = $this->_Citizen->locality()->loc_local_code;

        $Citizen->pwd_id = $pwdid.'-'.$Citizen->brgy->uacs_code.'-'.$PWD->next_number;
        $Citizen->loc_local_code = $local_code;
        $Citizen->barangay_uacs_code = $Citizen->brgy->uacs_code;
        $Citizen->barangay_pwd_no = $PWD->next_number;

        // get Med ID
        $Citizen->med_id = ($Citizen->medical_record) ? $Citizen->medical_record->rec_card_num:'';

        echo json_encode($Citizen);
    }

    public function getBrgy(Request $request)
    {
        $data = [];
        $Citizen = Citizen::selectBrgy($request);
        foreach ($Citizen as $key => $value) {
            $data[] = array('id' => $key, 'text' => $value);
        }
        echo json_encode($data);
    }

    public function getNationality(Request $request)
    {
        $data = [];
        $Citizen = Citizen::selectNationality($request);
        foreach ($Citizen as $key => $value) {
            $data[] = array('id' => $key, 'text' => $value);
        }
        echo json_encode($data);
    }

    
}
