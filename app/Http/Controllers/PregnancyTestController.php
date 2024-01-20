<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\PregnancyTestModel;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\HoLabRequest;
use Carbon\Carbon;
use DB;
use File;
class PregnancyTestController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $getcitizens = array(""=>"Please Select");
	 public $getPhysician = array(""=>"Please Select");
     public function __construct(){
        $this->_PregnancyTestModel = new PregnancyTestModel(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'hp_code'=>'',
            'med_tech_position'=>'',
            'health_officer_position'=>'',
            'esign_is_approved'=>'',
			'officer_is_approved'=>'',
            'med_tech'=>'',
            'health_officer'=>'',
            'lab_req_id'=>'',
            'lab_control_no'=>'',
            'pt_lab_no'=>'',
            'pt_date'=>'',
            'pt_age'=>'',
            'pt_or_num'=>'',
            'pt_lab_num'=>'',
            'pt_specimen'=>'',
            'pt_brand_lot'=>'',
            'pt_expiry'=>'',
            'pt_result'=>'',
            'pt_remarks'=>''
        );  
        $this->slugs = 'pregnancy-test';
		foreach ($this->_PregnancyTestModel->getCitizens() as $val) {
             $this->getcitizens[$val->id]=$val->cit_fullname;
        }
		foreach ($this->_PregnancyTestModel->getPhysician() as $val) {
             $this->getPhysician[$val->id]=$val->fullname;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('PregnancyTest.index');
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_PregnancyTestModel->getList($request);
		$getcitizens  = $this->getcitizens;
		$getphysician = $this->getPhysician;
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/pregnancy-test/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Pregnancy Test">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
			$actions .='<div class="action-btn bg-info ms-2">
				<a href="'.url('/pregnancy-test/print/'.$row->id).'" target="_blank" class="mx-1 btn btn-sm  align-items-center digital-sign-btn" title="Print"  data-title="Print"><i class="ti-printer text-white" ></i></a>
				</div>';
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->pt_is_active == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
			
			$pt_remarks = wordwrap($row->pt_remarks,40, "<br />\n");
			
            $arr[$i]['srno']= $sr_no;
            $arr[$i]['pt_lab_num']= $row->pt_lab_num;
			$arr[$i]['cit_id']= $getcitizens[$row->cit_id];
            $arr[$i]['pt_age']= $row->patient->age_human;
            $arr[$i]['cit_gender']= ($row->cit_gender==1?'Female':'Male');
            $arr[$i]['brgy_name']= $row->brgy_name;
            $arr[$i]['pt_or_num']= $row->pt_or_num;
            $arr[$i]['created_at']= Carbon::parse($row->pt_date)->format('M d, Y g:i A');
			$arr[$i]['pt_remarks']="<div class='showLess'>".$pt_remarks."</div>";
            $arr[$i]['pt_result']= ($row->pt_result==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Positive</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Negative</span>');
            $arr[$i]['pt_is_posted']= ($row->pt_is_posted==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
			$arr[$i]['pt_is_active']= ($row->pt_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
			
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
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('pt_is_active' => $is_activeinactive);
        $this->_PregnancyTestModel->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Pregnancy Test ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$getcitizens = $this->getcitizens;
		$getphysician = $this->getPhysician;
		$current_years=date('Y');
        $data = (object)$this->data;

		$user_savedata = array();
		$user_savedata['med_tech_id']       = $request->input('med_tech');
		$user_savedata['med_tech_position'] = $request->input('med_tech_position');
		$user_savedata['health_officer_id'] = $request->input('health_officer');
		$user_savedata['health_officer_position']= $request->input('health_officer_position');
        $user_savedata['esign_is_approved']= $request->input('esign_is_approved');
        
		$user_savedata['pregnancytest_id']       = $request->input('id');
		$userlastdata = array();
		$userlastdata['form_id'] = 38;
		$userlastdata['user_id'] = \Auth::user()->id;
		$userlastdata['is_data'] = json_encode($user_savedata);
		$userlastdata['created_at'] = date('Y-m-d H:i:s');
		$userlastdata['updated_at'] = date('Y-m-d H:i:s');
		$checkisexist = $this->_PregnancyTestModel->CheckFormdataExist('38',\Auth::user()->id);
		if(!empty($checkisexist[0]->is_data)){
			$last_user_data = json_decode($checkisexist[0]->is_data);
		}else{
			$aaaa= json_encode($user_savedata);
			$last_user_data = json_decode($aaaa);
		}		
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_PregnancyTestModel->find($request->input('id'));
        }
        if($request->isMethod('post')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[urldecode($key)] = urldecode($request->input($key));
            }
            $this->data['pt_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                if ($request->input('button') === 'submit') {
                    $this->data['pt_is_posted'] = 1;
                }
                $this->_PregnancyTestModel->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $checkisexist = $this->_PregnancyTestModel->CheckFormdataExist('38',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_PregnancyTestModel->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_PregnancyTestModel->addusersaveData($userlastdata);
                }
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Pregnancy Test '".$this->data['pt_remarks']."'"; 
            }else{
				//$incre_no_req_id=$this->generatelabreqidNumber("00");
				//$this->data['lab_req_id']= $incre_no_req_id;
				//$this->data['lab_control_no']= $current_years."-".$incre_no_req_id;
				$this->data['pt_lab_year'] = $current_years;
				$incre_no_lab=$this->generatehemalabnoNumber(" ");
				$this->data['pt_lab_no'] = $incre_no_lab;
				$this->data['pt_lab_num']= $current_years."-".$incre_no_lab;
                $this->data['pt_created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['pt_is_active'] = 1;
                $this->data['pt_is_posted'] = 0;
                $request->id = $this->_PregnancyTestModel->addData($this->data);
                $success_msg = 'Added successfully.';
				$checkisexist = $this->_PregnancyTestModel->CheckFormdataExist('38',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_PregnancyTestModel->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_PregnancyTestModel->addusersaveData($userlastdata);
                }
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Pregnancy Test '".$this->data['pt_remarks']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);

            return json_encode(
                [
                    'ESTATUS'=>0,
                    'msg'=>$success_msg,
                    'data' => $data
                ]
            );
        }
        if (($request->input('lab_id'))) {
            $lab_request = HoLabRequest::find($request->input('lab_id'));
            // dd($lab_request);
            $data->lab_control_no = $lab_request->lab_control_no;
            $data->cit_id = $lab_request->cit_id;
            $data->pt_or_num = $lab_request->get_or_results(5);
            $data->lab_req_id = $lab_request->id;
            $data->patient = $lab_request->patient;
            $data->pt_date = $lab_request->lab_reg_date;
            $lab_results = $this->_PregnancyTestModel->where('lab_control_no',$lab_request->lab_control_no)->first();
            if ($lab_results) {
                $data = $lab_results;
            }
        }
		if($data->med_tech > 0){ 
            $esignisapproveds = $this->_PregnancyTestModel->selectHRemployees($data->med_tech); 
        }else{
            $esignisapproveds = 0;
        }
		if($data->health_officer > 0){ 
            $officerisapproved = $this->_PregnancyTestModel->selectHRemployees($data->health_officer); 
        }else{
            $officerisapproved = 0;
        }
        return view('PregnancyTest.create',compact('data','officerisapproved','getcitizens','getphysician','last_user_data','esignisapproveds'));
    }
    
    public function generatelabreqidNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_pregnancy')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->lab_req_id;
            } else {
              $last_booking='0000';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                   $last_booking='0000';
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 2, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
	public function generatehemalabnoNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_pregnancy')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->pt_lab_no;
            } else {
              $last_booking='0000';
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                   $last_booking='0000';
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 4, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'pt_or_num'=>'required',
                'pt_result'=>'required',
                'med_tech'=>'required',
                'health_officer'=>'required',
                'med_tech_position'=>'required',
                'health_officer_position'=>'required',
            ], [
                'pt_result.required' => 'Result is Required',
                'med_tech.required' => 'Medical Technologist is Required',
                'health_officer.required' => 'Health Officer is Required',
                'med_tech_position.required' => 'Medical Technologist Position is Required',
                'health_officer_position.required' => 'Health Officer Position is Required',
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
	
    public function getCitizensname(Request $request){
	  $id = $request->input('id');
	  $data = $this->_PregnancyTestModel->getCitizensname($id);
	  return $data;
	}

    public function submit(Request $request, $id)
    {
        $this->_PregnancyTestModel->updateData($id,['pt_is_posted'=>1]);
        $arr = [];
        $arr['ESTATUS'] = 0;
        return json_encode($arr);
    }

    public function print(Request $request, $id)
    {
        # code...
    }
	public function getDesignation($employee_id){
    	try{
			$designation = $this->_PregnancyTestModel->getDesignation($employee_id);
			return response()->json(['status' => 200, 'data' => $designation]);
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = PregnancyTestModel::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/pregnancytest/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['doc_id'] = count($arrJson)+1;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->doc_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['doc_json'] = json_encode($finalJsone);
                $this->_PregnancyTestModel->updateData($healthCertId,$data);
                $arrDocumentList = $this->generateDocumentList($data['doc_json'],$healthCertId);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    public function generateDocumentList($arrJson,$healthCertid){
        $html = "";
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td>".$val['filename']."</td>
                        <td>
                            <div class='action-btn ms-2'>
                                <a class='btn' href='".asset('uploads/pregnancytest').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
                            </div>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' doc_id='".$val['doc_id']."' healthCertid='".$healthCertid."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function deleteAttachment(Request $request){
        $healthCertid = $request->input('healthCertid');
        $doc_id = $request->input('doc_id');
        $arrEndrosment = PregnancyTestModel::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/pregnancytest/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_PregnancyTestModel->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
