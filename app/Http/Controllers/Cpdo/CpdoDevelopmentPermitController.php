<?php

namespace App\Http\Controllers\Cpdo;
use App\Http\Controllers\Controller;
use App\Models\Cpdo\CpdoDevelopmentPermit;
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
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Models\BploBusiness;


class CpdoDevelopmentPermitController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $approval_status=[];
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
        $this->_cpdodevelopmentapp= new CpdoDevelopmentPermit(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->data = array('id'=>'','cdp_control_no'=>'','cpdo_module_id'=>'','client_id'=>'','cdp_address'=>'','cdp_email_address'=>'','cdp_phone_no'=>'','tfoc_id'=>'','tfoc_idtype'=>'','nameofproject'=>'','locationofproject'=>'');
        $this->approval_status = array('0' => "Pending",'1' => "Approved",'2' => "Declined");
        $this->inspectiondata = array('id'=>'','cir_date'=>date('Y-m-d'),'caf_id'=>'','cir_zoning_class'=>'','cir_use_res'=>'','cit_id'=>'','cir_north'=>'','cir_south'=>'','cir_east'=>'','cir_west'=>'','cir_long_we_degree'=>'','cir_long_we_minutes'=>'','cir_long_we_seconds'=>'','cir_lat_ns_degree'=>'','cir_lat_ns_minutes'=>'','cir_lat_ns_seconds'=>'','cir_long'=>'','cir_lat'=>'','cir_water_supply'=>'','cir_decs'=>'','cir_power_supply'=>'','cir_drainage'=>'','cir_other'=>'','cir_remark'=>'','cir_approved_by'=>'','cir_noted_by'=>'','cir_penalty'=>'');
        $this->certificate = array('id'=>'','cc_applicant_no'=>'','cc_date'=>'','caf_id'=>'','cc_falc_no'=>'','cc_rol'=>'','cc_boc'=>'','cc_name_project'=>'','cc_area'=>'','cc_location'=>'','cc_project_class'=>'','cc_site_classification'=>'','cc_dominant'=>'','cc_evaluation'=>'','cc_decision'=>'','preparedby'=>'','cir_created_position'=>'','cc_recom_approval'=>'','cc_recom_approval_position'=>'','cc_noted'=>'','cc_noted_position'=>'','cc_approved'=>'','cc_approved_position'=>'','projecttelno'=>'','developername'=>'','developerlocation'=>'','developtelno'=>'');     
        $this->slugs = 'cpdodevelopmentapp'; 
        foreach ($this->_cpdodevelopmentapp->getOwners() as $val) {
             $this->arrOwners[$val->id]=$val->full_name;
         }
         foreach ($this->_cpdodevelopmentapp->getServices() as $val) {
             $this->getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }
         foreach ($this->_cpdodevelopmentapp->gethremployess() as $val){
             $this->hremployees[$val->id]=$val->fullname;
         } 
         foreach ($this->_cpdodevelopmentapp->getRequirements() as $val){
             $this->requirement[$val->id]=$val->req_code_abbreviation." - ".$val->req_description;
         } 
    }
    public function getRequirementsAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_cpdodevelopmentapp->getRequirementsAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->req_code_abbreviation." - ".$val->req_description;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function index(Request $request)
    {       $barangay=array(""=>"Please select");
             $getmincipalityid = $this->_cpdodevelopmentapp->getCpdomunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
            foreach ($this->_cpdodevelopmentapp->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
            }
            $statusarray = config('constants.arrCpdoStatusDetails');
            $to_date=Carbon::now()->format('Y-m-d');
            $from_date=Carbon::now()->format('Y-m-d');
            $this->is_permitted($this->slugs, 'read');
                return view('cpdo.development.index',compact('barangay','to_date','from_date','statusarray'));
    }

    public function onlineindex(Request $request)
    {
        $this->is_permitted("online-development-permit", 'read');
        $barangay=array(""=>"Please select");
         $getmincipalityid = $this->_cpdodevelopmentapp->getCpdomunciapality(); $munid ="";
            if(!empty($getmincipalityid)){ $munid = $getmincipalityid->mun_no;}
        foreach ($this->_cpdodevelopmentapp->getBarangaybymunno($munid) as $val) {
             $barangay[$val->id]=$val->brgy_name;
            }
        $status= [
            "4"=>"All",
            '0' => 'Pending',
            '2' => 'Declined',
        ];
        $to_date="";
        $from_date="";
        return view('cpdo.development.online')->with(compact('barangay','status','to_date','from_date'));
    }

    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_cpdodevelopmentapp->updateData($id,$data);
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cpdodevelopmentapp->getList($request);
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
            $arr[$i]['caf_control_no']=$row->cdp_control_no;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['projectname']=$row->nameofproject;
            $barngayName = "";
            $barngayAddress = $this->_Barangay->getBarangayname($row->project_barangay_id);
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['address']=$barngayName;
            $arr[$i]['sdetail']=config('constants.arrCpdoStatus')[$row->cs_id];
            $arr[$i]['status']=config('constants.arrCpdoStatusDetails')[$row->csd_id];
            $arr[$i]['is_online']=($row->is_online == 0)? 'Walkin':'Online'; 
            $arr[$i]['topno']=""; 
            $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_cpdodevelopmentapp->checkTransactionexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_cpdodevelopmentapp->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                 $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }
            $totalamount = $row->cdp_total_amount + $row->penaltyamount;
            $arr[$i]['amount']=number_format($totalamount,2);              
            $arr[$i]['orno']=$orno; 
            $arr[$i]['ordate']=$ordate;  $startDate ="";
            if(!empty($row->date)){
            $arr[$i]['date']=date('M d, Y',strtotime($row->date));
            $startDate = Carbon::parse($row->date);
            }else{
               $arr[$i]['date']=date('M d, Y',strtotime($row->date)); 
            }
            $endDate = Carbon::parse(date('Y-m-d'));
            $diff = $startDate->diff($endDate);
            
            if ($row->csd_id == 9) {
                $duration = "";
            } else {
                $duration = $diff->days . " Day";
            } 
            $arr[$i]['duration']=$duration;
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdodevelopmentapp/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Development Permit Application">
                        <i class="ti-pencil text-white"></i>
                    </a></div>';
                   
                    $arr[$i]['action'] .='<div class="action-btn bg-warning ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdodevelopmentapp/inspectionreport?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Inspection Order"  data-title="Manage Development Permit Inspection Order">
                            <i class="ti-eye text-white"></i>
                        </a></div>';
                    
                    if(!empty($row->cirid) && $row->csd_id >='4'){
                        $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cpdodevelopmentapp/certification?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Certificate"  data-title="Manage Development Permit Certificate">
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

    public function onlineGetList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_cpdodevelopmentapp->getListonline($request);
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
            $controlno = $row->cdp_control_no;
            if(empty($row->cdp_control_no) || $row->cdp_control_no =='0'){
                $appno = str_pad($row->id, 6, '0', STR_PAD_LEFT);
                $controlno='ONLINE-'.$appno;
            }
            $arr[$i]['caf_control_no']=$controlno;
            //$arr[$i]['caf_control_no']=$row->cdp_control_no;
            $arr[$i]['ownername']=$row->full_name;
            $arr[$i]['projectname']=$row->nameofproject;
            $barngayName = "";
            $barngayAddress = $this->_Barangay->getBarangayname($row->project_barangay_id);
            if(!empty($barngayAddress)){
               $barngayName = $barngayAddress->brgy_name;
            }
            $arr[$i]['address']="<div class='showLess'>".$barngayName."</div>";
            $arr[$i]['sdetail']=config('constants.arrCpdoStatus')[$row->cs_id];
            // $arr[$i]['status']=config('constants.arrCpdoStatusDetails')[$row->csd_id]; 
            $arr[$i]['topno']=""; 
            $orno =""; $ordate="";
            if($row->top_transaction_type_id > 0){
               $gettopdata = $this->_cpdodevelopmentapp->checkTransactionexist($row->id,$row->top_transaction_type_id); 
               if(count($gettopdata) > 0){
                $arr[$i]['topno']=$gettopdata[0]->transaction_no;
                //print_r($gettopdata); exit;
                 $ordata = $this->_cpdodevelopmentapp->getORandORdate($gettopdata[0]->id);
                 if(count($ordata) > 0){
                 $orno = $ordata[0]->or_no; $ordate = $ordata[0]->created_at; 
                 }
               }
            }
            $startCarbon = Carbon::parse($row->created_at);
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
            $arr[$i]['duration']=$duration; 
            //$arr[$i]['approval_status']=$this->approval_status[$row->is_approved]; 
           if($row->is_approved == '0'){
                $status = '<span class="btn btn-info" style="padding: 0.1rem 0.5rem !important;">Pending</span>';
            }
            if($row->is_approved == '2'){
                $status = '<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Declined</span>';
            }
            if($row->is_approved == '1'){
                $status = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Approved</span>';
            } 
            $arr[$i]['approval_status']=$status;  
            $arr[$i]['amount']=$row->cdp_total_amount;              
            $arr[$i]['orno']=$orno; 
            $arr[$i]['date']=date('Y-m-d',strtotime($row->created_at)); 
            $arr[$i]['method']='Online';
            $arr[$i]['action']='
                <div class="action-btn bg-success ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/online-development-permit/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Development Permit: Online Application">
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
    public function saveorderofpayment(Request $request){
         $id= $request->input('appid');
         $clientdata = $this->_cpdodevelopmentapp->getclientidbyid($id);
         $tfocid= $request->input('tfocid');
         $amount= $request->input('amount');
         $data =array();
         $data['top_transaction_type_id'] = 44;
         $data['tfoc_is_applicable'] = '5';
         $data['transaction_ref_no'] = $id;
         $data['tfoc_id'] = $tfocid;
         $amount = str_replace(",", "", $amount);
         $data['amount'] = $amount;
         $checkidexist = $this->_cpdodevelopmentapp->checkTransactionexist($id);
         if(count($checkidexist)> 0){
             $filename = $this->saveorderofpaymentfile($checkidexist[0]->id);
             $appuptdata = array('amount'=>$amount,'attachment'=>$filename);
                $this->_cpdodevelopmentapp->TransactionupdateData($checkidexist[0]->id,$appuptdata);
            $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$checkidexist[0]->transaction_no,'transid'=>$checkidexist[0]->id];
         }else{
             $data['created_at'] = date('Y-m-d H:i:s');
             $lastinsert =$this->_cpdodevelopmentapp->TransactionaddData($data);
             $filename = $this->saveorderofpaymentfile($lastinsert);
                $transactionno = str_pad($lastinsert, 6, '0', STR_PAD_LEFT);
                $updatedata = array('transaction_no'=>$transactionno,'attachment'=>$filename);
                $this->_cpdodevelopmentapp->TransactionupdateData($lastinsert,$updatedata);
                $appuptdata = array('top_transaction_type_id'=>44);
                $this->_cpdodevelopmentapp->updateData($id,$appuptdata);
                $array = ["status"=>"success","message" =>"Data Saved Successfully.",'transactionno'=>$transactionno,'transid'=>$lastinsert];
               
                $orderdata = $this->_cpdodevelopmentapp->findDataById($id);
                $smsTemplate=SmsTemplate::where('id',13)->where('is_active',1)->first();
                if(!empty($smsTemplate))
                {
                    $receipient=$orderdata->p_mobile_no;
                    $msg=$smsTemplate->template;
                    $msg = str_replace('<NAME>', $orderdata->full_name,$msg);
                    $msg = str_replace('<TOP_NO>', $transactionno,$msg);
                    $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                    $this->send($msg, $receipient);
                }
         }

         $updateremotedata = array();
         $updateremotedata['topno'] = $transactionno;
         $updateremotedata['cashieramount'] = $amount;
         $this->_cpdodevelopmentapp->updateremotedevdata($id,$updateremotedata);
        
         echo json_encode($array);
    }

     public function storeDevelopbillSummary(Request $request){
      if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
          $id = $request->input('appid'); 
          $transactionno =  $request->input('transactionno');
          $clientdata = $this->_cpdodevelopmentapp->getclientidbyid($id);
          $arrTran = $this->_cpdodevelopmentapp->getBillDetails($transactionno,$id);
          if(isset($arrTran)){ 
             $billsaummary = array();
             $billsaummary['permit_id'] = $id; 
             $billsaummary['client_id'] = $clientdata->client_id;
             $billsaummary['cpdo_type'] = 2;
             $billsaummary['bill_year'] = date('Y');
             $billsaummary['bill_month'] = date('m');
             $billsaummary['total_amount'] = $arrTran->amount;
             $billsaummary['pm_id'] = 1;
             $billsaummary['attachement'] = $arrTran->attachment;
             $billsaummary['transaction_no'] = $arrTran->transaction_no;
             
          //This is for Main Server
            $arrBill = DB::table('planning_bill_summary')->select('id')->where('permit_id',$id)->where('cpdo_type','2')->where('transaction_no',$arrTran->transaction_no)->first();
            if(isset($arrBill)){
                DB::table('planning_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
            }else{
                 $billsaummary['created_by'] = \Auth::user()->id;
                 $billsaummary['created_at'] = date('Y-m-d H:i:s');
                $this->_cpdodevelopmentapp->insertbillsummary($billsaummary);
            }

            // This is for Remote Server
                $destinationPath =  public_path().'/uploads/billing/cpdo/'.$arrTran->attachment;
                $fileContents = file_get_contents($destinationPath);
                $remotePath = 'public/uploads/billing/cpdo/'.$arrTran->attachment;
                Storage::disk('remote')->put($remotePath, $fileContents);
                $remortServer = DB::connection('remort_server');
                $arrBill = $remortServer->table('planning_bill_summary')->select('id')->where('permit_id',$id)->where('cpdo_type','2')->where('transaction_no',$arrTran->transaction_no)->first();

                try {
                    if(isset($arrBill)){
                        $remortServer->table('planning_bill_summary')->where('id',$arrBill->id)->update($billsaummary);
                    }else{
                        $billsaummary['created_by'] =  \Auth::user()->id;
                        $billsaummary['created_at'] =  date('Y-m-d H:i:s');
                       $this->_cpdodevelopmentapp->insertbillsummaryremote($billsaummary);
                    }
                    DB::table('planning_bill_summary')->where('permit_id',$id)->where('cpdo_type','2')->where('transaction_no',$arrTran->transaction_no)->update(array('is_synced'=>1));
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
        $this->_cpdodevelopmentapp->updateData($id,$appuptdata);

        $appuptdata = array('cir_isapprove'=>'1','cir_approved_date'=>date('Y-m-d'));
        $this->_cpdodevelopmentapp->updateinspectionData($id,$appuptdata);
        $array = ["status"=>"success","message" =>"Approved Successfully."];
        echo json_encode($array);
    }

     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $eid = $request->input('eid');
        $arrRequirements = $this->_cpdodevelopmentapp->getRequirementsbyid($rid);
        if(count($arrRequirements) > 0){
            if($arrRequirements[0]->cf_name){
                $path =  public_path().'/uploads/'.$arrRequirements[0]->cf_path."/".$arrRequirements[0]->cf_name;
                if(File::exists($path)) { 
                    unlink($path);

                }
                $this->_cpdodevelopmentapp->deleteRequirementsbyid($rid);
                if(!empty($eid)){
                   $this->_cpdodevelopmentapp->deleteimagerowbyid($eid); 
                }
                echo "deleted";
            }
        }
    }

    public function insepectiondeleteAttachment(Request $request){
        $rid = $request->input('rid');
        $id = $request->input('id');
        $arr = $this->_cpdodevelopmentapp->getEditDetails($id);
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
                    $this->_cpdodevelopmentapp->updateinspectionData($id,$data);
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
        $this->_cpdodevelopmentapp->CertificateupdateData($id,$appuptdata);
        $this->_cpdodevelopmentapp->updateData($cafid,$appuptdataapp);
        $array = ["status"=>"success","message" =>"Approved Successfully."];
        echo json_encode($array);
    }

    public function positionbyid(Request $request){
        $id= $request->input('id');
        $posid= $request->input('posid');
        $data = $this->_cpdodevelopmentapp->getpositionbyid($id);
        echo $data->description;
    }

     public function getProfileDetails(Request $request){
        $id= $request->input('pid');
        $data = $this->_cpdodevelopmentapp->getProfileDetails($id);
        $data->fulladdress = $this->_commonmodel->getTaxPayerAddress($data->clientid);
        echo json_encode($data);
    }

    public function getRequirements(Request $request){
           $tfocid= 89;
           $requirements = $this->_cpdodevelopmentapp->getSercviceRequirements($tfocid);
           $reqhtml = "";  $i=0;
           foreach ($requirements as $key => $value) {
               $reqhtml .= '<div class="removerequirementsdata row pt10" style="padding-bottom:5px;">';
               $reqhtml .= '<div class="col-lg-6 col-md-6 col-sm-6">
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
               $reqhtml .= '<div class="col-lg-2 col-md-2 col-sm-2" style="text-align: end;padding-right: 35px;">
                     <div class="form-group">
                        <div class="form-icon-user"><button type="button" class="btn btn-danger btn_cancel_requirement" style="padding:3px 8px;"><i class="ti-trash"></i></button>
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
            $html = str_replace('{{nameandfirm}}',$clientname." ".$appdata->caf_name_firm, $html);
            $represedata = $this->_cpdodevelopmentapp->Getclientbyid($appdata->caf_client_representative_id);
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
           $arrterrin = config('constants.arrCpdoInspectionTerrain'); $appoftenure ="";
            foreach ($arrterrin as $key => $value) {
                    if( $key == $appdata->cpt_id){
                        $appoftenure .='<div style="width:33%; float:left;"> <img src="'.$checked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</div>';
                    }else{
                        $appoftenure .='<div style="width:33%; float:left;"><img src="'.$unchecked.'" style="max-width:20px;">&nbsp;&nbsp;'.$value.'</div>';
                    }
                 } 
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
            $html = str_replace('{{location}}',$appdata->caf_complete_address, $html);
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
            $this->_cpdodevelopmentapp->updateData($cafid,$appuptdata);
            $issuedarray = array('issued_certificate'=>'1');
            $this->_cpdodevelopmentapp->CertificateupdatebyCafid($cafid,$issuedarray);
            $certificatedata = $this->_cpdodevelopmentapp->getCertificateData($certid);
            //print_r($certificatedata ); exit;
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;

            $html = file_get_contents(resource_path('views/layouts/templates/developmentcertificate.html'));
            $arrCpdoOverland = config('constants.arrCpdoOverland'); 
            $logo = url('/assets/images/logo.png');
            $logo2 = url('/assets/images/logo2.jpg');  
            $bgimage = url('/assets/images/clearancebackground.jpg');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{falcno}}',$certificatedata->cc_falc_no, $html);
            $html = str_replace('{{appno}}',$certificatedata->cdp_control_no, $html);
            $html = str_replace('{{dateissued}}',date('M,d,Y',strtotime($certificatedata->cc_date)), $html);
            $clientname = $certificatedata->full_name;
            $html = str_replace('{{nameofapplicant}}',$clientname, $html);
            $html = str_replace('{{nameofproject}}','', $html);
            $html = str_replace('{{telephoneno}}',$certificatedata->cdp_phone_no, $html);
            $html = str_replace('{{location}}',$certificatedata->cc_area." ".$certificatedata->cc_location, $html);
            $html = str_replace('{{overland}}',$arrCpdoOverland[$certificatedata->cc_rol], $html);
            $html = str_replace('{{classification}}',$certificatedata->cc_project_class, $html);
            $html = str_replace('{{clearance}}',$certificatedata->cc_boc, $html);
            $html = str_replace('{{siteclass}}',$certificatedata->cc_site_classification, $html);
            $html = str_replace('{{dominnat}}',$certificatedata->cc_dominant, $html);
            $html = str_replace('{{evalution}}',$certificatedata->cc_evaluation, $html);
            $html = str_replace('{{decision}}',$certificatedata->cc_decision, $html); 
            
            $predata = $this->_cpdodevelopmentapp->getpositionbyid($certificatedata->preparedby);
            $html = str_replace('{{preparename}}',$predata->fullname, $html);
            $html = str_replace('{{prePosition}}',$predata->description, $html);

            $recomdata = $this->_cpdodevelopmentapp->getpositionbyid($certificatedata->cc_recom_approval);
            $html = str_replace('{{recomname}}',$recomdata->fullname, $html);
            $html = str_replace('{{recomPosition}}',$recomdata->description, $html);

            $noteddata = $this->_cpdodevelopmentapp->getpositionbyid($certificatedata->cc_noted);
            $html = str_replace('{{notedname}}',$noteddata->fullname, $html);
            $html = str_replace('{{notedPosition}}',$noteddata->description, $html);

            $approvedata = $this->_cpdodevelopmentapp->getpositionbyid($certificatedata->cc_approved);
            $html = str_replace('{{approvename}}',$approvedata->fullname, $html);
            $html = str_replace('{{appPosition}}',$approvedata->description, $html);
            $certificatedata->caf_amount ="";
            $certificatedata->or_no ="";
            $certificatedata->orissueddate ="";
               if($certificatedata->top_transaction_type_id > 0){
                   $gettopdata = $this->_cpdodevelopmentapp->checkTransactionexist($certificatedata->cafid,$certificatedata->top_transaction_type_id); 
                   if(count($gettopdata) > 0){
                     $ordata = $this->_cpdodevelopmentapp->getORandORdate($gettopdata[0]->id);
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
            $filename="develop";
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
            $Prepared_By= $this->_commonmodel->isSignApply('planning_development_permit_certificate_prepared_by');
        $isSignPrepared = isset($Prepared_By)?$Prepared_By->status:0;

        $Recommended_By= $this->_commonmodel->isSignApply('planning_development_permit_certificate_recommend_by');
        $isSignRecommended = isset($Recommended_By)?$Recommended_By->status:0;

        $Noted_By= $this->_commonmodel->isSignApply('planning_development_permit_certificate_noted_by');
        $isSignNoted = isset($Noted_By)?$Noted_By->status:0;

        $Approved_By= $this->_commonmodel->isSignApply('planning_development_permit_certificate_approval_by');
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
        //$appid = $request->input('appid');  //$transid = $request->input('id');
        $orderdata = $this->_cpdodevelopmentapp->GetOrderdata($transid);
        //print_r($orderdata ); exit;
        $penaltyarray = array();
        foreach ($this->_cpdodevelopmentapp->GetPenaltiesmaster() as $keyp => $valuep) {
          $penaltyarray[$valuep->id] = $valuep->name;
        }
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $html = file_get_contents(resource_path('views/layouts/templates/delporderofpayment.html'));
        $html = str_replace('{{transactionno}}',$orderdata->transaction_no, $html);
        $html = str_replace('{{date}}',date('Y-m-d',strtotime($orderdata->created_at)), $html);
        $clientname = $orderdata->full_name;
        $html = str_replace('{{applicantname}}',$clientname, $html);
        $html = str_replace('{{telephoenno}}',$orderdata->cdp_phone_no, $html);
        $html = str_replace('{{zoningamt}}',$orderdata->cdp_total_amount, $html);
        $html = str_replace('{{penalty}}',$orderdata->penaltyamount, $html);
        $penaltyrate = $this->_cpdodevelopmentapp->getinspectionorderforedit($orderdata->transaction_ref_no);
        $html = str_replace('{{precentage}}',$penaltyarray[$penaltyrate->cir_penalty], $html);
        $totalprpamount = $orderdata->cdp_total_amount + $orderdata->penaltyamount; 
        $html = str_replace('{{total}}',$orderdata->cdp_total_amount, $html);
        $filename="";
        //$html = $html;
        //echo $html; exit;
        $mpdf->WriteHTML($html);
       
       //$filename = str_replace(' ','', $applicantname);
        $orderfilename = $transid.$filename."cpdodeveloppayment.pdf";
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
            $orderdata = $this->_cpdodevelopmentapp->GetOrderdata($transid);
            //print_r($orderdata ); exit;
            $penaltyarray = array();
            foreach ($this->_cpdodevelopmentapp->GetPenaltiesmaster() as $keyp => $valuep) {
              $penaltyarray[$valuep->id] = $valuep->name;
            }
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/delporderofpayment.html'));
            $html = str_replace('{{transactionno}}',$orderdata->transaction_no, $html);
            $html = str_replace('{{date}}',date('Y-m-d',strtotime($orderdata->created_at)), $html);
            $clientname = $orderdata->full_name;
            $html = str_replace('{{applicantname}}',$clientname, $html);
            $html = str_replace('{{telephoenno}}',$orderdata->cdp_phone_no, $html);
            $html = str_replace('{{zoningamt}}',$orderdata->cdp_total_amount, $html);
            $html = str_replace('{{penalty}}',$orderdata->penaltyamount, $html);
            $penaltyrate = $this->_cpdodevelopmentapp->getinspectionorderforedit($orderdata->transaction_ref_no);
            $html = str_replace('{{precentage}}',$penaltyarray[$penaltyrate->cir_penalty], $html);
            $totalprpamount = $orderdata->cdp_total_amount + $orderdata->penaltyamount; 
            $html = str_replace('{{total}}',$orderdata->cdp_total_amount, $html);
            $filename="";
            //$html = $html;
            //echo $html; exit;
            $mpdf->WriteHTML($html);
           
           //$filename = str_replace(' ','', $applicantname);
            $orderfilename = $transid.$filename."cpdodeveloppayment.pdf";
            $folder =  public_path().'/uploads/cpdo/orderpayment/';
            if(!File::exists($folder)) { 
                File::makeDirectory($folder, 0755, true, true);
            }
            $filename = public_path() . "/uploads/cpdo/orderpayment/" . $orderfilename;
            $mpdf->Output($filename, "F");
            @chmod($filename, 0777);
            echo url('/uploads/cpdo/orderpayment/' . $orderfilename);
    }

    public function printinspection(Request $request)
    {
            $id = $request->input('id');
            $inspectiondata = $this->_cpdodevelopmentapp->GetInspectiondata($id);
            //echo "<pre>"; print_r($inspectiondata ); exit;
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $html = file_get_contents(resource_path('views/layouts/templates/cpdoinspectionreport.html'));
            $html = str_replace('{{nameofproject}}',$inspectiondata->nameofproject, $html);
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
            $html = str_replace('{{location}}',$inspectiondata->cdp_address, $html);
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
                   $gettopdata = $this->_cpdodevelopmentapp->checkTransactionexist($inspectiondata->cafid,$inspectiondata->top_transaction_type_id); 
                   if(count($gettopdata) > 0){
                     $ordata = $this->_cpdodevelopmentapp->getORandORdate($gettopdata[0]->id);
                     if(count($ordata) > 0){
                     $inspectiondata->or_no = $ordata[0]->or_no; $inspectiondata->orissueddate = $ordata[0]->created_at;
                     $inspectiondata->caf_amount =  $ordata[0]->total_amount;
                     }
                   }
                }
            $html = str_replace('{{Amount}}',$inspectiondata->caf_amount, $html);
            $html = str_replace('{{ornumber}}',$inspectiondata->or_no, $html);
			if(isset($inspectiondata->orissueddate)){
            $html = str_replace('{{dateissued}}',date("m/d/Y", strtotime($inspectiondata->orissueddate)), $html);
            $filename="develop";
            $noteddata = $this->_cpdodevelopmentapp->getpositionbyid($inspectiondata->cir_approved_by);
            $html = str_replace('{{preparename}}',$noteddata->fullname, $html);
            $html = str_replace('{{prePosition}}',$noteddata->description, $html);

            $approvedata = $this->_cpdodevelopmentapp->getpositionbyid($inspectiondata->cir_noted_by);
            $html = str_replace('{{recomname}}',$approvedata->fullname, $html);
            $html = str_replace('{{recomPosition}}',$approvedata->description, $html);
            //$html = $html;
            //echo $html; exit;
            $preparedbyId = HrEmployee::where('id', $inspectiondata->cir_approved_by)->first();
            $notedId = HrEmployee::where('id', $inspectiondata->cir_noted_by)->first();
            // echo $preparedbyId->user_id; exit;
            $mpdf->WriteHTML($html);
           //$filename = str_replace(' ','', $applicantname);
            $orderfilename = $id.$filename."cpdoinspectionreport.pdf";
            $arrSign= $this->_commonmodel->isSignApply('planning_development_permit_inspect_order_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('planning_development_permit_inspect_order_noted_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

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
            if(File::exists($folder.$orderfilename)) { 
                File::delete($folder.$orderfilename);
            }
        }
        $mpdf->Output($orderfilename,"I");
    }
    }
     public function uploadDocument(Request $request){
        $id =  $request->input('id');
        $arrDtls = $this->_cpdodevelopmentapp->getEditDetails($id);
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
                $this->_cpdodevelopmentapp->updateinspectionData($id,$data);
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
		    $arrgetBrgyCode = array(""=>"Please Select");
        $arrterrin = config('constants.arrCpdoInspectionTerrain');
        $applicationid =""; $approvalid ="";  $inspectiondata->document_details ="";
        $loginusedid = \Auth::user()->id;
        $arrLocations = array();
        $penaltyarray = array();
        foreach ($this->_cpdodevelopmentapp->GetPenaltiesmaster() as $keyp => $valuep) {
          $penaltyarray[$valuep->id] = $valuep->name;
        }
        $inspectiondata->csd_id ="";   $inspectiondata->csd_id = ""; $inspectiondata->tfoc_id="";
        $orderarray = array('id'=>'','date'=>date('Y-m-d'),'transaction_no'=>'');
        $checkidexist = $this->_cpdodevelopmentapp->checkTransactionexist($request->input('id'));
        $orderpayment = (object)$orderarray;
         if(count($checkidexist)> 0){
            $orderpayment->transaction_no = $checkidexist[0]->transaction_no;
            $orderpayment->id = $checkidexist[0]->id;
        }
        if($request->input('id')>0 && $request->input('submit')==""){
             $data = $this->_cpdodevelopmentapp->GetapplicationRecord($request->input('id'));  
              $getsurchargesl = $this->_cpdodevelopmentapp->getCasheringIds($data->tfoc_id);  
              $issurcharge = $getsurchargesl->tfoc_surcharge_sl_id;
              $inspectiondata->caf_id =$request->input('id');
              $inspectiondata->cir_isapprove = "0";
              $checkinspectionisexist =  $this->_cpdodevelopmentapp->isexistinspection($request->input('id'));
              //print_r($checkinspectionisexist); exit;
              if(count($checkinspectionisexist)> 0){
                  $inspectiondata =  $this->_cpdodevelopmentapp->getinspectionorderforedit($request->input('id'));
                  $arrdocDtls = $this->generateDocumentListnew($inspectiondata->cir_upload_documents_json,$data->id);
                  if(isset($arrdocDtls)){
                      $inspectiondata->document_details = $arrdocDtls;
                  }
                  $approvaldata =  $this->_cpdodevelopmentapp->getapproveluserid($inspectiondata->cir_noted_by); 
                  $approvalid = $approvaldata->user_id; 
                  $arrLocations = $this->_cpdodevelopmentapp->GetGeoLocationbyid($inspectiondata->id); 
              }
				foreach ($this->_commonmodel->getBarangay($data->locationofproject)['data'] as $val) {
					$arrgetBrgyCode[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region;
				}
                 $inspectiondata->locationproject = $data->locationofproject;
                 $inspectiondata->nameofproject=$data->nameofproject;
                 $inspectiondata->cafid = $data->client_id;
                 $inspectiondata->csd_id = $data->csd_id; 
                 $inspectiondata->caf_amount ="";
                 $inspectiondata->or_no ="";
                 $inspectiondata->orissueddate ="";
                 $inspectiondata->client_telephone = $data->cdp_phone_no;
                 $inspectiondata->orptotal_amount = $data->cdp_total_amount + $data->penaltyamount;
                 $inspectiondata->orptotal = $data->cdp_total_amount;
                 $inspectiondata->penaltyamount = $data->penaltyamount;
                 $inspectiondata->tfoc_id = $data->tfoc_id;
                 if($data->top_transaction_type_id > 0){
                   $gettopdata = $this->_cpdodevelopmentapp->checkTransactionexist($data->id,$data->top_transaction_type_id); 
                   if(count($gettopdata) > 0){
                     $ordata = $this->_cpdodevelopmentapp->getORandORdate($gettopdata[0]->id);
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
                 $appamount = $this->_cpdodevelopmentapp->GetapplicationRecord($request->input('caf_id'));  
                 $penaltyper = $this->_cpdodevelopmentapp->Getpenaltypercen($request->input('cir_penalty'));
                 $penaltyamount = ($appamount->cdp_total_amount * $penaltyper->percentage) /100; 
                 $totalprpamount = $appamount->cdp_total_amount + $penaltyamount; 
             }    
            if($request->input('insid')>0){
                if(!empty($request->input('cir_isapprove'))){
                    $this->inspectiondata['cir_isapprove'] ='1';
                    $appuptdata = array('csd_id'=>'4','cs_id'=>'2');
                    if($totalprpamount > 0){
                      $appuptdata['penaltyamount'] = $penaltyamount;
                    }
                    $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdata);
                 }
                 if($totalprpamount > 0){
                      $appuptdata = array();
                      $appuptdata['penaltyamount'] = $penaltyamount;
                       $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdata);
                  }
                $this->_cpdodevelopmentapp->InspectionupdateData($request->input('insid'),$this->inspectiondata);
                 $success_msg = 'Inspection Order updated successfully.';
                 $lastinsertid = $request->input('insid');
                //$this->_cpdodevelopmentapp->updateData($appid,$appuptdata);
               }else{
                $this->inspectiondata['created_by']=\Auth::user()->id;
                $this->inspectiondata['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_cpdodevelopmentapp->InspectionaddData($this->inspectiondata);
                $appuptdata = array('csd_id'=>'3');
                if($totalprpamount > 0){
                      $appuptdata['penaltyamount'] = $penaltyamount;
                    }
                $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdata);
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
                            $this->_cpdodevelopmentapp->UpdateGeoLocationData($_POST['geoid'][$key],$locationarray);
                         }else{ $this->_cpdodevelopmentapp->AddGeoLocationData($locationarray); }
                     
                    }
            }
            return redirect()->route('cpdodevelopment.index')->with('success', __($success_msg));
        }
        return view('cpdo.development.inspectionreport',compact('arrgetBrgyCode','inspectiondata','arrterrin','arrOwners','hremployees','loginusedid','approvalid','arrLocations','penaltyarray','orderpayment','issurcharge'));
    }

    public function certification(Request $request){
        
        $certificatedata = (object)$this->certificate;
        $arrOwners = $this->arrOwners;
        $hremployees = $this->hremployees;
        $arrterrin = config('constants.arrCpdoInspectionTerrain');
        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        $applicationid =""; $preparedid =""; $recommendedid =""; $notedid ="";  $approvalid="";
        $loginusedid = \Auth::user()->id;
        $certificatedata->cc_approval_status ="";
       
        if($request->input('id')>0 && $request->input('submit')==""){
             $data = $this->_cpdodevelopmentapp->GetapplicationRecord($request->input('id'));  //CpdoApplicationForm::find($request->input('id'))
              $certificatedata->caf_id =$request->input('id');
              $checkinspectionisexist =  $this->_cpdodevelopmentapp->isexistcertificate($request->input('id'));
              if(count($checkinspectionisexist)> 0){
                 $certificatedata =  $this->_cpdodevelopmentapp->getcertificateforedit($request->input('id'));
                 $preparedata =  $this->_cpdodevelopmentapp->getapproveluserid($certificatedata->preparedby); 
                 $preparedid = $preparedata->user_id;
                 $recommenddata =  $this->_cpdodevelopmentapp->getapproveluserid($certificatedata->cc_recom_approval); 
                 $recommendedid = $recommenddata->user_id;
                 $noteddata =  $this->_cpdodevelopmentapp->getapproveluserid($certificatedata->cc_noted); 
                 $notedid = $noteddata->user_id;
                 $approvaldata =  $this->_cpdodevelopmentapp->getapproveluserid($certificatedata->cc_approved); 
                 $approvalid = $approvaldata->user_id;
              }
              $barngayName = "";
              $barngayAddress = $this->_Barangay->getBarangayname($data->project_barangay_id); 
                if(!empty($barngayAddress)){
                   $barngayName = $barngayAddress->brgy_name;
                }
                 $certificatedata->locationproject = $barngayName; 
                 $certificatedata->telephone=$data->cdp_phone_no;
                 $certificatedata->cafid = $data->client_id;
                 $certificatedata->caf_amount =$data->cdp_total_amount;
                 $certificatedata->nameofproject =$data->nameofproject;
                 $certificatedata->locationofproject =$barngayName;
                 $certificatedata->transaction_no =$data->cdp_control_no;
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
                $ext_data=$this->_cpdodevelopmentapp->findCertificateDataById($request->input('certid'));
                if(!empty($request->input('cc_recom_status'))){
                    $this->certificatedata['cc_recom_status'] ='1';
                    $this->certificatedata['cc_recom_approval_date'] =date('Y-m-d');
                    $appuptdataapp = array('csd_id'=>'7'); 
                    $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdataapp);
                 }
                 if(!empty($request->input('cc_notes_status'))){
                    $this->certificatedata['cc_notes_status'] ='1';
                    $this->certificatedata['cc_noted_date'] =date('Y-m-d');
                    $appuptdataapp = array('csd_id'=>'6'); 
                    $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdataapp);
                 }
                 if(!empty($request->input('cc_approval_status'))){
                    $this->certificatedata['cc_approval_status'] ='1';
                    $this->certificatedata['cc_approved_date'] =date('Y-m-d');
                    $appuptdataapp = array('csd_id'=>'8'); 
                    $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdataapp);
                 }
                $this->_cpdodevelopmentapp->CertificateupdateData($request->input('certid'),$this->certificatedata);
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
                $lastinsertid = $this->_cpdodevelopmentapp->CertificateaddData($this->certificatedata);
                $appuptdata = array('csd_id'=>'5','cs_id'=>'3');
                $this->_cpdodevelopmentapp->updateData($request->input('caf_id'),$appuptdata);
                $success_msg = 'Certificate Order added successfully.';
                $cert_id = $lastinsertid;
            }
            if($request->input('cc_approval_status') == 1 && $apv_send == 1){
                $orderdata = $this->_cpdodevelopmentapp->findCertificateDataById($cert_id);
                $smsTemplate=SmsTemplate::where('id',14)->where('is_active',1)->first();
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
            
            return redirect()->route('cpdodevelopment.index')->with('success', __($success_msg));
        }
        // echo $loginusedid; print_r($certificatedata); exit;
        return view('cpdo.development.certificate',compact('certificatedata','arrterrin','arrOwners','hremployees','arrCpdoOverland','loginusedid','preparedid','recommendedid','notedid','approvalid'));
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
     $getclearance = $this->_cpdodevelopmentapp->getcleranceid($tfocid);
     if(isset($getclearance->id)){
     $optiondata = $this->_cpdodevelopmentapp->paymentlines($getclearance->id);
     }
     //echo"<pre>"; print_r($optiondata); exit;
            
      foreach ($optiondata as $key => $value) {
        $html .='<div class="row" style="padding-top:5px;">
               <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"> <div class="form-icon-user"><input class="form-check-input code"  id="line'.$value->id.'" name="lines[]" type="hidden" value="'.$value->id.'" fdprocessedid="3w2mkr"><input class="form-check-input code linecheckbox"  idval="'.$value->id.'" name="checkbox'.$value->id.'" type="checkbox" value="1" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"> <div class="form-icon-user"><input class="form-control disabled-field"  id="paymentline'.$value->id.'" name="paymentline[]" type="text" value="'.$value->cdpcl_description.'" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"> <div class="form-icon-user"><input class="form-control number disabled-field" idval="'.$value->id.'" id="number'.$value->id.'" name="numberpay[]" type="text" value="" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"> <div class="form-icon-user"><input class="form-control disabled-field"  id="type'.$value->id.'" name="type[]" type="text" value="'.$value->cis_code.'" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"> <div class="form-icon-user"><input class="form-control amount disabled-field"  id="amount'.$value->id.'" name="amount[]" type="text" value="'.number_format($value->cdpcl_amount,2).'" fdprocessedid="3w2mkr"><input class="form-control disabled-field"  id="fixedamount'.$value->id.'" name="fixedamount[]" type="hidden" value="'.number_format($value->cdpcl_amount,2).'" fdprocessedid="3w2mkr"></div></div></div>
               </div>';

      }
      echo $html;
   }

   public function getpaymentlinedit(Request $request){
	   
     $optiondata = $paymentlinedata  = $this->_cpdodevelopmentapp->getPaymentlinebyappid($request->input('appid'));;
    
     //echo"<pre>"; print_r($optiondata); exit;
        $html ="";    
      foreach ($optiondata as $key => $value) {
        $checked = ($value->cdppl_checkbox ==1) ?'checked':'';
        $html .='<div class="row" style="padding-top:5px;">
               <div class="col-lg-1 col-md-1 col-sm-1"><div class="form-group"> <div class="form-icon-user"><input class="form-check-input code"  id="line'.$value->cdppl_plineid.'" name="lines[]" type="hidden" value="'.$value->cdppl_plineid.'" fdprocessedid="3w2mkr">';
               if($checked){
                $html .='<input class="form-check-input code linecheckbox"  idval="'.$value->cdppl_plineid.'" name="checkbox'.$value->cdppl_plineid.'" checked="'.$checked.'" type="checkbox" value="1" fdprocessedid="3w2mkr">';
            }else{
                $html .='<input class="form-check-input code linecheckbox"  idval="'.$value->cdppl_plineid.'" name="checkbox'.$value->cdppl_plineid.'"  type="checkbox" value="1" fdprocessedid="3w2mkr">';
            }
               
               $html .='</div></div></div>
               <div class="col-lg-5 col-md-5 col-sm-5"><div class="form-group"> <div class="form-icon-user"><input class="form-control disabled-field"  id="paymentline'.$value->cdppl_plineid.'" name="paymentline[]" type="text" value="'.$value->cdppl_description.'" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"> <div class="form-icon-user"><input class="form-control number disabled-field" idval="'.$value->cdppl_plineid.'" id="number'.$value->cdppl_plineid.'" name="numberpay[]" type="text" value="'.$value->cdppl_number.'" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"> <div class="form-icon-user"><input class="form-control disabled-field"  id="type'.$value->cdppl_plineid.'" name="type[]" type="text" value="'.$value->cdppl_type.'" fdprocessedid="3w2mkr"></div></div></div>
               <div class="col-lg-2 col-md-2 col-sm-2"><div class="form-group"> <div class="form-icon-user"><input class="form-control amount disabled-field"  id="amount'.$value->cdppl_plineid.'" name="amount[]" type="text" value="'.number_format($value->cdppl_amount,2).'" fdprocessedid="3w2mkr"><input class="form-control disabled-field"  id="fixedamount'.$value->cdppl_plineid.'" name="fixedamount[]" type="hidden" value="'.number_format($value->cppdo_fixedamount,2).'" fdprocessedid="3w2mkr"></div></div></div>
               </div>';

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
        $data->cdp_total_amount = "";
        $arrgetBrgyCode = array(""=>"Please Select");
        $paymentlinedata = array();
        $checkrecords = $this->_cpdodevelopmentapp->GetcpdolatestApp();
        if(!empty($checkrecords)){
            $getServices = array(""=>"Please Select");
            foreach ($this->_cpdodevelopmentapp->getServicesbyid($checkrecords->tfoc_id) as $val) {
             $getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
            }
        }else{
            $getServices = $this->getServices;
        }
        $getServices = array(""=>"Select Service type","89"=>"Development Permit Fees");
        //print_r($checkrecords); exit;
        
        $orderarray = array('id'=>'','date'=>date('Y-m-d'),'transaction_no'=>'');
        $checkidexist = $this->_cpdodevelopmentapp->checkTransactionexist($request->input('id'));
        $orderpayment = (object)$orderarray;
         if(count($checkidexist)> 0){
            $orderpayment->transaction_no = $checkidexist[0]->transaction_no;
            $orderpayment->id = $checkidexist[0]->id;
         }
        $arrApptype = config('constants.arrCpdoStatus');
        foreach ($this->_cpdodevelopmentapp->getServiceTypearraydefault() as $k => $valat) {
          $apptype[$valat->id] = $valat->cm_module_desc;
        }
        $arrtenure = config('constants.arrCpdoProjectTenure');
        $arrCpdoOverland = config('constants.arrCpdoOverland'); 
        //$apptype = config('constants.arrCpdoAppModule'); 
        $applicationid ="";
        $data->is_approve ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CpdoDevelopmentPermit::find($request->input('id'));
            foreach ($this->_commonmodel->getBarangay($data->project_barangay_id)['data'] as $val) {
                $arrgetBrgyCode[$val->id]=$val->brgy_name;
            }
            $arrRequirements = $this->_cpdodevelopmentapp->getAppRequirementsData($request->input('id'));
        }
        //print_r($apptype); exit; 
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $cashdata = $this->_cpdodevelopmentapp->getCasheringIds('78');
            //print_r($this->data); exit;
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['agl_account_id'] = $cashdata->gl_account_id;
            $this->data['sl_id'] = $cashdata->sl_id;
            $this->data['cdp_total_amount'] = $request->input('cdp_total_amount');
            $materialbill = $request->input('caf_amount');
            if($request->input('caf_excempted') =='1')
            {
              $this->data['caf_total_amount'] = $request->input('totalamount');
            }else{
                // $getclearance = $this->_cpdodevelopmentapp->getcleranceid($request->input('cm_id'));
                // $getclerancelinedata = $this->_cpdodevelopmentapp->getclerancelinedata($getclearance->id,$request->input('caf_amount'));
                // //echo "<pre>"; print_r($getclerancelinedata); exit;
                // if($getclerancelinedata ==""){
                //     $getclerancelinedata = $this->_cpdodevelopmentapp->hetoverbyAmount
                //     ($getclearance->id,$request->input('caf_amount'));
                //       $payment1 = $materialbill - $getclerancelinedata->czccl_below;
                //       $payment2 = ($payment1 * 0.01)/10;
                //       $caf_total_amount = $getclerancelinedata->czccl_amount + $payment2;
                //       $this->data['caf_total_amount'] = $caf_total_amount;
                // }else{
                //   $this->data['caf_total_amount'] = $getclerancelinedata->czccl_amount;
                // }
            }
            
            if($request->input('id')>0){
                
                $this->_cpdodevelopmentapp->updateData($request->input('id'),$this->data);
                $success_msg = 'Cpdo Development Application updated successfully.';
                $currentappid = $request->input('id');
                $controlnumber = $_POST['cdp_control_no'];
               }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['cs_id'] = '1';
                $this->data['csd_id'] = '1';
                $this->data['cpdo_module_id'] = $request->input('tfoc_id');
                unset($this->data['cdp_control_no']);
                $lastinsertid = $this->_cpdodevelopmentapp->addData($this->data);
                $success_msg = 'Cpdo Development Application added successfully.';

                $controlno = str_pad($lastinsertid, 6, '0', STR_PAD_LEFT);
                $updatedata = array('cdp_control_no'=>$controlno);
                $this->_cpdodevelopmentapp->updateData($lastinsertid,$updatedata);
                $controlnumber = $controlno;
                $currentappid = $lastinsertid;
            }
            //$file_ary = $this->reArrayFiles($_FILES['reqfile']);
             //echo "<pre>"; print_r($_POST); exit;
             foreach ($_POST['lines'] as $kl => $vall) {
                    $appplinearr = array();
                    $srno = $_POST['lines'][$kl];
                    $appplinearr['cdp_id'] = $currentappid;
                    $appplinearr['cdppl_plineid'] = $_POST['lines'][$kl];
                    if(isset($_POST['checkbox'.$srno]))
                    $appplinearr['cdppl_checkbox'] = $_POST['checkbox'.$srno];

                    $appplinearr['cdppl_description'] = $_POST['paymentline'][$kl];
                    $appplinearr['cdppl_number'] = $_POST['numberpay'][$kl];
                    $appplinearr['cdppl_type'] = $_POST['type'][$kl];
                    $appplinearr['cdppl_amount'] = str_replace(",", "", $_POST['amount'][$kl]);
                    $appplinearr['cppdo_fixedamount'] = str_replace(",", "", $_POST['fixedamount'][$kl]);
                    $appplinearr['created_by']=\Auth::user()->id;
                    $appplinearr['created_at'] = date('Y-m-d H:i:s');
                    $checkexistpline = $this->_cpdodevelopmentapp->checkpaymentlineExist($request->input('id'),$_POST['lines'][$kl]);
                    if(count($checkexistpline) > 0){
                         $this->_cpdodevelopmentapp->updatepaymentlineData($checkexistpline[0]->id,$appplinearr);

                    }else{ $this->_cpdodevelopmentapp->AddPaymentLineData($appplinearr); }
             }
            //     echo "<pre>"; print_r($_FILES); exit;
            if(!empty($_POST['reqid']) > 0){
                foreach ($_POST['reqid'] as $key => $value) {
                    $appreqarr = array();
                    $appreqarr['cdp_id'] = $currentappid;
                    $appreqarr['tfoc_id'] = '89';
                    $appreqarr['cs_id'] = $_POST['tfoc_id'];
                    $appreqarr['req_id'] = $_POST['reqid'][$key];
                    $appreqarr['created_by']=\Auth::user()->id;
                    $appreqarr['created_at'] = date('Y-m-d H:i:s');
                    $checkexistreq = $this->_cpdodevelopmentapp->checkappRequiremenexist($request->input('id'),$_POST['reqid'][$key]);
                    if(count($checkexistreq) > 0){
                         $lastinsertid = $checkexistreq[0]->id; 
                    }else{ $lastinsertid = $this->_cpdodevelopmentapp->appRequirementaddData($appreqarr); }

                    
                    if(isset($request->file('reqfile')[$key])){  
                        if($image =$request->file('reqfile')[$key]) {
                            $reqid= $_POST['reqid'][$key];
                             $destinationPath =  public_path().'/uploads/cpdo_development/requirement';
                            if(!File::exists($destinationPath)){ 
                                File::makeDirectory($destinationPath, 0755, true, true);
                            }
                             $filename =  $controlnumber.'requirement'.$lastinsertid;  
                             $filename = str_replace(" ", "", $filename);   
                             $requirementpdf = $filename. "." . $image->extension();
                             $extension =$image->extension();
                             $image->move($destinationPath, $requirementpdf);
                             
                            // print_r($image); exit;
                             $filearray = array();
                             $filearray['cdpr_id'] = $lastinsertid;
                             $filearray['cdprl_name'] = $requirementpdf;
                             $filearray['cdprl_type'] = $extension;
                             //$filearray['cf_size'] = $_FILES['reqfile'.$key]['size'];
                             $filearray['cdprl_path'] = 'cpdo_development/requirement';
                             $filearray['created_by']=\Auth::user()->id;
                             $filearray['created_at'] = date('Y-m-d H:i:s');
                             //echo $_POST['cfid'][$key]; print_r($filearray); exit;
                             $checkimageexits = $this->_cpdodevelopmentapp->checkRequirementfileexist($reqid);
                             if(!empty($_POST['cfid'][$key])){ echo "here"; 
                                $this->_cpdodevelopmentapp->UpdateappFilesData($_POST['cfid'][$key],$filearray);
                             }else{ $this->_cpdodevelopmentapp->AddappFilesData($filearray);  }  
                             // echo $profileImage;
                        }
                    } 
                 }
            }
            return redirect()->route('cpdodevelopment.index')->with('success', __($success_msg));
        }
        return view('cpdo.development.create',compact('data','arrRequirements','arrOwners','getServices','arrApptype','arrtenure','arrCpdoOverland','apptype','orderpayment','requirement','paymentlinedata','arrgetBrgyCode'));
    }

    public function onlineStore(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $arrgetBrgyCode =array();
        $remortUrl = config('filesystems.disks.remote')['asset_url'];
        $arrRequirements = array();  $apptype = array(""=>"Select Service");
        $data->caf_total_amount ="";  
        $requirement =$this->requirement;
        $arrOwners = $this->arrOwners;
        $data->cdp_total_amount = "";
        $checkrecords = $this->_cpdodevelopmentapp->GetcpdolatestApp();
        if(!empty($checkrecords)){
            $getServices = array(""=>"Please Select");
            foreach ($this->_cpdodevelopmentapp->getServicesbyid($checkrecords->tfoc_id) as $val) {
             $getServices[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
            }
        }else{
            $getServices = $this->getServices;
        }
        $data = $this->_cpdodevelopmentapp->getDataForEdit($request->input('id'));
        foreach ($this->_commonmodel->getBarangay($data->project_barangay_id)['data'] as $val) {
                    $arrgetBrgyCode[$val->id]=$val->brgy_name;
                }
        $getServices = array(""=>"Select Service type","16"=>"Development Permit Fees");
        //print_r($checkrecords); exit;
        
        $orderarray = array('id'=>'','date'=>date('Y-m-d'),'transaction_no'=>'');
        $checkidexist = $this->_cpdodevelopmentapp->checkTransactionexist($request->input('id'));
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_cpdodevelopmentapp->findOnlineApp($request->input('id'));
            if(empty($data->cdp_control_no) || $data->cdp_control_no =='0'){
                $appno = str_pad($request->input('id'), 6, '0', STR_PAD_LEFT);
                $data->cdp_control_no='ONLINE-'.$appno;
            } 
            $arrRequirements = $this->_cpdodevelopmentapp->getAppRequirementsDataonline($data->frgn_cdp_id);
        }
        return view('cpdo.development.createOnline',compact('data','arrRequirements','arrOwners','getServices','apptype','remortUrl','arrgetBrgyCode','requirement'));
    }

    public function approve(Request $request,$id){
        $data=$this->_cpdodevelopmentapp->approve($request,$id);
        // dd($data);
        return response()->json([
            'data' =>$data,
            'success' => true
        ]);
    }

    public function decline(Request $request,$id){
        $data=$this->_cpdodevelopmentapp->decline($request,$id);
        // dd($data);
        return response()->json([
            'data' =>$data,
            'success' => true
        ]);
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
				// 'cdp_email_address'=>'required|regex:/(.+)@(.+)\.(.+)/i',
                'client_id'=>'required',
                'tfoc_idtype'=>'required',
                'tfoc_id' =>'required',
                'locationofproject' =>'required'
            ],[
				// "cdp_email_address.required" => "Email Address is required",
				"cdp_email_address.regex" => "Invalid Email Address",
                "locationofproject.required" => "Required Field",   				
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
				// 'cdp_email_address'=>'required|regex:/(.+)@(.+)\.(.+)/i',
                'cir_use_res'=>'required',
                'cit_id'=>'required',
                'cafid' =>'required'
            ],[
				// "cdp_email_address.required" => "Email Address is required",
				//"cdp_email_address.regex" => "Invalid Email Address",				
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
