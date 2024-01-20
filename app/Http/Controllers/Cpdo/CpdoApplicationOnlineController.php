<?php

namespace App\Http\Controllers\Cpdo;
use App\Http\Controllers\Controller;
use App\Models\Cpdo\CpdoApplicationOnline;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use File;
use Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CpdoApplicationOnlineController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $arrgetBrgyCode = array(""=>"Please Select");
     public $arrOwners = array(""=>"Please Select");
     public $getServices = array(""=>"Please Select");
     public $apptype = array("PLease Select");
     public $hremployees = array(""=>"Please Select");
     public $requirement = array(""=>"Please Select");
       public function __construct(){
		$this->_cpdoappform= new CpdoApplicationOnline(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','caf_control_no'=>'','client_id'=>'','caf_date'=>date('Y-m-d'),'caf_name_firm'=>'','caf_client_representative_id'=>'','client_telephone'=>'','tfoc_id'=>'','cm_id'=>'','caf_amount'=>'','caf_excempted'=>'','caf_email'=>'','cna_id'=>'','caf_purpose_application'=>'','caf_type_project'=>'','caf_complete_address'=>'','cpt_id'=>'','cpt_others'=>'','caf_site_area'=>'','croh_id'=>'','caf_radius'=>'','caf_use_project_site'=>'','caf_product_manufactured'=>'','caf_averg_product_output'=>'','caf_power_source'=>'','caf_power_daily_consump'=>'','caf_employment_current'=>'','caf_employment_project'=>'','caf_others_nature_of_applicant'=>'','caf_remarks'=>'');
           
        $this->slugs = 'online-cpdoapplication'; 
        foreach ($this->_cpdoappform->getOwners() as $val) {
             $this->arrOwners[$val->id]=$val->rpo_first_name." ".$val->rpo_middle_name." ".$val->rpo_custom_last_name;
         }
         foreach ($this->_cpdoappform->getServices() as $val) {
             $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }
         foreach ($this->_cpdoappform->gethremployess() as $val){
             $this->hremployees[$val->id]=$val->fullname;
         } 
         foreach ($this->_cpdoappform->getRequirements() as $val){
             $this->requirement[$val->id]=$val->req_code_abbreviation." - ".$val->req_description;
         } 
    }
    public function index(Request $request)
    {       
            $barangay=array(""=>"Please select");
            $getmincipalityid = $this->_cpdoappform->getCpdomunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
            foreach ($this->_cpdoappform->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
            }
            $to_date="";
            $from_date="";
            $this->is_permitted($this->slugs, 'read');
                return view('cpdo.online.index',compact('barangay','to_date','from_date'));
    }
    public function Declineapplication(Request $request){
        $id=$request->input('appid'); $remark = $request->input('remark');
        $updatearray = array("is_approved"=>"2","caf_remarks"=>$remark);
        $this->_cpdoappform->updateData($id,$updatearray);
    }
    public function approve(Request $request)
    {  $id=$request->input('appid');
        $data=$this->_cpdoappform->approve($id);
        return response()->json([
            'data' =>$data
        ]);
    }

    public function syncapptoremote(Request $request){
        $id=$request->input('id');
        $data=$this->_cpdoappform->syncapptoremote($id);
        if(isset($data)){
             Session::forget('REMOTE_SYNC_APPFORMID');
        }
        return response()->json([
            'data' =>$data
        ]);

    }

    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_cpdoappform->updateData($id,$data);
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cpdoappform->getList($request);
        //echo "<pre>"; print_r($data); exit;
        
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $controlno = $row->caf_control_no;
            if(empty($row->caf_control_no) || $row->caf_control_no =='0'){
                $controlno = 'ONLINE-'.str_pad($row->id, 6, '0', STR_PAD_LEFT);
            }
            $arr[$i]['caf_control_no']=$controlno;
            $arr[$i]['ownername']=$row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
            $arr[$i]['projectname']=$row->caf_type_project;
            $barngayAddress = $this->_commonmodel->getBarangayname($row->caf_brgy_id); 
            $barngayName = "";
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['address']=$barngayName;  
            $arr[$i]['sdetail']=config('constants.arrCpdoStatus')[$row->cs_id];
            if($row->is_approved == '0'){
                $status = '<span class="btn btn-info" style="padding: 0.1rem 0.5rem !important;">Pending</span>';
            }
            if($row->is_approved == '2'){
                $status = '<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Declined</span>';
            }
            if($row->is_approved == '1'){
                $status = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Approved</span>';
            } 
            $arr[$i]['status']=$status; 
            $startCarbon = Carbon::parse($row->caf_date);
             $endCarbon = date('Y-m-d');
             $diff = $startCarbon->diff($endCarbon);
             if ($diff->days == 0) {
                 $duration = "";
             } 
             elseif($diff->days == 1) {
                 $duration = $diff->days . " Day";
             } else {
                 $duration = $diff->days . " Days";
             }
            $arr[$i]['date']=$row->caf_date; 
            $arr[$i]['method']='Online';              
            $arr[$i]['duration']=$duration; 
            $arr[$i]['action']='
                <div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-cpdoapplication/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Zoning Clearance: Online Application">
                        <i class="ti-eye text-white"></i>
                    </a></div>';
            $i++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data,JSON_INVALID_UTF8_IGNORE);
    }

    public function positionbyid(Request $request){
    	$id= $request->input('id');
    	$posid= $request->input('posid');
    	$data = $this->_cpdoappform->getpositionbyid($id);
    	echo $data->description;
    }

     public function getProfileDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_cpdoappform->getProfileDetails($id);
        echo json_encode($data);
    }

    public function getRequirements(Request $request){
           $tfocid= $request->input('tfocid');
           $requirements = $this->_cpdoappform->getSercviceRequirements($tfocid);
           $reqhtml = "";  $i=0;
           foreach ($requirements as $key => $value) {
               $reqhtml .= '<div class="removerequirementsdata row pt10">';
               $reqhtml .= '<div class="col-lg-5 col-md-5 col-sm-5">
                      <div class="form-group"><div class="form-icon-user">
                        '.$value->req_description.'<input type="hidden" name="reqid[]" value="'.$value->id.'">'.'</div></div></div>';
               $reqhtml .= '<div class="col-lg-1 col-md-1 col-sm-1">
                     <div class="form-group">
                        <div class="form-icon-user">
                    </div></div></div>';
               if($i <= 3){
                  $reqhtml .= '<div class="col-lg-3 col-md-3 col-sm-3">
                     <div class="form-group">
                        <div class="form-icon-user"><input class="form-control" required="required" name="reqfile[]" type="file" value="">
                    </div></div></div>';
               }else{
                   $reqhtml .= '<div class="col-lg-3 col-md-3 col-sm-3">
                     <div class="form-group">
                        <div class="form-icon-user"><input class="form-control" name="reqfile[]" type="file" value="">
                    </div></div></div>';
               } 
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2">
                     <div class="form-group">
                        <div class="form-icon-user"><button type="button" class="btn btn-primary btn_cancel_requirement"><i class="ti-trash"></i></button>
                    </div></div></div>';
               $reqhtml .= '</div>'; $i++;
           }
           echo $reqhtml; exit;
    }

	public function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
   }

   public function getServicetype(Request $request){
      $tfocid = $request->input('tfocid'); $html ="";
      if($tfocid =='23' || $tfocid =='14'){
          $optiondata = $this->_cpdoappform->getServiceTypearray('1');
      }else{
           $optiondata = $this->_cpdoappform->getServiceTypearray('2');
      }
      foreach ($optiondata as $key => $value) {
        $html .='<option value="'.$value->id.'">'.$value->cm_module_desc.'</option>';
      }
      echo $html;
   }

    public function store(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $arrRequirements = array();  $apptype = array(""=>"Select Service");
        $data->caf_total_amount ="";  
        $requirement =$this->requirement;
        $arrOwners = $this->arrOwners;
        $checkrecords = $this->_cpdoappform->GetcpdolatestApp();
        if(!empty($checkrecords)){
        	$getServices = array(""=>"Please Select");
        	foreach ($this->_cpdoappform->getServicesbyid($checkrecords->tfoc_id) as $val) {
             $getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
            }
        }else{
        	$getServices = $this->getServices;
        }
        //print_r($checkrecords); exit;
        
        $orderarray = array('id'=>'','date'=>date('Y-m-d'),'transaction_no'=>'');
        $checkidexist = $this->_cpdoappform->checkTransactionexist($request->input('id'));
        $orderpayment = (object)$orderarray;
         if(count($checkidexist)> 0){
    	 	$orderpayment->transaction_no = $checkidexist[0]->transaction_no;
    	 	$orderpayment->id = $checkidexist[0]->id;
    	 }
        $arrApptype = config('constants.arrCpdoStatus');
        foreach ($this->_cpdoappform->getServiceTypearraydefault() as $k => $valat) {
          $apptype[$valat->id] = $valat->cm_module_desc;
        }
        $arrtenure = config('constants.arrCpdoProjectTenure');
        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        //$apptype = config('constants.arrCpdoAppModule'); 
        $applicationid ="";
        $data->is_approve ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_cpdoappform->getDataForEdit($request->input('id'));
            if(empty($data->caf_control_no) || $data->caf_control_no =='0'){
                $appno = str_pad($request->input('id'), 6, '0', STR_PAD_LEFT);
                $data->caf_control_no='ONLINE-'.$appno;
            } 
            $arrRequirements = $this->_cpdoappform->getAppRequirementsData($data->frgn_caf_id);
        }
        //print_r($apptype); exit; 
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $cashdata = $this->_cpdoappform->getCasheringIds($this->data['tfoc_id']);
            //print_r($cashdata); exit;
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['agl_account_id'] = $cashdata->gl_account_id;
            $this->data['sl_id'] = $cashdata->sl_id;
            $materialbill = $request->input('caf_amount');
            if($request->input('caf_excempted') =='1')
            {
              $this->data['caf_total_amount'] = $request->input('totalamount');
            }else{
             if($materialbill > 0){ 
               $getclearance = $this->_cpdoappform->getcleranceid($request->input('cm_id'));
                if(!empty($getclearance)){
                  $getclerancelinedata = $this->_cpdoappform->getclerancelinedata($getclearance->id,$request->input('caf_amount'));
                  //echo "<pre>"; print_r($getclerancelinedata); exit;
                  if($getclerancelinedata ==""){
                  $getclerancelinedata = $this->_cpdoappform->hetoverbyAmount($getclearance->id,$request->input('caf_amount'));
                        $payment1 = $materialbill - $getclerancelinedata->czccl_below;
                        $payment2 = ($payment1 * 0.01)/10;
                        $caf_total_amount = $getclerancelinedata->czccl_amount + $payment2;
                        $this->data['caf_total_amount'] = $caf_total_amount;
                  }else{
                    $this->data['caf_total_amount'] = $getclerancelinedata->czccl_amount;
                  }
                }
              }else{
                $this->data['caf_total_amount'] = $request->input('totalamount');
              }
            }
            //echo "<pre>"; print_r($this->data); exit;
            if($request->input('id')>0){
                $this->_cpdoappform->updateData($request->input('id'),$this->data);
                $success_msg = 'Cpdo Application updated successfully.';
                $currentappid = $request->input('id');
                $controlnumber = $_POST['caf_control_no'];
               }else{
               	$this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['cs_id'] = '1';
                $this->data['csd_id'] = '1';
                $lastinsertid = $this->_cpdoappform->addData($this->data);
                $success_msg = 'Cpdo Application added successfully.';

                $controlno = str_pad($lastinsertid, 6, '0', STR_PAD_LEFT);
                $updatedata = array('caf_control_no'=>$controlno);
                $this->_cpdoappform->updateData($lastinsertid,$updatedata);
                $controlnumber = $controlno;
                $currentappid = $lastinsertid;
            }
            //$file_ary = $this->reArrayFiles($_FILES['reqfile']);
            // echo "<pre>"; print_r($_POST);
            //     echo "<pre>"; print_r($_FILES); exit;
            if(!empty($_POST['reqid']) > 0){
	            foreach ($_POST['reqid'] as $key => $value) {
	                $appreqarr = array();
	                $appreqarr['caf_id'] = $currentappid;
	                $appreqarr['tfoc_id'] = $_POST['tfoc_id'];
	                $appreqarr['cs_id'] = $_POST['cm_id'];
	                $appreqarr['req_id'] = $_POST['reqid'][$key];
	                $appreqarr['created_by']=\Auth::user()->id;
	                $appreqarr['created_at'] = date('Y-m-d H:i:s');
	                $checkexistreq = $this->_cpdoappform->checkappRequiremenexist($request->input('id'),$_POST['reqid'][$key]);
	                if(count($checkexistreq) > 0){
	                	 $lastinsertid = $checkexistreq[0]->id; 
	                }else{ $lastinsertid = $this->_cpdoappform->appRequirementaddData($appreqarr); }

	                
	                if(isset($request->file('reqfile')[$key])){  
	               	 	if($image =$request->file('reqfile')[$key]) {
	                        $reqid= $_POST['reqid'][$key];
	                    	 $destinationPath =  public_path().'/uploads/cpdo/requirement';
	                        if(!File::exists($destinationPath)){ 
	                            File::makeDirectory($destinationPath, 0755, true, true);
	                        }
		                     $filename =  'requirement'.date('Ymdhis');  
		                     $filename = str_replace(" ", "", $filename);   
		                     $requirementpdf = $filename. "." . $image->extension();
		                     $extension =$image->extension();
		                     $image->move($destinationPath, $requirementpdf);
		                     
		                    // print_r($image); exit;
		                     $filearray = array();
		                     $filearray['car_id'] = $lastinsertid;
		                     $filearray['cf_name'] = $requirementpdf;
		                     $filearray['cf_type'] = $extension;
		                     //$filearray['cf_size'] = $_FILES['reqfile'.$key]['size'];
		                     $filearray['cf_path'] = 'cpdo/requirement';
		                     $filearray['created_by']=\Auth::user()->id;
		                     $filearray['created_at'] = date('Y-m-d H:i:s');
		                     //echo $_POST['cfid'][$key]; print_r($filearray); exit;
		                     $checkimageexits = $this->_cpdoappform->checkRequirementfileexist($reqid);
		                     if(!empty($_POST['cfid'][$key])){ echo "here"; 
		                        $this->_cpdoappform->UpdateappFilesData($_POST['cfid'][$key],$filearray);
		                     }else{ $this->_cpdoappform->AddappFilesData($filearray);  }  
		                     // echo $profileImage;
		                }
		            } 
	             }
	        }
            return redirect()->route('cpdoapplication.index')->with('success', __($success_msg));
    	}
        return view('cpdo.online.create',compact('data','arrRequirements','arrOwners','getServices','arrApptype','arrtenure','arrCpdoOverland','apptype','orderpayment','requirement'));
	}

	 public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'tfoc_id'=>'required',
                'cna_id' =>'required'
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
   
}
