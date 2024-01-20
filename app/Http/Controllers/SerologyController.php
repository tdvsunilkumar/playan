<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\SerologyModel;
use App\Models\SerologyDetails;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
use App\Models\HoLabRequest;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\HealthSafetySetupDataService;
use File;
class SerologyController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $getcitizens = array(""=>"Please Select");
	 public $getPhysician = array(""=>"Please Select");
	 public $ArrScreeningTest = array(""=>"Please Select");
     public function __construct(){
        $this->_SerologyModel = new SerologyModel(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','cit_id'=>'','hp_code'=>'','med_tech'=>'','officer_is_approved'=>'','med_tech_position'=>'','health_officer_position'=>'','health_officer'=>'','esign_is_approved'=>'','lab_req_id'=>'','lab_control_no'=>'','ser_date'=>'','ser_age'=>'','ser_or_num'=>'','ser_lab_num'=>'','ser_lab_no'=>'','ser_remarks'=>'');
        $this->slugs = 'serology';
		foreach ($this->_SerologyModel->getCitizens() as $val) {
             $this->getcitizens[$val->id]=$val->cit_fullname;
        }
		foreach ($this->_SerologyModel->getPhysician() as $val) {
             $this->getPhysician[$val->id]=$val->fullname;
        }
		//$SyphilisScreeningTest =config('constants.SyphilisScreeningTest');
		foreach ($this->_SerologyModel->getScreeningTest() as $val) {
             $this->ArrScreeningTest[$val->id]= $val->ser_m_method;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Serology.index');
    }

    
    public function getList(Request $request){

        $this->is_permitted($this->slugs, 'read');
        $data=$this->_SerologyModel->getList($request);
		$getcitizens  = $this->getcitizens;
		$getphysician = $this->getPhysician;
		$ScreeningTest = $this->ArrScreeningTest;
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/serology/store?id='.$row->id).'&cit_id='.$row->cit_id.'&lab_req_id='.$row->lab_req_id.'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Serology">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
			$actions .='<div class="action-btn bg-info ms-2">
				<a href="'.url('/serology/print/'.$row->id).'" target="_blank" class="mx-1 btn btn-sm  align-items-center digital-sign-btn" title="Print"  data-title="Print"><i class="ti-printer text-white" ></i></a>
				</div>';
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->ser_is_active == 1) ? '<div class="action-btn btn-sm btn-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
			$ser_remarks = wordwrap($row->ser_remarks,40, "<br />\n");
			
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ser_lab_num']=$row->ser_lab_num;
			$arr[$i]['cit_id']=$getcitizens[$row->cit_id];
            $arr[$i]['ser_age']=$row->patient->age_human;
            $arr[$i]['sex']=$row->cit_gender==1?'Female':'Male';
            $arr[$i]['address']=$row->brgy_name;
            $arr[$i]['ser_or_num']=$row->ser_or_num;
            $arr[$i]['ser_remarks']="<div class='showLess'>".$row->ser_remarks."</div>";
            $arr[$i]['ser_date']=Carbon::parse($row->ser_date)->format('M d, Y g:i A');
			$arr[$i]['ser_is_posted']=($row->ser_is_posted==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Posted</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Draft</span>');
            $arr[$i]['ser_is_active']=($row->ser_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ser_is_active' => $is_activeinactive);
        $this->_SerologyModel->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Serology status ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    
	public function update(Request $request){
		 
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['ser_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_SerologyModel->updateData($request->input('id'),$this->data);
				$y=count($_POST['ho_service_id']);
					$i=0;
					while($i < $y){
						 if(isset($_POST['serology_details_id'][$i]) > 0){
							 SerologyDetails:: where('id', $_POST['serology_details_id'][$i])
							->update(['ser_id' => $request->input('id'),'ho_service_id'=>$_POST['ho_service_id'][$i],'sm_id'=>$_POST['sm_id'][$i],'ser_specimen'=>$_POST['ser_specimen'][$i],'ser_brand'=>$_POST['ser_brand'][$i],'ser_lot'=>$_POST['ser_lot'][$i],'ser_exp'=>$_POST['ser_result'][$i]]);
						 
						 }else{
							 $serologydetail = new SerologyDetails();
							 $serologydetail->ser_id = $request->input('id');
							 $serologydetail->ho_service_id = $_POST['ho_service_id'][$i];
							 $serologydetail->sm_id = $_POST['sm_id'][$i];
							 $serologydetail->ser_specimen = $_POST['ser_specimen'][$i];
							 $serologydetail->ser_brand = $_POST['ser_brand'][$i];
							 $serologydetail->ser_lot = $_POST['ser_lot'][$i];
							 $serologydetail->ser_exp = $_POST['ser_exp'][$i];
							 $serologydetail->ser_result = $_POST['ser_result'][$i];
							 $serologydetail->created_by = \Auth::user()->creatorId();
							 $serologydetail->created_at = date('Y-m-d H:i:s');
							 $serologydetail->save(); 
						 }
						$i++;
					}
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Serology '".$this->data['ser_remarks']."'"; 
            }else{
				//$incre_no_req_id=$this->generatelabreqidNumber("00");
				//$this->data['lab_req_id']= $incre_no_req_id;
				//$this->data['lab_control_no']= $current_years."-".$incre_no_req_id;
				// $this->data['ser_lab_year'] = $current_years;
				$incre_no_lab=$this->generatehemalabnoNumber("00");
				$this->data['ser_lab_no'] = $incre_no_lab;
				$this->data['ser_lab_num']= $current_years."-".$incre_no_lab;
                $this->data['ser_created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ser_is_active'] = 1;
                $request->id = $this->_SerologyModel->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Serology '".$this->data['ser_remarks']."'"; 
            }
			return $logDetails;

	}
	
	
    public function store(Request $request){
		$cit_id =  $request->input('cit_id');
        $lab_req_id =  $request->input('lab_req_id');
		
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		
		$Laboratorytest = $this->_SerologyModel->getTestlistlab($cit_id);
		//echo '<pre>';
		//print_r($Laboratorytest);exit;
		
		$getcitizens  = $this->getcitizens;
		$getphysician = $this->getPhysician;
		$ScreeningTest = $this->ArrScreeningTest;
		$current_years=date('Y');
        $data = (object)$this->data;
		
		$user_savedata = array();
		$user_savedata['med_tech_id']       = $request->input('med_tech');
		$user_savedata['med_tech_position'] = $request->input('med_tech_position');
		$user_savedata['health_officer_id'] = $request->input('health_officer');
		$user_savedata['health_officer_position']= $request->input('health_officer_position');
        $user_savedata['esign_is_approved']= $request->input('esign_is_approved');
		if($request->input('id')>0){
			$user_savedata['serology_id']       = $request->input('id');
		}else{
			$user_savedata['serology_id']       = $request->id;	
		}
		$userlastdata = array();
		$userlastdata['form_id'] = 35;
		$userlastdata['user_id'] = \Auth::user()->id;
		$userlastdata['is_data'] = json_encode($user_savedata);
		$userlastdata['created_at'] = date('Y-m-d H:i:s');
		$userlastdata['updated_at'] = date('Y-m-d H:i:s');
		$checkisexist = $this->_SerologyModel->CheckFormdataExist('35',\Auth::user()->id);
		if(!empty($checkisexist[0]->is_data)){
			$last_user_data = json_decode($checkisexist[0]->is_data);
		}else{
			$aaaa= json_encode($user_savedata);
			$last_user_data = json_decode($aaaa);
		}
		if(count($this->_SerologyModel->getlabResult($request->input('id'))) > 0){
			$Laboratoryresult = $this->_SerologyModel->find($request->input('id'));
		}else{
			$Laboratoryresult=array();
		}
        if($request->isMethod('post')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['ser_modified_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                if ($request->input('button') === 'submit') {
                    $this->data['ser_is_posted'] = 1;
                }
                $this->_SerologyModel->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';

                $checkisexist = $this->_SerologyModel->CheckFormdataExist('35',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_SerologyModel->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_SerologyModel->addusersaveData($userlastdata);
                }
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Serology '".$this->data['ser_remarks']."'"; 
                $ser_id = $request->input('id');
            }else{
				//$incre_no_req_id=$this->generatelabreqidNumber("00");
				//$this->data['lab_req_id']= $incre_no_req_id;
				//$this->data['lab_control_no']= $current_years."-".$incre_no_req_id;
				$this->data['ser_lab_year'] = $current_years;
				$incre_no_lab=$this->generatehemalabnoNumber(" ");
				$this->data['ser_lab_no'] = $incre_no_lab;
				$this->data['ser_lab_num']= $current_years."-".$incre_no_lab;
                $this->data['ser_created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ser_is_active'] = 1;
				$this->data['ser_is_posted'] = 0;
                $request->id = $this->_SerologyModel->addData($this->data);
                $success_msg = 'Added successfully.';
				$checkisexist = $this->_SerologyModel->CheckFormdataExist('35',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_SerologyModel->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_SerologyModel->addusersaveData($userlastdata);
                }
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Serology '".$this->data['ser_remarks']."'"; 
                $ser_id = $request->id;
            }

            // for serology details
            foreach ($request->input('field') as $key => $value) {
                if ($value['disabled'] == 'true') {
                    if($value['id'] > 0 ){
                        SerologyDetails::where('id', $value['id'])
                            ->update([
                                'ser_id' => $ser_id,
                                'ho_service_id'=>$key,
                                'sm_id'=>isset($value['sm_id'])?$value['sm_id']:0,
                                'ser_specimen'=>$value['ser_specimen'],
                                'ser_brand'=>$value['ser_brand'],
                                'ser_lot'=>$value['ser_lot'],
                                'ser_exp'=>$value['ser_exp'],
                                'ser_result'=>isset($value['ser_result'])?$value['ser_result']:2
                            ]);
                    }else{
                            $serologydetail = new SerologyDetails();
                            $serologydetail->ser_id = $ser_id;
                            $serologydetail->ho_service_id = $key;
                            $serologydetail->ser_exp = $value['ser_exp'];
                            $serologydetail->ser_specimen = $value['ser_specimen'];
                            $serologydetail->sm_id = isset($value['sm_id'])?$value['sm_id']:0;//
                            $serologydetail->ser_brand = $value['ser_brand'];
                            $serologydetail->ser_lot = $value['ser_lot'];
                            $serologydetail->ser_result = isset($value['ser_result'])?$value['ser_result']:2;//
                            $serologydetail->created_by = \Auth::user()->creatorId();
                            $serologydetail->created_at = date('Y-m-d H:i:s');
                            $serologydetail->save(); 
                    }
                }
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
            $data->lab_control_no = $lab_request->lab_control_no;
            $data->lab_req_id = $lab_request->id;
            $data->cit_id = $lab_request->cit_id;
            $data->patient = $lab_request->patient;
            $data->ser_or_num = $lab_request->get_or_results(2);
            $lab_results = $this->_SerologyModel->where('lab_control_no',$lab_request->lab_control_no)->first();
            if ($lab_results) {
                $data = $lab_results;
            }
        }
        $service = [];
        if (($request->input('service_id'))) {
            $service = $request->input('service_id');
        }
        if($request->input('id')>0){
            $data = $this->_SerologyModel->find($request->input('id'));
        }
        $ScreeningTest = $this->_SerologyModel;
        $serologyFields = $this->_SerologyModel->getFields();
		if(!empty($data->doc_json)){
        $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
		if($data->med_tech > 0){ 
            $esignisapproveds = $this->_SerologyModel->selectHRemployees($data->med_tech); 
        }else{
            $esignisapproveds = 0;
        }
		if($data->health_officer > 0){ 
            $officerisapproved = $this->_SerologyModel->selectHRemployees($data->health_officer); 
        }else{
            $officerisapproved = 0;
        }
        return view('Serology.create',compact('data','officerisapproved','esignisapproveds','service','last_user_data','getcitizens','getphysician','ScreeningTest','Laboratorytest','Laboratoryresult','serologyFields'));
    }
    
    public function generatelabreqidNumber($company_code) {
        $prefix = $company_code;
        $last_bookingq=DB::table('ho_serology')->orderBy('id','desc');
        
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
        $last_bookingq=DB::table('ho_serology')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->ser_lab_no;
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
                // 'ser_or_num'=>'required',
            ], [
                // 'ser_or_num.required' => 'O.R. No. is Required',
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
	  $data = $this->_SerologyModel->getCitizensname($id);
	  return $data;
	}
    public function submit(Request $request, $id)
    {
        $this->_SerologyModel->updateData($id,['ser_is_posted'=>1]);
        $arr = [];
        $arr['ESTATUS'] = 0;
        return json_encode($arr);
    }
	
	public function getDesignation($employee_id){
    	try{
			$designation = $this->_SerologyModel->getDesignation($employee_id);
			return response()->json(['status' => 200, 'data' => $designation]);
    	}catch(Exception $e){
    		return ($e->getMessage());
    	}
    }
	
	public function uploadDocument(Request $request){
        
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = SerologyModel::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/serology/';
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
                $this->_SerologyModel->updateData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/serology').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = SerologyModel::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/serology/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_SerologyModel->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }
}
