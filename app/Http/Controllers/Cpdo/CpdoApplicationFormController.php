<?php

namespace App\Http\Controllers\Cpdo;
use App\Http\Controllers\Controller;
use App\Models\Cpdo\CpdoApplicationForm;
use App\Models\Barangay;
use App\Models\HrEmployee;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;
use File;
use DB;
use Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use \Mpdf\Mpdf as PDF;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Models\BploBusiness;

class CpdoApplicationFormController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrgetBrgyCode = array(""=>"Please Select");
     public $arrOwners = array(""=>"Please Select");
     public $getServices = array(""=>"Please Select");
     public $apptype = array("PLease Select");
     public $hremployees = array(""=>"Please Select");
     public $requirement = array(""=>"Please Select");
     private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
     private $carbon;
     public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness();
		$this->_cpdoappform= new CpdoApplicationForm(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array('id'=>'','caf_control_no'=>'','client_id'=>'','caf_date'=>date('Y-m-d'),'caf_name_firm'=>'','caf_client_representative_id'=>'','client_telephone'=>'','tfoc_id'=>'','cm_id'=>'','caf_amount'=>'','caf_excempted'=>'','caf_email'=>'','cna_id'=>'','caf_purpose_application'=>'','caf_type_project'=>'','caf_brgy_id'=>'','cpt_id'=>'','cpt_others'=>'','caf_site_area'=>'','croh_id'=>'','caf_radius'=>'','caf_use_project_site'=>'','caf_product_manufactured'=>'','caf_averg_product_output'=>'','caf_power_source'=>'','caf_power_daily_consump'=>'','caf_employment_current'=>'','caf_employment_project'=>'','caf_others_nature_of_applicant'=>'','caf_remarks'=>'');

        $this->inspectiondata = array('id'=>'','cir_date'=>date('Y-m-d'),'caf_id'=>'','cir_zoning_class'=>'','cir_use_res'=>'','cit_id'=>'','cir_north'=>'','cir_south'=>'','cir_east'=>'','cir_west'=>'','cir_long_we_degree'=>'','cir_long_we_minutes'=>'','cir_long_we_seconds'=>'','cir_lat_ns_degree'=>'','cir_lat_ns_minutes'=>'','cir_lat_ns_seconds'=>'','cir_long'=>'','cir_lat'=>'','cir_water_supply'=>'','cir_decs'=>'','cir_power_supply'=>'','cir_drainage'=>'','cir_other'=>'','cir_remark'=>'','cir_approved_by'=>'','cir_noted_by'=>'','cir_penalty'=>'');
        $this->certificate = array('id'=>'','cc_applicant_no'=>'','cc_date'=>'','caf_id'=>'','cc_falc_no'=>'','cc_rol'=>'','cc_boc'=>'','cc_name_project'=>'','cc_area'=>'','cc_location'=>'','cc_project_class'=>'','cc_site_classification'=>'','cc_dominant'=>'','cc_evaluation'=>'','cc_decision'=>'','preparedby'=>'','cir_created_position'=>'','cc_recom_approval'=>'','cc_recom_approval_position'=>'','cc_noted'=>'','cc_noted_position'=>'','cc_approved'=>'','cc_approved_position'=>'');     
        $this->slugs = 'cpdoapplication'; 
        foreach ($this->_cpdoappform->getOwners() as $val) {
             $this->arrOwners[$val->id]=$val->full_name;
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
    {       $barangay=array(""=>"Please select");
            $getmincipalityid = $this->_cpdoappform->getCpdomunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
            foreach ($this->_cpdoappform->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
            }
            $statusarray = config('constants.arrCpdoStatusDetails');
            $to_date=Carbon::now()->format('Y-m-d');
            $from_date=Carbon::now()->format('Y-m-d');
            $this->is_permitted($this->slugs, 'read');
                return view('cpdo.application.index',compact('barangay','to_date','from_date','statusarray'));
           
    }
    public function onlineindex(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
                return view('cpdo.application.online');
    }

     public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_cpdoappform->updateData($id,$data);
    }

     public function getEngTaxpayersAutoSearchList(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_cpdoappform->getClientsNameAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->full_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
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
           $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['caf_control_no']=$row->caf_control_no;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['projectname']="<div class='showLess'>".$row->caf_type_project."</div>";
            $barngayName = "";
            $barngayAddress = $this->_Barangay->getBarangayname($row->caf_brgy_id); 
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['address']= $barngayName;
            $arr[$i]['sdetail']=config('constants.arrCpdoStatus')[$row->cs_id];
            $arr[$i]['status']=config('constants.arrCpdoStatusDetails')[$row->csd_id]; 
            $arr[$i]['is_online']=($row->is_online == 0)? 'Walkin':'Online'; 
            $arr[$i]['date']=date('M d, Y',strtotime($row->caf_date));
            $startDate = Carbon::parse($row->caf_date);
            $endDate = Carbon::parse(date('Y-m-d'));
            $diff = $startDate->diff($endDate);
            
            if ($row->csd_id == 9) {
                $duration = "";
            } else {
                $duration = $diff->days . " Day";
            } 
            $arr[$i]['duration']=$duration;
            $arr[$i]['topno']=""; 
            $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_cpdoappform->checkTransactionexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_cpdoappform->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                 $orno = $ordata[0]->or_no; $ordate =date('M d, Y',strtotime($ordata[0]->created_at)); ; 
                 }
               }
            }
            $totalamount = $row->caf_total_amount + $row->penaltyamount;
            $arr[$i]['amount']=number_format($totalamount, 2, '.', ',');              
            $arr[$i]['orno']=$orno; 
            $arr[$i]['ordate']=$ordate; 
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoapplication/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Zoning Clearance">
                        <i class="ti-pencil text-white"></i>
                    </a></div>';
                    if($row->csd_id >='1'){
                    $arr[$i]['action'] .='<div class="action-btn bg-warning ms-2">
		                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoapplication/inspectionreport?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Inspection Order"  data-title="Zoning Clearance Inspection Order">
		                    <i class="ti-eye text-white"></i>
		                </a></div>';
                    }
		            if(!empty($row->cirid) && $row->csd_id >='4'){
		            	$arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
		                <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoapplication/certification?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Certificate"  data-title="Zoning Clearance Certificate">
		                    <i class="fa fa-certificate text-white"></i>
		                </a></div>';
		            } 
              
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

    public function getListonline(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cpdoappform->getListonline($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
           $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['caf_control_no']=$row->caf_control_no;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['projectname']=$row->caf_type_project;
            $barngayAddress = $this->_Barangay->findDetails($row->caf_brgy_id);
            $arr[$i]['address']=$barngayAddress;  
            $arr[$i]['sdetail']=config('constants.arrCpdoStatus')[$row->cs_id];
            $arr[$i]['status']=config('constants.arrCpdoStatusDetails')[$row->csd_id]; 
            $arr[$i]['topno']=""; 
            $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_cpdoappform->checkTransactionexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_cpdoappform->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                 $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }
            $arr[$i]['amount']=$row->caf_total_amount;              
            $arr[$i]['orno']=$orno; 
            $arr[$i]['ordate']=$ordate; 
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoapplication/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Planning &  Devt Application">
                        <i class="ti-pencil text-white"></i>
                    </a></div>';
                    if($row->csd_id >='2'){
                    $arr[$i]['action'] .='<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoapplication/inspectionreport?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Inspection Order"  data-title="Manage Planning &  Devt Inspection Order">
                            <i class="ti-eye text-white"></i>
                        </a></div>';
                    }
                    if(!empty($row->cirid) && $row->csd_id >='4'){
                        $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdoapplication/certification?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Certificate"  data-title="Manage Planning &  Devt Certificate">
                            <i class="fa fa-certificate text-white"></i>
                        </a></div>';
                    } 
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
    public function saveorderofpayment(Request $request){
    	 $id= $request->input('appid');
    	 $tfocid= $request->input('tfocid');
    	 $amount= $request->input('amount');
    	 $data =array();
    	 $data['top_transaction_type_id'] = 19;
         $data['tfoc_is_applicable'] = '5';
    	 $data['transaction_ref_no'] = $id;
    	 $data['tfoc_id'] = $tfocid;
         $amount = str_replace(",", "", $amount);
    	 $data['amount'] = $amount;
    	 $checkidexist = $this->_cpdoappform->checkTransactionexist($id);
    	 if(count($checkidexist)> 0){
            $filename = $this->saveorderofpaymentfile($checkidexist[0]->id);
             $appuptdata = array('amount'=>$amount,'attachment'=>$filename);
                $this->_cpdoappform->TransactionupdateData($checkidexist[0]->id,$appuptdata);
    	 	$array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$checkidexist[0]->transaction_no,'transid'=>$checkidexist[0]->id];
            $transactionno = $checkidexist[0]->id;
            $trans_id=$checkidexist[0]->id;
    	 }else{
    	 	$data['created_at'] = date('Y-m-d H:i:s');
    	 	$lastinsert =$this->_cpdoappform->TransactionaddData($data);
            $filename = $this->saveorderofpaymentfile($lastinsert);
    	 	    $transactionno = str_pad($lastinsert, 6, '0', STR_PAD_LEFT);
                $updatedata = array('transaction_no'=>$transactionno,'attachment'=>$filename);
                $this->_cpdoappform->TransactionupdateData($lastinsert,$updatedata);
                $appuptdata = array('top_transaction_type_id'=>19,'topno'=>$transactionno);
                $this->_cpdoappform->updateData($id,$appuptdata);
                $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$transactionno,'transid'=>$lastinsert];
                $trans_id=$lastinsert;
    	 }
         $orderdata = $this->_cpdoappform->GetOrderdata($trans_id);
         if($orderdata->is_printed == 0){
            $smsTemplate=SmsTemplate::where('id',11)->where('is_active',1)->first();
            if(!empty($smsTemplate))
            {
                $receipient=$orderdata->p_mobile_no;
                $msg=$smsTemplate->template;
                $msg = str_replace('<NAME>', $orderdata->full_name,$msg);
                $msg = str_replace('<TOP_NO>', $orderdata->transaction_no,$msg);
                $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                $this->send($msg, $receipient);
                $u_trans_data=[
                    'is_printed' => 1
                  ];
                $this->_cpdoappform->TransactionupdateData($orderdata->id,$u_trans_data);
            }
            $updateremotedata = array();
            $updateremotedata['topno'] = $transactionno;
            $updateremotedata['cashieramount'] = $amount;
            $this->_cpdoappform->updateremotedata($id,$updateremotedata); 
         }
    	 //$this->storeCpdobillSummary($id,$amount,$transactionno);
         echo json_encode($array);
    }

    public function storeCpdobillSummary(Request $request){
       if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
          $id = $request->input('appid'); 
          $transactionno =  $request->input('transactionno');
          $clientdata = $this->_cpdoappform->getclientidbyid($id);
          $arrTran = $this->_cpdoappform->getBillDetails($transactionno,$id);
          if(isset($arrTran)){ 
             $billsaummary = array();
             $billsaummary['permit_id'] = $id; 
             $billsaummary['client_id'] = $clientdata->client_id;
             $billsaummary['cpdo_type'] = 1;
             $billsaummary['bill_year'] = date('Y');
             $billsaummary['bill_month'] = date('m');
             $billsaummary['total_amount'] = $arrTran->amount;
             $billsaummary['pm_id'] = 1;
             $billsaummary['attachement'] = $arrTran->attachment;
             $billsaummary['transaction_no'] = $arrTran->transaction_no;
             
          //This is for Main Server
            $arrBill = DB::table('planning_bill_summary')->select('id')->where('permit_id',$id)->where('cpdo_type','1')->where('transaction_no',$arrTran->transaction_no)->first();
            if(isset($arrBill)){
                DB::table('planning_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
            }else{
                 $billsaummary['created_by'] = \Auth::user()->id;
                 $billsaummary['created_at'] = date('Y-m-d H:i:s');
                $this->_cpdoappform->insertbillsummary($billsaummary);
            }

            // This is for Remote Server
                $destinationPath =  public_path().'/uploads/billing/cpdo/'.$arrTran->attachment;
                $fileContents = file_get_contents($destinationPath);
                $remotePath = 'public/uploads/billing/cpdo/'.$arrTran->attachment;
                Storage::disk('remote')->put($remotePath, $fileContents);
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('planning_bill_summary')->select('id')->where('permit_id',$id)->where('cpdo_type','1')->where('transaction_no',$arrTran->transaction_no)->first();

                try {
                    if(isset($arrBill)){
                        $remortServer->table('planning_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
                    }else{
                        $billsaummary['created_by'] =  \Auth::user()->id;
                        $billsaummary['created_at'] =  date('Y-m-d H:i:s');
                       $this->_cpdoappform->insertbillsummaryremote($billsaummary);
                    }
                    DB::table('planning_bill_summary')->where('permit_id',$id)->where('cpdo_type','1')->where('transaction_no',$arrTran->transaction_no)->update(array('is_synced'=>1));
                    unlink($destinationPath);
                }catch (\Throwable $error) {
                    return $error;
                }  
                echo "Done";
            } 
        }
           
    }
    public function ApproveInspection(Request $request){
    	$id= $request->input('id');
    	$appuptdata = array('csd_id'=>'4','cs_id'=>'2');
        $this->_cpdoappform->updateData($id,$appuptdata);

        $appuptdata = array('cir_isapprove'=>'1','cir_approved_date'=>date('Y-m-d'));
        $this->_cpdoappform->updateinspectionData($id,$appuptdata);
        $array = ["status"=>"success","message" =>"Approved Successfully."];
        echo json_encode($array);
    }

     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $eid = $request->input('eid');
        $arrRequirements = $this->_cpdoappform->getRequirementsbyid($rid);
        if(count($arrRequirements) > 0){
            if($arrRequirements[0]->cf_name){
                $path =  public_path().'/uploads/'.$arrRequirements[0]->cf_path."/".$arrRequirements[0]->cf_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_cpdoappform->deleteRequirementsbyid($rid);
                if(!empty($eid)){
                   $this->_cpdoappform->deleteimagerowbyid($eid); 
                }
                echo "deleted";
            }
        }
    }
   public function insepectiondeleteAttachment(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arr = $this->_cpdoappform->getEditDetails($id);
        if(isset($arr)){
            $arrJson = json_decode($arr->cir_upload_documents_json,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/cpdo/inspection/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);
                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['cir_upload_documents_json'] = json_encode($arrJson);
                    $this->_cpdoappform->updateinspectionData($id,$data);
                    echo "deleted";
                }
            }
        }
    }

    public function ApproveCertificate(Request $request){
        $id= $request->input('id'); $cafid = $request->input('cafid');
        $button= $request->input('button');
        if($button =='noted'){
          $appuptdata = array('cc_notes_status'=>'1','cc_noted_date'=>date('Y-m-d')); 
          $appuptdataapp = array('csd_id'=>'6'); 
        }
        if($button =='recommend'){
          $appuptdata = array('cc_recom_status'=>'1','cc_recom_approval_date'=>date('Y-m-d')); 
          $appuptdataapp = array('csd_id'=>'7'); 
        }
        if($button =='approve'){
          $appuptdata = array('cc_approval_status'=>'1','cc_approved_date'=>date('Y-m-d'));
          $appuptdataapp = array('csd_id'=>'8');  
        }
        $this->_cpdoappform->CertificateupdateData($id,$appuptdata);
        $this->_cpdoappform->updateData($cafid,$appuptdataapp);
        $array = ["status"=>"success","message" =>"Approved Successfully."];
        echo json_encode($array);
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
        $data->fulladdress = $this->_commonmodel->getTaxPayerAddress($data->clientid);
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
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2">
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
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2" style="text-align:center;padding-left: 56px;">
                     <div class="form-group">
                        <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_requirement" style="padding: 4px;"><i class="ti-trash"></i></button>
                    </div></div></div>';
               $reqhtml .= '</div>'; $i++;
           }
           echo $reqhtml; exit;
    }

    public function printapplication(Request $request){
    	    $id = $request->input('id');
     		$appdata = $data = CpdoApplicationForm::find($request->input('id'));
     		//echo "<pre>"; print_r($appdata); exit;
     		$mpdf = new \Mpdf\Mpdf();
     		$mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;

            $html = file_get_contents(resource_path('views/layouts/templates/cpdozoningapplication.html'));
            $arrCpdoOverland = config('constants.arrCpdoOverland'); 
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $unchecked = url('/assets/images/unchecked-checkbox.jpg');
            $checked = url('/assets/images/checked-checkbox.jpeg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{controlno}}',$appdata->caf_control_no, $html);
            $html = str_replace('{{dateofapp}}',date('M,d,Y',strtotime($appdata->caf_date)), $html);
            $clientname = $appdata->full_name;
			      $arrOwners = $this->arrOwners;
            $html = str_replace('{{nameandfirm}}',$clientname." ".$appdata->caf_name_firm, $html);
			      $html = str_replace('{{client_name}}',$arrOwners[$appdata->client_id], $html);
            $represedata = $this->_cpdoappform->Getclientbyid($appdata->caf_client_representative_id);
            $representative ="";
            if(!empty( $represedata)){
               $representative = $represedata->full_name; 
            }

            $htmldynaapp='';
            $natureofapp = config('constants.arrCpdoNatureApp');

            foreach ($natureofapp as $key => $value) {
                 	if( $key == $appdata->cna_id){
                 		$htmldynaapp .='<td style="border:0px solid black; padding-right:30px;"> <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</p></td>';
                 	}else{
                 		$htmldynaapp .='<td style="border:0px solid black; padding-right:30px;"><p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</p></td>';
                 	}
                 } 
           $arrterrin = config('constants.arrCpdoProjectTenure'); $appoftenure ="";
            foreach ($arrterrin as $key => $value) {
                 	if( $key == $appdata->cpt_id){
                 		$appoftenure .='<div style="width:33%; float:left;"> <img src="'.$checked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</div>';
                 	}else{
                 		$appoftenure .='<div style="width:33%; float:left;"><img src="'.$unchecked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</div>';
                 	}
                 }
             $appoftenure .='<div style="width:33%; float:left;">Specify No. of Years&nbsp;&nbsp;'.$appdata->cpt_others.'</div>';     
            $rightipverhtml =""; 
            $overland = config('constants.arrCpdoOverland'); 
            foreach ($overland as $key => $value) {
                 	if( $key == $appdata->croh_id){
                 		$rightipverhtml .='<div style="width:20%; float:left;"><img src="'.$checked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</div>';
                 	}else{
                 		$rightipverhtml .='<div style="width:20%; float:left;"><img src="'.$unchecked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</div>';
                 	}
                 }    
                    
            $html = str_replace('{{natureofapp}}',$htmldynaapp, $html); 
            $html = str_replace('{{protenure}}',$appoftenure, $html);
            $html = str_replace('{{rightover}}',$rightipverhtml, $html);      
            $html = str_replace('{{representative}}',$representative, $html);
            $html = str_replace('{{nameofproject}}',$appdata->caf_type_project, $html);
            $html = str_replace('{{telephoneno}}',$appdata->client_telephone, $html);
            $html = str_replace('{{apppurpose}}',$appdata->caf_purpose_application, $html);
            $html = str_replace('{{typeofproject}}',$appdata->caf_type_project, $html);
            $barngayAddress = $this->_Barangay->findDetails($appdata->caf_brgy_id);
            $html = str_replace('{{location}}',$barngayAddress, $html);
            $html = str_replace('{{sitearea}}',$appdata->caf_site_area, $html);
            $html = str_replace('{{landclassification}}',$appdata->caf_radius, $html);
            $html = str_replace('{{useofproject}}',$appdata->caf_use_project_site, $html);
            $html = str_replace('{{promanufacturer}}',$appdata->caf_product_manufactured, $html);
            $html = str_replace('{{averageoutput}}',$appdata->caf_averg_product_output, $html);
            $html = str_replace('{{source}}',$appdata->caf_power_source, $html);
            $html = str_replace('{{dailyconsumption}}',$appdata->caf_power_daily_consump, $html);
            $html = str_replace('{{current}}',$appdata->caf_employment_current, $html);
            $html = str_replace('{{projected}}',$appdata->caf_employment_project, $html);
            $html = str_replace('{{appliacant}}',$clientname, $html);
            $html = str_replace('{{representative}}',$representative, $html);

            // $html = str_replace('{{Amount}}',$certificatedata->caf_amount, $html);
            // $html = str_replace('{{ornumber}}',$certificatedata->transaction_no, $html);
            // $html = str_replace('{{dateissued}}',$certificatedata->cc_date, $html);
            // $html = str_replace('{{total}}',$orderdata->caf_amount, $html);
            $filename="";
            //$html = $html;
            //echo $html; exit;
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $orderfilename = $id.$filename."zoningapplication.pdf";
            // $folder =  public_path().'/uploads/cpdo/certificate/';
            // if(!File::exists($folder)) { 
            //     File::makeDirectory($folder, 0755, true, true);
            // }
            // $filename = public_path() . "/uploads/cpdo/certificate/" . $orderfilename;
            $mpdf->Output($orderfilename, "I");
            @chmod($filename, 0777);
            echo url('/uploads/cpdo/certificate/' . $orderfilename);
    }

    public function printcertificate(Request $request){
    	    $certid = $request->input('id'); $cafid =$request->input('cafid'); 
            $appuptdata = array('csd_id'=>'9');
            $this->_cpdoappform->updateData($cafid,$appuptdata);
            $issuedarray = array('issued_certificate'=>'1');
            $this->_cpdoappform->CertificateupdatebyCafid($cafid,$issuedarray);
     		$certificatedata = $this->_cpdoappform->getCertificateData($certid);
     		//print_r($certificatedata ); exit;
     		$mpdf = new \Mpdf\Mpdf();
     		$mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;

            $html = file_get_contents(resource_path('views/layouts/templates/cpdocertificate.html'));
            $arrCpdoOverland = config('constants.arrCpdoOverland'); 
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{falcno}}',$certificatedata->cc_falc_no, $html);
            $html = str_replace('{{appno}}',$certificatedata->caf_control_no, $html);
            $html = str_replace('{{dateissued}}',date('M,d,Y',strtotime($certificatedata->cc_date)), $html);
            $clientname = $certificatedata->full_name;
            $html = str_replace('{{nameofapplicant}}',$clientname, $html);
            $html = str_replace('{{nameofproject}}',$certificatedata->caf_type_project, $html);
            $html = str_replace('{{telephoneno}}',$certificatedata->client_telephone, $html);
            $html = str_replace('{{location}}',$certificatedata->cc_area." ".$certificatedata->cc_location, $html);
            $html = str_replace('{{overland}}',$arrCpdoOverland[$certificatedata->cc_rol], $html);
            $html = str_replace('{{classification}}',$certificatedata->cc_project_class, $html);
            $html = str_replace('{{clearance}}',$certificatedata->cc_boc, $html);
            $html = str_replace('{{siteclass}}',$certificatedata->cc_site_classification, $html);
            $html = str_replace('{{dominnat}}',$certificatedata->cc_dominant, $html);
            $html = str_replace('{{evalution}}',$certificatedata->cc_evaluation, $html);
            $html = str_replace('{{decision}}',$certificatedata->cc_decision, $html); 
            
            $predata = $this->_cpdoappform->getpositionbyid($certificatedata->preparedby);
            $html = str_replace('{{preparename}}',$predata->fullname, $html);
            $html = str_replace('{{prePosition}}',$predata->description, $html);

            $recomdata = $this->_cpdoappform->getpositionbyid($certificatedata->cc_recom_approval);
            $html = str_replace('{{recomname}}',$recomdata->fullname, $html);
            $html = str_replace('{{recomPosition}}',$recomdata->description, $html);
            
            $noteddata = $this->_cpdoappform->getpositionbyid($certificatedata->cc_noted);
            $html = str_replace('{{notedname}}',$noteddata->fullname, $html);
            $html = str_replace('{{notedPosition}}',$noteddata->description, $html);

            $approvedata = $this->_cpdoappform->getpositionbyid($certificatedata->cc_approved);
            $html = str_replace('{{approvename}}',$approvedata->fullname, $html);
            $html = str_replace('{{appPosition}}',$approvedata->description, $html);
            $certificatedata->caf_amount ="";
            $certificatedata->or_no ="";
            $certificatedata->orissueddate ="";
               if($certificatedata->top_transaction_type_id > 0){
                   $gettopdata = $this->_cpdoappform->checkTransactionexist($certificatedata->cafid,$certificatedata->top_transaction_type_id); 
                   if(count($gettopdata) > 0){
                     $ordata = $this->_cpdoappform->getORandORdate($gettopdata[0]->id);
                     if(count($ordata) > 0){
                     $certificatedata->or_no = $ordata[0]->or_no; $certificatedata->orissueddate = $ordata[0]->created_at;
                     $certificatedata->caf_amount =  $ordata[0]->total_amount;
                     }
                   }
                }

            $html = str_replace('{{Amount}}',$certificatedata->caf_amount, $html);
            $html = str_replace('{{ornumber}}',$certificatedata->or_no, $html);
            $html = str_replace('{{ordateissued}}',$certificatedata->orissueddate, $html);
            // $html = str_replace('{{total}}',$orderdata->caf_amount, $html);
            $filename="";
            //$html = $html;
            //echo $html; exit;
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $orderfilename = $certid.$filename."certificate.pdf";
            // $folder =  public_path().'/uploads/cpdo/certificate/';
            // if(!File::exists($folder)) { 
            //     File::makeDirectory($folder, 0755, true, true);
            // }
            // $filename = public_path() . "/uploads/cpdo/certificate/" . $orderfilename;
            // $mpdf->Output($orderfilename, "I");
            // @chmod($filename, 0777);
            // echo url('/uploads/cpdo/certificate/' . $orderfilename);
        $Prepared_By= $this->_commonmodel->isSignApply('planning_development_zoning_clearance_prepared_by');
        $isSignPrepared = isset($Prepared_By)?$Prepared_By->status:0;

        $Recommended_By= $this->_commonmodel->isSignApply('planning_development_zoning_clearance_recommended_by');
        $isSignRecommended = isset($Recommended_By)?$Recommended_By->status:0;

        $Noted_By= $this->_commonmodel->isSignApply('planning_development_zoning_clearance_noted_by');
        $isSignNoted = isset($Noted_By)?$Noted_By->status:0;

        $Approved_By= $this->_commonmodel->isSignApply('planning_development_zoning_clearance_approval_by');
        $isSignApproved = isset($Approved_By)?$Approved_By->status:0;


        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            $mpdf->Output($folder.$orderfilename,'F');
            @chmod($folder.$orderfilename, 0777);
        }
        
        $arrData['filename'] = $orderfilename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $preparedbyId = HrEmployee::where('id', $certificatedata->preparedby)->first();
        $cc_recom_approvalId = HrEmployee::where('id', $certificatedata->cc_recom_approval)->first();
        $cc_notedId = HrEmployee::where('id', $certificatedata->cc_noted)->first();
        $cc_approvedId = HrEmployee::where('id', $certificatedata->cc_approved)->first();
        
        $PreparedSignature = $this->_commonmodel->getuserSignature($preparedbyId->user_id);
        $PreparedPath =  public_path().'/uploads/e-signature/'.$PreparedSignature;

        if($isSignPrepared==1 && $signType==2){
            if(!empty($PreparedSignature) && File::exists($PreparedPath)){
                $arrData['isSavePdf'] = 1;
                $arrData['signerXyPage'] = $Prepared_By->pos_x.','.$Prepared_By->pos_y.','.$Prepared_By->pos_x_end.','.$Prepared_By->pos_y_end.','.$Prepared_By->d_page_no;
                $arrData['signaturePath'] = $PreparedSignature;
                $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        $RecommendedSignature = $this->_commonmodel->getuserSignature($cc_recom_approvalId->user_id);
        $RecommendedPath =  public_path().'/uploads/e-signature/'.$RecommendedSignature;

        if($isSignRecommended==1 && $signType==2){
            if(!empty($RecommendedSignature) && File::exists($RecommendedPath)){
                $arrData['isSavePdf'] = 2;
                $arrData['signerXyPage'] = $Recommended_By->pos_x.','.$Recommended_By->pos_y.','.$Recommended_By->pos_x_end.','.$Recommended_By->pos_y_end.','.$Recommended_By->d_page_no;
                $arrData['signaturePath'] = $RecommendedSignature;
                $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        $NotedSignature = $this->_commonmodel->getuserSignature($cc_notedId->user_id);
        $NotedPath =  public_path().'/uploads/e-signature/'.$NotedSignature;

        if($isSignNoted==1 && $signType==2){
            if(!empty($NotedSignature) && File::exists($NotedPath)){
                $arrData['isSavePdf'] = 3;
                $arrData['signerXyPage'] = $Noted_By->pos_x.','.$Noted_By->pos_y.','.$Noted_By->pos_x_end.','.$Noted_By->pos_y_end.','.$Noted_By->d_page_no;
                $arrData['signaturePath'] = $NotedSignature;
                $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        $ApprovedSignature = $this->_commonmodel->getuserSignature($cc_approvedId->user_id);
        $ApprovedPath =  public_path().'/uploads/e-signature/'.$ApprovedSignature;

        if($isSignApproved==1 && $signType==2){
            if(!empty($ApprovedSignature) && File::exists($ApprovedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1 || $arrData['isSavePdf']==2 || $arrData['isSavePdf']==3)?0:1;
                $arrData['signerXyPage'] = $Approved_By->pos_x.','.$Approved_By->pos_y.','.$Approved_By->pos_x_end.','.$Approved_By->pos_y_end.','.$Approved_By->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $ApprovedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignPrepared==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($PreparedSignature) && File::exists($PreparedPath)){
                $mpdf->Image($PreparedPath,$Prepared_By->esign_pos_x, $Prepared_By->esign_pos_y, $Prepared_By->esign_resolution);
            }
        }
        if($isSignRecommended==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($RecommendedSignature) && File::exists($RecommendedPath)){
                $mpdf->Image($RecommendedPath,$Recommended_By->esign_pos_x, $Recommended_By->esign_pos_y, $Recommended_By->esign_resolution);
            }
        }
        if($isSignNoted==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($NotedSignature) && File::exists($NotedPath)){
                $mpdf->Image($NotedPath,$Noted_By->esign_pos_x, $Noted_By->esign_pos_y, $Noted_By->esign_resolution);
            }
        }
        if($isSignApproved==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($ApprovedSignature) && File::exists($ApprovedPath)){
                $mpdf->Image($ApprovedPath,$Approved_By->esign_pos_x, $Approved_By->esign_pos_y, $Approved_By->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$orderfilename)) { 
                File::delete($folder.$orderfilename);
            }
        }
        $mpdf->Output($orderfilename,"I");
    }

    public function saveorderofpaymentfile($transid){
            $orderdata = $this->_cpdoappform->GetOrderdata($transid);

            $penaltyarray = array();
            foreach ($this->_cpdoappform->GetPenaltiesmaster() as $keyp => $valuep) {
              $penaltyarray[$valuep->id] = $valuep->name;
            }
            //print_r($orderdata ); exit;
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/cpdoorderofpayment.html'));
            $html = str_replace('{{transactionno}}',$orderdata->transaction_no, $html);
            $html = str_replace('{{date}}',date('Y-m-d',strtotime($orderdata->created_at)), $html);
            $clientname = $orderdata->full_name;
            $html = str_replace('{{applicantname}}',$clientname, $html);
            $html = str_replace('{{telephoenno}}',$orderdata->client_telephone, $html);
            $html = str_replace('{{zoningamt}}',$orderdata->caf_total_amount, $html);
            $html = str_replace('{{penalty}}',$orderdata->penaltyamount, $html);
            $penaltyrate = $this->_cpdoappform->getinspectionorderforedit($orderdata->transaction_ref_no);
            $html = str_replace('{{precentage}}',$penaltyarray[$penaltyrate->cir_penalty], $html);
            
            $totalamit = $orderdata->caf_total_amount + $orderdata->penaltyamount;
            $html = str_replace('{{total}}',$totalamit, $html);
            $filename="";
            //$html = $html;
            //echo $html; exit;
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $orderfilename = $transid.$filename."cpdoorderofpayment.pdf";
            $folder =  public_path().'/uploads/billing/cpdo/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/billing/cpdo/" . $orderfilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            return $orderfilename;
    }
    
    public function printorderofpayment(Request $request){
     		$appid = $request->input('appid');  $transid = $request->input('id');
     		$orderdata = $this->_cpdoappform->GetOrderdata($transid);
            $penaltyarray = array();
            foreach ($this->_cpdoappform->GetPenaltiesmaster() as $keyp => $valuep) {
              $penaltyarray[$valuep->id] = $valuep->name;
            }
     		//print_r($orderdata ); exit;
     		$mpdf = new \Mpdf\Mpdf();
     		$mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/cpdoorderofpayment.html'));
            $html = str_replace('{{transactionno}}',$orderdata->transaction_no, $html);
            $html = str_replace('{{date}}',date('Y-m-d',strtotime($orderdata->created_at)), $html);
            $clientname = $orderdata->full_name;
            $html = str_replace('{{applicantname}}',$clientname, $html);
            $html = str_replace('{{telephoenno}}',$orderdata->client_telephone, $html);
            $html = str_replace('{{zoningamt}}',$orderdata->caf_total_amount, $html);
            $html = str_replace('{{penalty}}',$orderdata->penaltyamount, $html);
            $penaltyrate = $this->_cpdoappform->getinspectionorderforedit($orderdata->transaction_ref_no);
            $html = str_replace('{{precentage}}',$penaltyarray[$penaltyrate->cir_penalty], $html);
            
            $totalamit = $orderdata->caf_total_amount + $orderdata->penaltyamount;
            $html = str_replace('{{total}}',$totalamit, $html);
            $filename="";
            //$html = $html;
            //echo $html; exit;
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $orderfilename = $transid.$filename."cpdoorderofpayment.pdf";
            $folder =  public_path().'/uploads/cpdo/orderpayment/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/cpdo/orderpayment/" . $orderfilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/cpdo/orderpayment/' . $orderfilename);
    }
    public function send($message, $receipient)
    {   
        $validate = $this->componentSMSNotificationRepository->validate();
        if ($validate > 0) {
            $setting = $this->componentSMSNotificationRepository->fetch_setting();
            $details = array(
                'message_type_id' => 1,
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => $this->carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
           
                //$this->sendSms($receipient, $message);
                $this->componentSMSNotificationRepository->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }
    public function printinspection(Request $request){
     	    $id = $request->input('id');

     		$inspectiondata = $this->_cpdoappform->GetInspectiondata($id);
     		//echo "<pre>"; print_r($inspectiondata ); exit;
     		$mpdf = new \Mpdf\Mpdf();
     		$mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/cpdoinspectionreport.html'));
            $html = str_replace('{{nameofproject}}',$inspectiondata->caf_type_project, $html);
            $html = str_replace('{{dateofinspection}}',date('Y-m-d',strtotime($inspectiondata->cir_date)), $html);
            $clientname = $inspectiondata->full_name;
            $unchecked = url('/assets/images/unchecked-checkbox.jpg');
            $checked = url('/assets/images/checked-checkbox.jpeg');
            $htmldyna=' <tr>
                  <td style="text-align:left;border:0px solid black;margin-left: 20px;">
                   <p style="font-size: 15px;font-weight: 400;">A. 2 Terrin</p>
                 </td>';
            $arrterrin = config('constants.arrCpdoInspectionTerrain');
            foreach ($arrterrin as $key => $value) {
                 	if( $key == $inspectiondata->cit_id){
                 		$htmldyna .='<td style="text-align:left;border:0px solid black;margin-left: 20px;"> <p style="font-size: 15px;font-weight: 400;"><img src="'.$checked.'" style="max-width:20px;">'.$value.'</p></td>';
                 	}else{
                 		$htmldyna .='<td style="text-align:left;border:0px solid black;margin-left: 20px;"> <p style="font-size: 15px;font-weight: 400;"><img src="'.$unchecked.'" style="max-width:20px;">'.$value.'</p></td>';
                 	}
                 }     
                  $htmldyna .='
                   </tr>';
            $html = str_replace('{{terrintr}}',$htmldyna, $html);
            $html = str_replace('{{ownername}}',$clientname, $html);
            $html = str_replace('{{location}}',$inspectiondata->caf_brgy_id, $html);
            $html = str_replace('{{sitezoning}}',$inspectiondata->cir_zoning_class, $html);
            $html = str_replace('{{north}}',$inspectiondata->cir_north, $html);
            $html = str_replace('{{south}}',$inspectiondata->cir_south, $html);
            $html = str_replace('{{east}}',$inspectiondata->cir_east, $html);
            $html = str_replace('{{west}}',$inspectiondata->cir_west, $html);
            $cir_long = 'E '.$inspectiondata->cir_long_we_degree.'°'.$inspectiondata->cir_long_we_minutes."'".$inspectiondata->cir_long_we_seconds.'"'; 
            $cir_lat = 'N '.$inspectiondata->cir_lat_ns_degree.'°'.$inspectiondata->cir_lat_ns_minutes."'".$inspectiondata->cir_lat_ns_seconds.'"'; 
            $html = str_replace('{{long}}',$cir_long, $html);
            $html = str_replace('{{lat}}',$cir_lat, $html);
            $html = str_replace('{{ctdesc}}',$inspectiondata->cir_decs, $html);
            $html = str_replace('{{watersupply}}',$inspectiondata->cir_water_supply, $html);
            $html = str_replace('{{powersupply}}',$inspectiondata->cir_power_supply, $html);
            $html = str_replace('{{drainage}}',$inspectiondata->cir_drainage, $html);
            $html = str_replace('{{otherspecify}}',$inspectiondata->cir_other, $html);
            $html = str_replace('{{remarks}}',$inspectiondata->cir_remark, $html);
            $inspectiondata->caf_amount ="";
            $inspectiondata->or_no ="";
            $inspectiondata->orissueddate ="";
               if($inspectiondata->top_transaction_type_id > 0){
                   $gettopdata = $this->_cpdoappform->checkTransactionexist($inspectiondata->cafid,$inspectiondata->top_transaction_type_id); 
                   if(count($gettopdata) > 0){
                     $ordata = $this->_cpdoappform->getORandORdate($gettopdata[0]->id);
                     if(count($ordata) > 0){
                     $inspectiondata->or_no = $ordata[0]->or_no; $inspectiondata->orissueddate = $ordata[0]->created_at;
                     $inspectiondata->caf_amount =  $ordata[0]->total_amount;
                     $inspectiondata->orissueddate = date('m/d/Y',strtotime($inspectiondata->orissueddate));
                     }
                   }
                }
            $html = str_replace('{{Amount}}',$inspectiondata->caf_amount, $html);
            $html = str_replace('{{ornumber}}',$inspectiondata->or_no, $html);
            $html = str_replace('{{dateissued}}',$inspectiondata->orissueddate, $html);
            $filename="";
            $noteddata = $this->_cpdoappform->getpositionbyid($inspectiondata->cir_approved_by);
            $html = str_replace('{{preparename}}',$noteddata->fullname, $html);
            $html = str_replace('{{prePosition}}',$noteddata->description, $html);

            $approvedata = $this->_cpdoappform->getpositionbyid($inspectiondata->cir_noted_by);
            $html = str_replace('{{recomname}}',$approvedata->fullname, $html);
            $html = str_replace('{{recomPosition}}',$approvedata->description, $html);
            //$html = $html;
            $mpdf->WriteHTML($html);
        
            $filename = $inspectiondata->id."-zoning inspect order.pdf";
                    $arrSign= $this->_commonmodel->isSignApply('planning_development_zoning_inspect_order_prepared_by');
            $isSignVeified = isset($arrSign)?$arrSign->status:0;

            $arrCertified= $this->_commonmodel->isSignApply('planning_development_zoning_inspect_order_noted_by');
            $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

            $signType = $this->_commonmodel->getSettingData('sign_settings');
            
            $folder =  public_path().'/uploads/digital_certificates/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            if($signType==2){
                $mpdf->Output($folder.$filename,'F');
                @chmod($folder.$filename, 0777);
            }
            $arrData['filename'] = $filename;
            $arrData['isMultipleSign'] = 1;
            $arrData['isDisplayPdf'] = 0;
            $arrData['isSavePdf'] = 0;
            $preparedbyId = HrEmployee::where('id', $inspectiondata->cir_approved_by)->first();
            $notedId = HrEmployee::where('id', $inspectiondata->cir_noted_by)->first();
            $varifiedSignature = $this->_commonmodel->getuserSignature($preparedbyId->user_id);
            $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

            if($isSignVeified==1 && $signType==2){
                if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                    $arrData['isSavePdf'] = 1;
                    $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                    $arrData['signaturePath'] = $varifiedSignature;
                    if($isSignCertified==0 && $signType==2){
                        $arrData['isDisplayPdf'] = 1;
                            return $this->_commonmodel->applyDigitalSignature($arrData);
                    }else{
                        $this->_commonmodel->applyDigitalSignature($arrData);
                    }
                }
            }

            $certifiedSignature = $this->_commonmodel->getuserSignature($notedId->user_id);
            $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

            if($isSignCertified==1 && $signType==2){
                if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                    $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                    $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                    $arrData['isDisplayPdf'] = 1;
                    $arrData['signaturePath'] = $certifiedSignature;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }

            if($isSignCertified==1 && $signType==1){
                // Apply E-sign Here
                if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                    $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
                }
            }
            if($isSignVeified==1 && $signType==1){
                // Apply E-sign Here
                if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                    $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                }
            }
            if($signType==2){
                if(File::exists($folder.$filename)) { 
                    File::delete($folder.$filename);
                }
            }
            $mpdf->Output($filename,"I");
    }
     public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $arrDtls = $this->_cpdoappform->getEditDetails($id);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if(isset($arrDtls)){
            $arrJson = (array)json_decode($arrDtls->cir_upload_documents_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/cpdo/inspection/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrDtls)){
                    $arrJson = json_decode($arrDtls->cir_upload_documents_json,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['cir_upload_documents_json'] = json_encode($finalJsone);
                $this->_cpdoappform->updateinspectionData($id,$data);
                $arrDocumentList = $this->generateDocumentListnew($data['cir_upload_documents_json'],$id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
     public function generateDocumentListnew($arrJson,$id, $status='0'){
        $html = "";
        $dclass = ($status>0)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            if(isset($arr)){
                foreach($arr as $key=>$val){
                    $html .= "<tr>
                        <td><span class='showLessDoc'>".$val['filename']."</span></td>
                        <td><a class='btn' href='".asset('uploads/cpdo/inspection').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                        <td>
                            <div class='action-btn bg-danger ms-2'>
                                <a href='#' class='mx-3 btn btn-sm deleteAttachment ti-trash text-white text-white' id='".$id."' rid='".$val['filename']."'></a>
                            </div>
                        </td>
                    </tr>";
                }
            }
        }
        return $html;
    }
    public function inspectionreport(Request $request){
        $inspectiondata = (object)$this->inspectiondata;
        $arrOwners = $this->arrOwners;  $issurcharge = 0;
        $hremployees = $this->hremployees;
        $arrterrin = config('constants.arrCpdoInspectionTerrain');
        $applicationid =""; $approvalid =""; $inspectiondata->document_details ="";
        $penaltyarray = array();
        foreach ($this->_cpdoappform->GetPenaltiesmaster() as $keyp => $valuep) {
          $penaltyarray[$valuep->id] = $valuep->name;
        }
        $loginusedid = \Auth::user()->id;
        $arrLocations = array();
        $inspectiondata->csd_id ="";   $inspectiondata->csd_id = ""; $inspectiondata->tfoc_id="";

        $orderarray = array('id'=>'','date'=>date('Y-m-d'),'transaction_no'=>'');
        $checkidexist = $this->_cpdoappform->checkTransactionexist($request->input('id'));
        $orderpayment = (object)$orderarray;
         if(count($checkidexist)> 0){
        $orderpayment->transaction_no = $checkidexist[0]->transaction_no;
        $orderpayment->id = $checkidexist[0]->id;
        }
       

        if($request->input('id')>0 && $request->input('submit')==""){
        	 $data = $this->_cpdoappform->GetapplicationRecord($request->input('id')); 
              $getsurchargesl = $this->_cpdoappform->getCasheringIds($data->tfoc_id);  
              $issurcharge = $getsurchargesl->tfoc_surcharge_sl_id;
        	  $inspectiondata->caf_id =$request->input('id');
              $inspectiondata->cir_isapprove = "0";
              $checkinspectionisexist =  $this->_cpdoappform->isexistinspection($request->input('id'));
              //print_r($checkinspectionisexist); exit;
              if(count($checkinspectionisexist)> 0){
              	  $inspectiondata =  $this->_cpdoappform->getinspectionorderforedit($request->input('id'));
                  $arrdocDtls = $this->generateDocumentListnew($inspectiondata->cir_upload_documents_json,$data->id);
                  if(isset($arrdocDtls)){
                      $inspectiondata->document_details = $arrdocDtls;
                  }
                  $approvaldata =  $this->_cpdoappform->getapproveluserid($inspectiondata->cir_noted_by); 
                  $approvalid = $approvaldata->user_id; 
                  $arrLocations = $this->_cpdoappform->GetGeoLocationbyid($inspectiondata->id); 
              }
              $locationproject = "";
               foreach ($this->_commonmodel->getBarangay($data->caf_brgy_id)['data'] as $val) {
                 $locationproject = $val->brgy_name;
               }
                 $inspectiondata->locationproject = $locationproject;
      	         $inspectiondata->nameofproject=$data->caf_type_project;
      	         $inspectiondata->cafid = $data->client_id;
      	         $inspectiondata->csd_id = $data->csd_id; 
                 $inspectiondata->caf_amount ="";
                 $inspectiondata->or_no ="";
                 $inspectiondata->orissueddate ="";
                 $inspectiondata->client_telephone = $data->client_telephone;
                 $inspectiondata->orptotal_amount = $data->caf_total_amount + $data->penaltyamount;
                 $inspectiondata->orptotal = $data->caf_total_amount;
                 $inspectiondata->penaltyamount = $data->penaltyamount;
                 $inspectiondata->tfoc_id = $data->tfoc_id;
                 if($data->top_transaction_type_id > 0){
                   $gettopdata = $this->_cpdoappform->checkTransactionexist($data->id,$data->top_transaction_type_id); 
                   if(count($gettopdata) > 0){
                     $ordata = $this->_cpdoappform->getORandORdate($gettopdata[0]->id);
                     if(count($ordata) > 0){
                     $inspectiondata->or_no = $ordata[0]->or_no; $inspectiondata->orissueddate = $ordata[0]->created_at;
                     $inspectiondata->caf_amount =  $ordata[0]->total_amount;
                     }
                   }
                }
		         
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->inspectiondata as $key=>$val){
                $this->inspectiondata[$key] = $request->input($key);
            }
            $this->inspectiondata['updated_by']=\Auth::user()->id;
            $this->inspectiondata['updated_at'] = date('Y-m-d H:i:s');
            $this->inspectiondata['id']=$request->input('insid');
            $penalty = 0; $totalprpamount = 0;
            if($request->input('cir_penalty') > 0){
                 $appamount = $this->_cpdoappform->GetapplicationRecord($request->input('caf_id'));  
                 $penaltyper = $this->_cpdoappform->Getpenaltypercen($request->input('cir_penalty'));
                 $penaltyamount = ($appamount->caf_total_amount * $penaltyper->percentage) /100; 
                 $totalprpamount = $appamount->caf_total_amount + $penaltyamount; 
             }    
            if($request->input('insid')>0){
                if(!empty($request->input('cir_isapprove'))){
                    $this->inspectiondata['cir_isapprove'] ='1';
                    $appuptdata = array('csd_id'=>'4','cs_id'=>'2');
                    if($totalprpamount >= 0){
                      $appuptdata['penaltyamount'] = $penaltyamount;
                    }
                    $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdata);
                 }
                 if($totalprpamount >= 0){
                      $appuptdata = array();
                      $appuptdata['penaltyamount'] = $penaltyamount;
                       $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdata);
                  }
               
                $this->_cpdoappform->InspectionupdateData($request->input('insid'),$this->inspectiondata);
                 $success_msg = 'Inspection Order updated successfully.';
                 $lastinsertid = $request->input('insid');
                //$this->_cpdoappform->updateData($appid,$appuptdata);
               }else{
               	$this->inspectiondata['created_by']=\Auth::user()->id;
                $this->inspectiondata['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_cpdoappform->InspectionaddData($this->inspectiondata);
                $appuptdata = array('csd_id'=>'3');
                if($totalprpamount >= 0){
                      $appuptdata['penaltyamount'] = $penaltyamount;
                    }
                $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdata);
                $success_msg = 'Inspection Order added successfully.';
            }
            if(isset($_POST['linkdesc'])){
             foreach ($_POST['linkdesc'] as $key => $value){ 
                        // print_r($image); exit;
                         $locationarray = array();
                         $locationarray['cir_id'] = $lastinsertid;
                         $locationarray['cig_location_description'] = $value;
                         $locationarray['cig_remarks'] = $_POST['remark'][$key];
                         $locationarray['created_by']=\Auth::user()->id;
                         $locationarray['created_at'] = date('Y-m-d H:i:s');
                          if(!empty($_POST['geoid'][$key])){
                           // $this->_cpdoappform->UpdateGeoLocationData($_POST['geoid'][$key],$locationarray);
                         }else{ //$this->_cpdoappform->AddGeoLocationData($locationarray); 
                         }
                     
                    }
            }
            return redirect()->route('cpdoapplication.index')->with('success', __($success_msg));
    	}
        return view('cpdo.application.inspectionreport',compact('inspectiondata','arrterrin','arrOwners','hremployees','loginusedid','approvalid','arrLocations','penaltyarray','orderpayment','issurcharge'));
	}

    public function savegeolocations(Request $request){
        $insid = $request->input('inspectonid'); 
         $newValidation = [
             'inspectonid' => 'required',
             "linkdesc"  => 'required',
             "linkdesc.*"  => "required|url",
        ];
        $validator = \Validator::make(
            $request->all(),  $newValidation,
            [
            'inspectonid.required' => 'Required Field',
            'linkdesc.*.required' => 'Required Field',
            'linkdesc.*.url' => 'Invalid URL',
        ]
        );
        
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
            echo json_encode($arr);exit;
        }
        //dd($request->all());
        if(isset($_POST['linkdesc'])){
            //dd($request->all());
             foreach ($_POST['linkdesc'] as $key => $value){ 
                        // print_r($image); exit;
                         $locationarray = array();
                         $locationarray['cir_id'] = $insid;
                         $locationarray['cig_location_description'] = $value;
                         $locationarray['cig_remarks'] = $_POST['remark'][$key];
                         $locationarray['created_by']=\Auth::user()->id;
                         $locationarray['created_at'] = date('Y-m-d H:i:s');
                         if(!empty($_POST['geoid'][$key])){
                             $this->_cpdoappform->UpdateGeoLocationData($_POST['geoid'][$key],$locationarray);
                         }else{ 
                            // $checkisexist = $this->_cpdoappform->checkGeoLocationexist($insid,$value);
                            // if(count($checkisexist)> 0){
                            //     $this->_cpdoappform->UpdateGeoLocationData($checkisexist[0]->id,$locationarray);
                            // }else{
                            //    $this->_cpdoappform->AddGeoLocationData($locationarray); 
                            // }
                            $this->_cpdoappform->AddGeoLocationData($locationarray); 
                        }
                     
                    }
         }                                                                            

            $arr =array('status'=>'success','msg'=>'data saved successfully');

            echo json_encode($arr);
    }

	public function certification(Request $request){
        
		$certificatedata = (object)$this->certificate;
        $arrOwners = $this->arrOwners;
        $hremployees = $this->hremployees;
        $arrterrin = config('constants.arrCpdoInspectionTerrain');
        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        $applicationid =""; $preparedid =""; $recommendedid =""; $notedid ="";  $approvalid="";
        $loginusedid = \Auth::user()->id; $barngayName ="";
        $certificatedata->cc_approval_status ="";
       
        if($request->input('id')>0 && $request->input('submit')==""){
        	 $data = $this->_cpdoappform->GetapplicationRecord($request->input('id'));  //CpdoApplicationForm::find($request->input('id'))
              //echo "here"; exit;
        	  $certificatedata->caf_id =$request->input('id');
              $checkinspectionisexist =  $this->_cpdoappform->isexistcertificate($request->input('id'));
              if(count($checkinspectionisexist)> 0){
              	 $certificatedata =  $this->_cpdoappform->getcertificateforedit($request->input('id'));
                 $preparedata =  $this->_cpdoappform->getapproveluserid($certificatedata->preparedby); 
                 $preparedid = $preparedata->user_id;
                 $recommenddata =  $this->_cpdoappform->getapproveluserid($certificatedata->cc_recom_approval); 
                 $recommendedid = $recommenddata->user_id;
                 $noteddata =  $this->_cpdoappform->getapproveluserid($certificatedata->cc_noted); 
                 $notedid = $noteddata->user_id;
                 $approvaldata =  $this->_cpdoappform->getapproveluserid($certificatedata->cc_approved); 
                 $approvalid = $approvaldata->user_id;
                }
               $barngayAddress = $this->_Barangay->getBarangayname($data->caf_brgy_id); 
                if(!empty($barngayAddress)){
                   $barngayName = $barngayAddress->brgy_name;
                }
                 $certificatedata->locationproject = $data->caf_name_firm;
		         $certificatedata->telephone=$data->client_telephone;
		         $certificatedata->cafid = $data->client_id;
		         $certificatedata->caf_amount =$data->caf_amount;
                 $certificatedata->cc_name_project = $data->caf_type_project;
		         $certificatedata->transaction_no =$data->transaction_no;
                 $certificatedata->cclocation =  $barngayName;
          }
		  if($request->input('submit')!=""){
            foreach((array)$this->certificate as $key=>$val){
                $this->certificatedata[$key] = $request->input($key);
            }

            $this->certificatedata['updated_by']=\Auth::user()->id;
            $this->certificatedata['updated_at'] = date('Y-m-d H:i:s');
            $this->certificatedata['id']=$request->input('certid');
            $apv_send=1;
            if($request->input('certid')>0){
                $ext_data=$this->_cpdoappform->findCertificateDataById($request->input('certid'));
                if(!empty($request->input('cc_recom_status'))){
                    $this->certificatedata['cc_recom_status'] ='1';
                    $this->certificatedata['cc_recom_approval_date'] =date('Y-m-d');
                    $appuptdataapp = array('csd_id'=>'7'); 
                    $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdataapp);
                 }
                 if(!empty($request->input('cc_notes_status'))){
                    $this->certificatedata['cc_notes_status'] ='1';
                    $this->certificatedata['cc_noted_date'] =date('Y-m-d');
                    $appuptdataapp = array('csd_id'=>'6'); 
                    $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdataapp);
                 }
                 if(!empty($request->input('cc_approval_status'))){
                    $this->certificatedata['cc_approval_status'] ='1';
                    $this->certificatedata['cc_approved_date'] =date('Y-m-d');
                    $appuptdataapp = array('csd_id'=>'8'); 
                    $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdataapp);
                 }
                $this->_cpdoappform->CertificateupdateData($request->input('certid'),$this->certificatedata);
                $success_msg = 'Certificate Order updated successfully.';
                if($ext_data->cc_approval_status == 1)
                {
                    $apv_send=0;
                }
                $cert_id = $request->input('certid');
               }else{
                $this->certificatedata['cc_falc_no'] = date('m-Y')."-".$this->certificatedata['cc_applicant_no']; 
               	$this->certificatedata['created_by']=\Auth::user()->id;
                $this->certificatedata['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_cpdoappform->CertificateaddData($this->certificatedata);
                $appuptdata = array('csd_id'=>'5','cs_id'=>'3');
                $this->_cpdoappform->updateData($request->input('caf_id'),$appuptdata);
                $success_msg = 'Certificate Order added successfully.';
                $cert_id=$lastinsertid;
            }

            if($request->input('cc_approval_status') == 1 && $apv_send == 1){
                $orderdata = $this->_cpdoappform->findCertificateDataById($cert_id);
                $smsTemplate=SmsTemplate::where('id',12)->where('is_active',1)->first();
                if(!empty($smsTemplate))
                {
                    $receipient=$orderdata->p_mobile_no;
                    $msg=$smsTemplate->template;
                    $msg = str_replace('<NAME>', $orderdata->full_name,$msg);
                    $msg = str_replace('<REFERENCE_NO>', $orderdata->cc_falc_no,$msg);
                    $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                    $this->send($msg, $receipient);
                }
            }
            return redirect()->route('cpdoapplication.index')->with('success', __($success_msg));
    	}
        // echo $loginusedid; print_r($certificatedata); exit;
        return view('cpdo.application.certificate',compact('certificatedata','arrterrin','arrOwners','hremployees','arrCpdoOverland','loginusedid','preparedid','recommendedid','notedid','approvalid'));
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

   public function getBarngayList(Request $request){
       $search = $request->input('search');
       $getmincipalityid = $this->_cpdoappform->getCpdomunciapality(); $munid ="";
       if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
        $arrRes = $this->_cpdoappform->getBarangay($search,$munid);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
   }

    public function getEmployeeListAjax(Request $request){
       $search = $request->input('search');
        $arrRes = $this->_cpdoappform->getEmployees($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->fullname;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
   }

    public function store(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $arrRequirements = array();  $apptype = array(""=>"Select Service");
        $data->caf_total_amount ="";  
        $requirement =$this->requirement;
        $arrOwners = $this->arrOwners;
        $arrgetBrgyCode = array(""=>"Please Select");
        $checkrecords = $this->_cpdoappform->GetcpdolatestApp();
        if(!empty($checkrecords)){
        	$getServices = array(""=>"Please Select");
        	foreach ($this->_cpdoappform->getServicesbyid($checkrecords->tfoc_id) as $val) {
             $getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
            }
            //$getServices['88'] = "[40201010 - Permit Fees]=>[11 - Zoning Clearance/Location Permit Fees - Business]";

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
        $arrApptype = config('constants.arrCpdoNatureApp');
        foreach ($this->_cpdoappform->getServiceTypearraydefault() as $k => $valat) {
          $apptype[$valat->id] = $valat->cm_module_desc;
        }
        $arrtenure = config('constants.arrCpdoProjectTenure');
        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        //$apptype = config('constants.arrCpdoAppModule'); 
        $applicationid ="";
        $data->is_approve ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CpdoApplicationForm::find($request->input('id'));
            foreach ($this->_commonmodel->getBarangay($data->caf_brgy_id)['data'] as $val) {
                $arrgetBrgyCode[$val->id]=$val->brgy_name;
            }
            $arrRequirements = $this->_cpdoappform->getAppRequirementsData($request->input('id'));
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
            $this->data['is_synced'] = '0';
            $this->data['caf_amount'] = (int)$request->input('caf_amount');
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
              $this->data['caf_excempted'] = '0';
            }
            $this->data['totalamount'] = $this->data['caf_total_amount'];
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
                  $appreqarr['is_synced'] = '0';
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
          Session::put('REMOTE_SYNC_APPFORMID',$currentappid);
            return redirect()->route('cpdoapplication.index')->with('success', __($success_msg));
    	}
        return view('cpdo.application.create',compact('data','arrRequirements','arrOwners','getServices','arrApptype','arrtenure','arrCpdoOverland','apptype','orderpayment','requirement','arrgetBrgyCode'));
	}

	 public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'client_id'=>'required',
                'tfoc_id'=>'required',
                'cna_id' =>'required',
                'caf_brgy_id'=>'required'
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

     public function formValidationinspect(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'cir_use_res'=>'required',
                'cit_id'=>'required',
                'cafid' =>'required'
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

    public function formValidationcerti(Request $request){
    	$validator = \Validator::make(
            $request->all(), [
                'cc_applicant_no'=>'required',
                'cc_date'=>'required',
                'preparedby'=>'required',
                'cc_recom_approval'=>'required',
                'cc_noted'=>'required',
                'cc_approved'=>'required'
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
