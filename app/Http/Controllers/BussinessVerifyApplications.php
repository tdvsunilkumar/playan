<?php

namespace App\Http\Controllers;
use App\Models\BploBusiness;
use App\Models\CtoCashier;
use App\Models\SmsSetting;
use App\Models\SmsServerSetting;
use App\Models\SmsOutbox;
use App\Models\CronJob;
use App\Models\Bplo\BploBusinessEndorsement;
use App\Models\Bplo\BploEndorsingDept;
use App\Models\Engneering\CtoTfoc;
use App\Models\BploAssessmentCalculationCommon;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SmsTemplate;
use Illuminate\Http\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentSMSNotificationInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use DB;
use Session;
class BussinessVerifyApplications extends Controller
{
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    private $slugs;
    public $status = [
        '0' => 'Incomplete',
        '1' => 'Incomplete',
        '2' => 'Completed',
        '3' => 'Declined'
    ];
    public $psicclassess = array(""=>"Select Nature of Business");
    public $finalAssesmentArr=array();
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository, Carbon $carbon) 
    {
        date_default_timezone_set('Asia/Manila');
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->_BploBusiness = new BploBusiness();
        $this->_CtoCashier = new CtoCashier();
        $this->_BploBusinessEndorsement = new BploBusinessEndorsement();
        $this->_BploEndorsingDept = new BploEndorsingDept();
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->_commonmodel = new CommonModelmaster();
        $this->carbon = $carbon;
        $this->slugs = 'business-permit/application';
    } 

    public function viewapp(Request $request){
        $this->is_permitted($this->slugs, 'update');
        $this->is_permitted($this->slugs, 'create');
        $data = $this->_BploBusiness->getEditDetails($request->id);
        $psic_classess = $this->_BploBusiness->getBussinessClasses($request->id);
        foreach ($psic_classess as $key => $value) {
            $this->psicclassess[$value->id] = $value->subclass_description;
        }
        $psicclassess = $this->psicclassess;
        $ststuscode="";
        $data->document_details = $this->generateDocumentList($request->id,$ststuscode); 
        //echo "<pre>"; print_r($this->psicclassess); exit;
        $arrReq =""; 
        $health_status="";
        $env_status="";
        $plan_status="";
        $fire_status="";
        $busn_id=$request->id;
        $bplo_busn_info=$this->_BploBusiness->findBploBusnById($busn_id);
        $cto_cashier= $this->_CtoCashier->getLastPayment($busn_id,$data->busn_tax_year);
        $sunmary_url=url("/business-permit/application/print-summary/".$request->id);
        $req_fire_protection = $this->_BploBusiness->getBploDocReqByDept(1);
        $arrayFireProtReqDocData = json_decode($req_fire_protection->requirement_json, true);
        $arrFireReqDoc=array();
        if(!empty($arrayFireProtReqDocData))
        {
            $fire_end_dept=$this->_BploBusiness->getBploEndsByBusnId($busn_id,$bplo_busn_info->busn_tax_year,1);
            if(!empty($fire_end_dept)){
                $fire_status=$this->status[$fire_end_dept->bend_status];
            }
                foreach($arrayFireProtReqDocData as $i=>$item){
                    if($item['is_required'] == 1){
                        $req= ($item['is_required'] == 1) ? "Required" : "NO";
                        $pre_doc = $this->_BploBusiness->getUplodedDocByReqId($busn_id,$bplo_busn_info->busn_tax_year,1);
                        if ($pre_doc) {
                            $docJson = json_decode($pre_doc->documetary_req_json, true);
                            $filteredArray = collect($docJson)->where('requirement_id', $item['requirement_id']);
                            if ($filteredArray->isNotEmpty()) {
                                $filename = $filteredArray->first()['filename'];
                                $attachment='<a href="'.asset("uploads/document_requirement/" . $filename).'" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                                <i class="ti-download"></i>
                                        </a>';
                                        $avl="<div class='action-btn bg-success ms-2'>
                                        <i class='ti-check'></i>                   
                                </div>";
                            } else {
                                $attachment="";
                                $avl="<div class='action-btn bg-danger ms-2'>
                                        <i class='ti-close'></i>                   
                                </div>";
                            }
                        } else {
                            $attachment="";
                            $avl="<div class='action-btn bg-danger ms-2'>
                                            <i class='ti-close'></i>                   
                                    </div>";
                        }
                        $arrFireReqDoc[$i]['requirement_name']=$item['requirement_name'];
                        $arrFireReqDoc[$i]['is_required']=$avl;
                        $arrFireReqDoc[$i]['file']=$attachment;
                    }    
                }
        }    

        $req_plan = $this->_BploBusiness->getBploDocReqByDept(2);
        $arrayPlanDocData = json_decode($req_plan->requirement_json, true);
        $arrPlanReqDoc=array();
        if(!empty($arrayPlanDocData))
        {
            $plan_end_dept=$this->_BploBusiness->getBploEndsByBusnId($busn_id,$bplo_busn_info->busn_tax_year,2);
            if(!empty($plan_end_dept)){
                $plan_status=$this->status[$plan_end_dept->bend_status];
            }
                foreach($arrayPlanDocData as $i=>$item){
                    if($item['is_required'] == 1){
                        $req= ($item['is_required'] == 1) ? "Required" : "NO";
                        $pre_doc = $this->_BploBusiness->getUplodedDocByReqId($busn_id,$bplo_busn_info->busn_tax_year,2);
                        if ($pre_doc) {
                            $docJson = json_decode($pre_doc->documetary_req_json, true);
                            $filteredArray = collect($docJson)->where('requirement_id', $item['requirement_id']);
                            if ($filteredArray->isNotEmpty()) {
                                $filename = $filteredArray->first()['filename'];
                                $attachment='<a href="'.asset("uploads/document_requirement/" . $filename).'" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                                <i class="ti-download"></i>
                                        </a>';
                                        $avl="<div class='action-btn bg-success ms-2'>
                                        <i class='ti-check'></i>                   
                                </div>";
                            } else {
                                $attachment="";
                                $avl="<div class='action-btn bg-danger ms-2'>
                                        <i class='ti-close'></i>                   
                                </div>";
                            }
                        } else {
                            $attachment="";
                            $avl="<div class='action-btn bg-danger ms-2'>
                                        <i class='ti-close'></i>                   
                                </div>";
                        }
                        $arrPlanReqDoc[$i]['requirement_name']=$item['requirement_name'];
                        $arrPlanReqDoc[$i]['is_required']=$avl;
                        $arrPlanReqDoc[$i]['file']=$attachment;
                    }    
                }
            
        }

        $req_env = $this->_BploBusiness->getBploDocReqByDept(4);
        $arrayEnvDocData = json_decode($req_env->requirement_json, true);
        $arrEnvReqDoc=array();
        if(!empty($arrayEnvDocData))
        {
            $env_end_dept=$this->_BploBusiness->getBploEndsByBusnId($busn_id,$bplo_busn_info->busn_tax_year,4);
            if(!empty($env_end_dept)){
                $env_status=$this->status[$env_end_dept->bend_status];
            }
            foreach($arrayEnvDocData as $i=>$item){
                if($item['is_required'] == 1){
                    $req= ($item['is_required'] == 1) ? "Required" : "NO";
                    $pre_doc = $this->_BploBusiness->getUplodedDocByReqId($busn_id,$bplo_busn_info->busn_tax_year,4);
                    if ($pre_doc) {
                        $docJson = json_decode($pre_doc->documetary_req_json, true);
                        $filteredArray = collect($docJson)->where('requirement_id', $item['requirement_id']);
                        if ($filteredArray->isNotEmpty()) {
                            $filename = $filteredArray->first()['filename'];
                            $attachment='<a href="'.asset("uploads/document_requirement/" . $filename).'" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                            <i class="ti-download"></i>
                                    </a>';
                                    $avl="<div class='action-btn bg-success ms-2'>
                                    <i class='ti-check'></i>                   
                            </div>";
                        } else {
                            $attachment="";
                            $avl="<div class='action-btn bg-danger ms-2'>
                                    <i class='ti-close'></i>                   
                            </div>";
                        }
                    } else {
                        $attachment="";
                        $avl="<div class='action-btn bg-danger ms-2'>
                        <i class='ti-close'></i>                   
                </div>";
                    }
                    $arrEnvReqDoc[$i]['requirement_name']=$item['requirement_name'];
                    $arrEnvReqDoc[$i]['is_required']=$avl;
                    $arrEnvReqDoc[$i]['file']=$attachment;
                }
            }
        }

        $req_health = $this->_BploBusiness->getBploDocReqByDept(3);
        $arrayHealthDocData = json_decode($req_health->requirement_json, true);
        $arrHealthReqDoc=array();
        if(!empty($arrayHealthDocData))
        {
            foreach($arrayHealthDocData as $i=>$item){
                $health_end_dept=$this->_BploBusiness->getBploEndsByBusnId($busn_id,$bplo_busn_info->busn_tax_year,3);
                if(!empty($health_end_dept)){
                    $health_status=$this->status[$health_end_dept->bend_status];
                }
                if($item['is_required'] == 1){
                    $req= ($item['is_required'] == 1) ? "Required" : "NO";
                    $pre_doc = $this->_BploBusiness->getUplodedDocByReqId($busn_id,$bplo_busn_info->busn_tax_year,3);
                    if ($pre_doc) {
                        $docJson = json_decode($pre_doc->documetary_req_json, true);
                        $filteredArray = collect($docJson)->where('requirement_id', $item['requirement_id']);
                        if ($filteredArray->isNotEmpty()) {
                            $filename = $filteredArray->first()['filename'];
                            $attachment='<a href="'.asset("uploads/document_requirement/" . $filename).'" class="action-btn bg-warning btn ms-05 btn-sm align-items-center" target="_blank" title="Download">
                                            <i class="ti-download"></i>
                                    </a>';
                                    $avl="<div class='action-btn bg-success ms-2'>
                                    <i class='ti-check'></i>                   
                            </div>";
                        } else {
                            $attachment="";
                            $avl="<div class='action-btn bg-danger ms-2'>
                                    <i class='ti-close'></i>                   
                            </div>";
                        }
                    } else {
                        $attachment="";
                        $avl="<div class='action-btn bg-danger ms-2'>
                                <i class='ti-close'></i>                   
                        </div>";
                    }
                    $arrHealthReqDoc[$i]['requirement_name']=$item['requirement_name'];
                    $arrHealthReqDoc[$i]['is_required']=$avl;
                    $arrHealthReqDoc[$i]['file']=$attachment;
                }
            }
        }
        return view('BploBusiness.view',compact('data','health_status','env_status','plan_status','fire_status','busn_id','cto_cashier','arrFireReqDoc','arrEnvReqDoc','arrHealthReqDoc','arrPlanReqDoc','arrReq','psicclassess','sunmary_url'));
    }   

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'update');
        $this->is_permitted($this->slugs, 'create');
        $data = $this->_BploBusiness->find($request->id);
        return response()->json($data);
    }

    public function getRequirements(Request $request){
         $psicid = $request->input('psicid');
         $bussinessid = $request->input('bussinessid');
         $apptype = $request->input('apptype');
         $classid = $this->_BploBusiness->getclassbypsicclass($psicid);
         $subclass_id =$classid->subclass_id;
         $getRequirements = $this->_BploBusiness->getRequirements($subclass_id,$bussinessid,$apptype);
        // print_r($getRequirements); exit;
          $htmloption ='<option value="">Select Requirement</option>';
          foreach ($getRequirements as $key => $value) {
            $htmloption .='<option subclassid="'.$psicid.'" brcode="'.$value->bplo_requirement_id.'" value="'.$value->id.'">'.$value->req_description.'</option>';
          }
      echo $htmloption;

    }
     public function uploadDocument(Request $request){
        $busn_id =  $request->input('busn_id');
        $subclass_id =  $request->input('subclass_id');
        $requirement_id =  $request->input('requirement');
        $brcode =  $request->input('brcode');
        $psicid =  $request->input('psicid');
        $appcode =  $request->input('appcode');
        $bend_status = "";
        $arrcheckReqexist = $this->_BploBusiness->checkExistpbsireqdoc($requirement_id,$busn_id,$psicid);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        if(count($arrcheckReqexist) > 0 ){
                $message="This requirement is already exist";
                $ESTATUS=1;
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/bplo_business_req_doc/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['busn_id'] = $busn_id;
                $arrData['busn_psic_id'] = $psicid;
                $arrData['subclass_id'] = $subclass_id;
                $arrData['attachment'] = $filename;
                $arrData['br_code'] = $brcode;
                $arrData['req_code'] = $requirement_id;
                $arrData['busreq_status'] = "1";
                $arrData['created_at'] = date('Y-m-d H:i:s');
                $arrData['created_by'] = \Auth::user()->creatorId();
                $this->_BploBusiness->add_req_doc($arrData);
            }
            $arrDocumentList = $this->generateDocumentList($busn_id,$bend_status);
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
     public function generateDocumentList($busn_id, $bend_status=''){
        $html = "";
        $documentdata= $this->_BploBusiness->getAllreqbybusnid($busn_id);
        if(count($documentdata) > 0){
            
            foreach($documentdata as $key=>$val){
                $html .= "<tr>
                    <td>".$val->req_description."</td>
                    <td>
                        <div class='action-btn bg-success ms-2'>
                            <a class='btn' href='".asset('uploads/bplo_business_req_doc').'/'.$val->attachment."' target='_blank'><i class='ti-download'></i></a>                        
                        </div>
                        <div class='action-btn bg-danger ms-2'>
                            <a href='#' class='mx-3 btn btn-sm deleteRequirefille ti-trash text-white text-white' rid='".$val->id."' eid='".$val->busn_psic_id."'></a>
                        </div>
                    </td>
                </tr>";
            }
        }
        return $html;
    }
     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $eid = $request->input('eid');
        $arrRequirements = $this->_BploBusiness->getRequirementsbyid($rid);
        if(count($arrRequirements) > 0){
            if($arrRequirements[0]->attachment){
                $path =  public_path().'/uploads/bplo_business_req_doc/'.$arrRequirements[0]->attachment;
                if(File::exists($path)) { 
                    unlink($path);

                }
               
                $this->_BploBusiness->deleteRequirementsbyid($rid);
                echo "deleted";
            }
        }
    }


    public function reload_requirments(Request $request, $busn_plan_id) 
    {   
        $this->is_permitted($this->slugs, 'read'); 
        return response()->json([
            'data' => $this->_BploBusiness->reload_requirments($busn_plan_id)
        ]);
    }

    public function DeclineAttachment(Request $request){
        $busnid = $request->input('appid'); 
        $details = array(
            'busn_app_status' => '7'
        );
        $bplo_business = $this->_BploBusiness->updateData($busnid, $details);
        Session::put('REMOTE_UPDATED_BUSINESS_TABLE',$busnid); // This for remote server
    }

    public function ActivateAttachment(Request $request){
        $busnid = $request->input('appid'); 
        $details = array(
            'busn_app_status' => '1'
        );
        $bplo_business = $this->_BploBusiness->updateData($busnid, $details);
    }


    public function changestatus(Request $request)
    {   
        $this->is_permitted($this->slugs, 'update');
        $this->is_permitted($this->slugs, 'create');
        $timestamp = $this->carbon::now();
        $year=date("Y");
        $arrBuss = $this->_BploBusiness->getBussClientDetails($request->busn_id);
        $details = array(
            'busn_app_status' => 2,
            'updated_at' => $timestamp,
            'busn_dept_involved'=>'4',
            'updated_by' => Auth::user()->id
        );
        if(isset($arrBuss)){
            if(empty($arrBuss->busns_id_no)){
                $number = str_pad(($arrBuss->totalApproved+1), 5, '0', STR_PAD_LEFT);
            }else{
                $number = str_pad($arrBuss->busns_id, 5, '0', STR_PAD_LEFT);
            }
            $initial="";
            if(!empty($arrBuss->rpo_custom_last_name)){
                $initial = ucfirst($arrBuss->rpo_custom_last_name[0]);
            }
            $details['busns_id'] = $number;
            $details['busns_id_no'] = $initial.'-'.$arrBuss->loc_local_code.'-'.$number;
            $details['busn_id_initial'] = $initial;
            $details['loc_local_id'] = $arrBuss->loc_local_code;
            $this->_BploBusinessEndorsement->updateClient($arrBuss->client_id,array('is_fire_safety'=>1));
        }
        $bplo_business = $this->_BploBusiness->updateData($request->busn_id, $details);
        $remortServer = DB::connection('remort_server');
        $remortServer->table('bplo_business')->where('frgn_busn_id',$request->busn_id)->update($details);//to update the changes in taxpayer bplo business
        if($details['busn_app_status']  == 2){
            $l_bplo_business = $this->_BploBusiness->findForUpdateHistory($request->busn_id);
            foreach($l_bplo_business as $key=>$val){
                $details[$key] = $l_bplo_business->$key;
            }
            $app_code=$details['app_code'];
            $bplo_busn_history = $this->_BploBusiness->BploBusinessHistoryByBusnId($request->busn_id,$year,$app_code);  
                if(count($bplo_busn_history) == 0){
                    $newHistory=array(
                        'busn_id' => $request->busn_id,
                        );
                    $details = array_merge($details, $newHistory);                
                    $this->_BploBusiness->addBploBusinessHistory($details);
                }  
                else{
                    $bplo_business = $this->_BploBusiness->updateBploHistoryData($bplo_busn_history[0]->id, $details);               
                }
                $bplo_business_psic = $this->_BploBusiness->reload_busn_psic_by_Busn_id($request->busn_id);   
                $ext_busn_psic_id=array();
                    foreach($bplo_business_psic as $key=>$item)
                    {
                        $psicRowAttributes = get_object_vars($item);
                        unset($psicRowAttributes['id']);
                        $psicRowAttributes['busn_psic_id'] = $item->id;
                        $psicRowAttributes['busp_tax_year']=$year;
                        $psicRowAttributes['app_code']=$app_code;
                        $bplo_busn_psic_history = $this->_BploBusiness->BploBusnPsicHistoryByPsic($item->id,$year,$app_code);   
                        if(empty($bplo_busn_psic_history)){
                            DB::table('bplo_business_psic_history')->insert($psicRowAttributes);
                            $l_busn_psic_id=DB::getPdo()->lastInsertId();
                        }else{
                            DB::table('bplo_business_psic_history')->where('id',$bplo_busn_psic_history->id)->update($psicRowAttributes);
                            $l_busn_psic_id=$bplo_busn_psic_history->id;
                        }  
                    $ext_busn_psic_id[$key]=$l_busn_psic_id;
                    }
                DB::table('bplo_business_psic_history')->where('busn_id',$request->busn_id)->where('app_code',$app_code)->where('busp_tax_year',$year)->whereNotIn('id',$ext_busn_psic_id)->delete();    
                $bplo_business_psic_req = $this->_BploBusiness->reload_busn_req_doc($request->busn_id);   
                $ext_busn_psic_req_id=array();
                        foreach($bplo_business_psic_req as $key=>$item)
                        {
                            $psicReqRowAttributes = get_object_vars($item);
                            unset($psicReqRowAttributes['id']);
                            unset($psicReqRowAttributes['app_type_id']);
                            $psicReqRowAttributes['busn_psic_req_id'] = $item->id;
                            $psicReqRowAttributes['busreq_year']=$year;
                            $psicReqRowAttributes['app_type_id']=$app_code;
                            $bplo_busn_psic_req_history = $this->_BploBusiness->BploBusnPsicReqHistoryByPsicReq($item->id,$year,$app_code);   
                            if(empty($bplo_busn_psic_req_history)){
                                DB::table('bplo_business_psic_req_history')->insert($psicReqRowAttributes);
                                $l_busn_psic_req_id=DB::getPdo()->lastInsertId();
                            }else{
                                DB::table('bplo_business_psic_req_history')->where('id',$bplo_busn_psic_req_history->id)->update($psicReqRowAttributes);
                                $l_busn_psic_req_id=$bplo_busn_psic_req_history->id;
                            }  
                        $ext_busn_psic_req_id[$key]=$l_busn_psic_req_id;
                        }       
                DB::table('bplo_business_psic_req_history')->where('busn_id',$request->busn_id)->where('app_type_id',$app_code)->where('busreq_year',$year)->whereNotIn('id',$ext_busn_psic_req_id)->delete();
                
                $bplo_business_measure_pax = $this->_BploBusiness->reload_busn_measure_pax($request->busn_id);   
                $ext_busn_measure_pax_id=array();
                        foreach($bplo_business_measure_pax as $key=>$item)
                        {
                            $measurePaxRowAttributes = get_object_vars($item);
                            unset($measurePaxRowAttributes['id']);
                            $measurePaxRowAttributes['buspx_id'] = $item->id;
                            $measurePaxRowAttributes['buspx_year']=$year;
                            $measurePaxRowAttributes['app_code']=$app_code;
                            $bplo_busn_measure_pax_history = $this->_BploBusiness->BploBusnMeasurePaxHistoryByBuspx($item->id,$year,$app_code);   
                            if(empty($bplo_busn_measure_pax_history)){
                                DB::table('bplo_business_measure_pax_history')->insert($measurePaxRowAttributes);
                                $l_busn_measure_pax_id=DB::getPdo()->lastInsertId();
                            }else{
                                DB::table('bplo_business_measure_pax_history')->where('id',$bplo_busn_measure_pax_history->id)->update($measurePaxRowAttributes);
                                $l_busn_measure_pax_id=$bplo_busn_measure_pax_history->id;
                            }  
                        $ext_busn_measure_pax_id[$key]=$l_busn_measure_pax_id;
                        }       
                DB::table('bplo_business_measure_pax_history')->where('busn_id',$request->busn_id)->where('app_code',$app_code)->where('buspx_year',$year)->whereNotIn('id',$ext_busn_measure_pax_id)->delete();   
        }
        $data = $this->_BploEndorsingDept->getactivelist();

        $arrLoc = $this->_commonmodel->bploLocalityDetails();
        $isNational=0;
        if(isset($arrLoc)){
            $isNational=($arrLoc->asment_id)==2?1:0;
        }

      
        foreach ($data as $row){
            $ctotfoc = CtoTfoc::where('id',$row->tfoc_id)->select('tfoc_amount')->first(); 
            $amount=0;
            if(isset($ctotfoc)){
                $amount = $ctotfoc->tfoc_amount;
                if($isNational && $row->id==1){
                    $amount=0;
                }
            }
           $detail = array(
                'busn_id' => $request->busn_id,
                'app_type_id' => $request->app_code,
                'payment_mode'=> $request->pm_id,
                'bend_year'=> $year,
                'endorsing_dept_id' => $row->id ,
                'force_mark_complete' => $row->force_mark_complete ,
                'tfoc_id'=>(int)$row->tfoc_id,
                'tfoc_amount' => $amount,
                'bend_assessment_type'=>'0',
                'bend_status' => '0',
                'updated_at' => $timestamp,
                'updated_by' => Auth::user()->id,
            );
            
            $isExist = $this->_BploBusinessEndorsement->checkExistEndrosMent($request->busn_id,$year,$row->id);
            if(isset($isExist)){
                $this->_BploBusinessEndorsement->updateData($isExist->id,$detail);
            }else{
                $detail['created_by'] = \Auth::user()->creatorId();
                $detail['created_at'] = date('Y-m-d H:i:s');
                $this->_BploBusinessEndorsement->create($detail);
            }
        }

        $this->_assessmentCalculationCommon->calculateAssessmentDetails($request->busn_id,$request->app_code,$request->pm_id,'saveData',date("Y"));
        //$this->_assessmentCalculationCommon->updateDelinquencyDetails($request->busn_id,$request->app_code,date("Y"));
        session()->flash('success', 'Updated successfully');
        $smsTemplate=SmsTemplate::where('group_id',2)->where('module_id',1)->where('action_id',2)->where('type_id',1)->where('is_active',1)->first();
        if(!empty($smsTemplate))
        {
            $receipient=$arrBuss->p_mobile_no;
            $msg=$smsTemplate->template;
            $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
            $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
            $msg = str_replace('<DATE>', date('d/m/Y', strtotime($arrBuss->application_date)),$msg);
            $this->send($msg, $receipient);
        }
        return response()->json(['ESTATUS'  => '1']);

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
                'created_by' => Auth::user()->id
            );
            $message = $this->componentSMSNotificationRepository->create($details);
           
                //$this->sendSms($receipient, $message);
                $this->componentSMSNotificationRepository->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }
   

}
