<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BfpCertificate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Models\BploBusiness;
use Carbon\Carbon;
use App\Models\HrEmployee; 
class BfpCertificateController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $yeararr = array(""=>"Select Year");
    public $arrbfpapplication = array();
    public $employee = array(""=>"Please Select");
    public $arrYears = array(""=>"Select Year");
    private $slugs;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness();
		$this->_bfpcertificate = new BfpCertificate();
        $this->_hrEmployee= new HrEmployee();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','bend_id'=>'','bff_id'=>'','bfpas_id'=>'','bio_id'=>'','client_id'=>'','bgy_id'=>'','busn_id'=>'','bfpcert_year'=>'','bfpcert_no'=>'','bfpcert_date'=>'','bfpcert_type'=>'2','bfpcert_date_issue'=>'','bfpcert_date_expired'=>'','bfpcert_remarks'=>'','bfpcert_approved_recommending'=>'','bfpcert_approved_recommending_position'=>'','bfpcert_approved_recommending_date'=>'','bfpcert_approved'=>'','bfpcert_approved_position'=>'','bfpcert_approved_date'=>'','recommending_status'=>'0','approved_status'=>'0','endorsing_dept_id'=>'','orno'=>'','ordate'=>'','oramount'=>'','inspection_date'=>'','bio_inspection_no'=>'','inspection_officer_id'=>'');
        $this->slugs = 'fire-protection/endorsement';
         $arrYrs = $this->_bfpcertificate->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bend_year] =$val->bend_year;
        }
        foreach ($this->_bfpcertificate->getBploBusinessId() as $val) {
            $this->arrbfpapplication[$val->id]=$val->busn_name;
        }

        foreach ($this->_bfpcertificate->getEmployee() as $val) {
           $this->employee[$val->id]=$val->fullname;
        } 
        
    }
    public function getEmployeeRecommendinDetails(Request $request){
         $id= $request->input('id');
         $data = $this->_bfpcertificate->employeeData($id);
         echo json_encode($data);
    }

    public function getEmployeeApprovedDetails(Request $request){
         $id= $request->input('id');
         $data = $this->_bfpcertificate->employeeData($id);
         echo json_encode($data);
    }
    public function getBusineClient(Request $request){
         $id= $request->input('pid');
         $bend_id= $request->input('bend_id');
         $year= $request->input('year');
         $data = $this->_bfpcertificate->clientData($id,$bend_id,$year);
         echo json_encode($data);
    }
    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
         $arrYears = $this->arrYears;
       
            return view('bfpcertificate.index',compact('arrYears'));
        
    }
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $bt_is_activeinactive);
        $this->_bfpcertificate->updateActiveInactive($id,$data);
    }  
    public function money_format($money)
    {
        return '₱ ' . number_format(floor(($money*100))/100, 3);
    }
    
    public function getList(Request $request){
        $data=$this->_bfpcertificate->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['srno']=$j;
            $arr[$i]['bfpcert_year']=$row->bfpcert_year;    
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['taxpayer']=$row->full_name;
            $arr[$i]['address']=$row->rpo_address_house_lot_no.','.$row->rpo_address_street_name.','.$row->rpo_address_subdivision.','.$row->brgy_name.','.$row->mun_desc.','.$row->prov_desc.','.$row->reg_region;
            $arr[$i]['permitno']=$row->busns_id_no;
            $arr[$i]['orno']=$row->orno;
            $arr[$i]['ordate']=$row->ordate;
            
            $arr[$i]['amount']=$this->money_format($row->oramount);
            $arr[$i]['bfpcert_date_issue']=date("M d, Y",strtotime($row->bfpcert_date_issue));
            $arr[$i]['bfpcert_date_expired']=date("M d, Y",strtotime($row->bfpcert_date_expired));
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');"";
            $print="";
            if($row->recommending_status == 1 && $row->approved_status == 1){
               $print= '<div class="action-btn bg-info ms-2">
                    <a title="Print Certificate"  data-title="Print Certificate" class="mx-3 btn print btn-sm  align-items-center digital-sign-btn" target="_blank" href="'.url('/bfpCertificatePrint?id='.(int)$row->id).'" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>';
            }
            
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpcertificate/store?busn_id='.$row->busn_id).'&bend_id='.$row->endorsing_dept_id.'&year='.$row->bfpcert_year.'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Fire Safety Inspection Certificate">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               
                    '.$print.'
                    '.$status.'
                </div>';

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
        $this->is_permitted($this->slugs, 'update');
        $busn_id =  $request->input('busn_id');
        // echo "<pre>"; print_r($busn_id); exit;
        $bend_id =  $request->input('bend_id');
        $year =  $request->input('year');
        // echo "<pre>"; print_r($bend_id); exit;
        $data = (object)$this->data;
        $bfpapplications =$this->arrbfpapplication;
        $employee =$this->employee;
        $current_user_login = \Auth::user()->id;
        $auth=$this->_hrEmployee->empIdByUserId($current_user_login);
        $assessmentform = array(); $inspectionorder = array();

        foreach ($this->_bfpcertificate->getBploBusinessId() as $vala) {
            $assessmentform[$vala->id]=$vala->busn_name;
        } 

        // foreach ($this->_bfpcertificate->InspectionOrder() as $value) {
        //     $inspectionorder[$value->id]=$value->ba_business_account_no;
        // } 
        if($request->input('bend_id')>0 && $request->input('submit')==""){
            // $data = BfpCertificate::find($busn_id,$bend_id);
            $recommending_user_id  = $this->_bfpcertificate->selectHRemployees($data->bfpcert_approved_recommending);
             $approved_user_id  = $this->_bfpcertificate->selectHRemployees($data->bfpcert_approved);
            // print_r($data);exit;
            
            $dataApplicationBusnDetails =$this->_bfpcertificate->getApplicationEdit($busn_id,$bend_id,$year);
            if($dataApplicationBusnDetails){
               $arrdocDtls = $this->generateDocumentListInspection($dataApplicationBusnDetails->bfpcert_document,$dataApplicationBusnDetails->bend_id);
            // echo "<pre>"; print_r($arrdocDtls); exit;
            }else{
                $arrdocDtls="";
            }
            if(isset($dataApplicationBusnDetails)){
                $data=$dataApplicationBusnDetails;
                $recommending_user_id  = $this->_bfpcertificate->selectHRemployees($data->bfpcert_approved_recommending);
                $approved_user_id  = $this->_bfpcertificate->selectHRemployees($data->bfpcert_approved);
               // echo $recommending_user_id;exit;
            }else{
            $recommending_user_id  =0;
            $approved_user_id  = 0;
             $getdatausersave = $this->_bfpcertificate->CheckFormdataExist('3',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->bfpcert_approved_recommending = $usersaved->bfpcert_approved_recommending;
                  $data->bfpcert_approved_recommending_position = $usersaved->bfpcert_approved_recommending_position;
                  $data->bfpcert_approved = $usersaved->bfpcert_approved;
                  $data->bfpcert_approved_position = $usersaved->bfpcert_approved_position;
               } 
   
           }
           
        }
        $recomend_send=1;
        $approve_send=1;
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            if($request->input('bfpcert_approved_recommending'))
                {
                    $this->data['bfpcert_approved_recommending_date']=$request->input('bfpcert_approved_recommending_date');
                }else{
                    $this->data['bfpcert_approved_recommending_date']="";
                }
                if($request->input('bfpcert_approved'))
                {
                    $this->data['bfpcert_approved_date']=$request->input('bfpcert_approved_date');
                    
                }else{
                    $this->data['bfpcert_approved_date']="";
                }
            $this->data['bfpcert_type']=2;
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $ext_app_data=$this->_bfpcertificate->findDataById($request->input('id'));
                $this->_bfpcertificate->updateData($request->input('id'),$this->data);
                $success_msg = 'BFP Certificate updated successfully.';
                if($ext_app_data->recommending_status == 1)
               {
                $recomend_send=0;
               }
               if($ext_app_data->approved_status == 1)
               {
                $approve_send=0;
               }
            }else{
                if(!$request->input('bfpcert_approved_recommending'))
                {
                    $this->data['bfpcert_approved_recommending_date']="";
                  }else{
                   $this->data['bfpcert_approved_recommending_date']=$request->input('bfpcert_approved_recommending_date');
                }
                if($request->input('bfpcert_approved'))
                {
                    $this->data['bfpcert_approved_date']=$request->input('bfpcert_approved_date');
                    
                }else{
                    $this->data['bfpcert_approved_date']="";
                }
                $this->data['bfpcert_type']=2;
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $this->_bfpcertificate->addData($this->data);
                $success_msg = 'BFP Certificate added successfully.';
                $user_savedata = array();
                $user_savedata['bfpcert_approved_recommending'] = $request->input('bfpcert_approved_recommending');
                $user_savedata['bfpcert_approved_recommending_position'] = $request->input('bfpcert_approved_recommending_position');
                $user_savedata['bfpcert_approved'] = $request->input('bfpcert_approved');
                $user_savedata['bfpcert_approved_position'] = $request->input('bfpcert_approved_position');
                $userlastdata = array();
                $userlastdata['form_id'] = 3;
                $userlastdata['user_id'] = \Auth::user()->id;
                $userlastdata['is_data'] = json_encode($user_savedata);
                $userlastdata['created_at'] = date('Y-m-d H:i:s');
                $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                $checkisexist = $this->_bfpcertificate->CheckFormdataExist('3',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_bfpcertificate->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_bfpcertificate->addusersaveData($userlastdata);
                }
            }

        // if($request->input('bfpcert_approved_recommending') == $auth->id && $request->input('recommending_status') == 1 && $recomend_send == 1)
        //     {
        //         $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
        //         $smsTemplate=SmsTemplate::where('group_id',10)->where('module_id',49)->where('action_id',8)->where('type_id',1)->where('is_active',1)->first();
        //         if(!empty($smsTemplate))
        //         {
        //             $receipient=$arrBuss->p_mobile_no;
        //             $msg=$smsTemplate->template;
        //             $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
        //             $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
        //             $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
        //             $this->send($msg, $receipient);
        //         }
        //     }
        if($request->input('bfpcert_approved') == $auth->id && $request->input('approved_status') == 1 && $approve_send == 1)
            {
                $arrBuss = $this->_BploBusiness->getBussClientDetails($busn_id);
                $smsTemplate=SmsTemplate::where('group_id',10)->where('module_id',49)->where('action_id',8)->where('type_id',1)->where('is_active',1)->first();
                if(!empty($smsTemplate))
                {
                    $receipient=$arrBuss->p_mobile_no;
                    $msg=$smsTemplate->template;
                    $msg = str_replace('<NAME>', $arrBuss->full_name,$msg);
                    $msg = str_replace('<BUSINESS_NAME>', $arrBuss->busn_name,$msg);
                    $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                    $this->send($msg, $receipient);
                }
            }
            return redirect('fire-protection/endorsement')->with('success', __($success_msg));
        }
        return view('bfpcertificate.create',compact('data','bfpapplications','assessmentform','inspectionorder','employee','current_user_login','approved_user_id','recommending_user_id','arrdocDtls'));
        
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
    public function uploadAttachment(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('year');
        $bbendo_id =  $request->input('bbendo_id');
        $arrEndrosment = $this->_bfpcertificate->getApplicationDetails($busn_id,$bbendo_id,$year);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/document_requirement/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
                $filename = "attachment_".time().'.'.$image->extension();
                $image->move($destinationPath, $filename);
                $arrData = array();
                $arrData['business_id'] = $busn_id;
                $arrData['filename'] = $filename;
                $finalJsone[] = $arrData;
                if(isset($arrEndrosment)){
                    $arrJson = json_decode($arrEndrosment->bfpcert_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['bfpcert_document'] = json_encode($finalJsone);
                $this->_bfpcertificate->updateApplication($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['bfpcert_document'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    
     public function deleteAttachment(Request $request){
        $rid = $request->input('rid');
        $busn_id = $request->input('id');
        $year = $request->input('year');
        $bbendo_id = $request->input('bbendo_id');
        $arrEndrosment = $this->_bfpcertificate->getApplicationDetails($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->bfpcert_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['bfpcert_document'] = json_encode($arrJson);
                    $this->_bfpcertificate->updateApplication($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }

    public function generateDocumentListInspection($arrJson,$bbendo_id){
        $html = "";
        
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $html .= "<tr>
                  <td>".$val['filename']." </td>
                  <td><a class='btn' href='".asset('uploads/document_requirement').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                   <td>
                        <div class='action-btn bg-danger ms-2'>
                            <a href='#' class='mx-3 btn btn-sm deleteEndrosmentInspections ti-trash text-white text-white ' rid='".$val['filename']."' bbendo_id='".$bbendo_id."'></a>
                        </div>
                    </td>
                </tr>";
            }
        }
        return $html;
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                'bfpcert_date_issue'=>'required', 
                // 'bfpcert_date_expired'=>'required'
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
    public function recommendingsataus(Request $request){
        $id= $request->input('id');
        $data=DB::table('bfp_certificates')
            ->where('id', $id)
            ->update(['recommending_status' =>$request->input('recommending_status'),'recommending_status' =>1,'bfpcert_approved_recommending_date'=> date('Y-m-d H:i:s')]);
            
        return response()->json(['success' => $data]);
    }
    public function approvedsataus(Request $request){
        $id= $request->input('id');
        $data=DB::table('bfp_certificates')
            ->where('id', $id)
            ->update(['approved_status' =>$request->input('approved_status'),'approved_status' =>1,'bfpcert_approved_date'=> date('Y-m-d H:i:s')]);
       return response()->json(['success' => $data]);
    }
    public function isPrinted(Request $request){
        $id= $request->input('id');
        $data=DB::table('bfp_certificates')
            ->where('id', $id)
            ->update(['is_printed' =>1]);
       return response()->json(['success' => $data]);
    }
    public function bfpCertificateRelease(Request $request){
        $id= $request->input('id');
        $data=DB::table('bfp_certificates')
            ->where('id', $id)
            ->update(['is_release' =>1,'release_date'=> date('Y-m-d H:i:s')]);
       return response()->json(['success' => $data]);
    }
    public function bfpCertificatePrint(Request $request){
            $id= $request->input('id');
            $ordate="";
            $fsic="";
            $client="";
            $RECOMMEND="";
            $APPROVED="";
            $rdescription="";
            $adescription="";
            $address="";
            $area="";
            $bfpcert_date_expired="";
            $bfpcert_approved_date="";
            $oramount="";
            $orno="";
            foreach ($this->_bfpcertificate->getEmployee() as $val) {
                if($val->suffix){
                  $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
                }
                else{
                    $this->employee[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
                }
            }
            $employee =$this->employee;
            foreach ($this->_bfpcertificate->getClient() as $valClient) {
                if($valClient->suffix){
                  $this->owner[$valClient->id]=$valClient->rpo_first_name.' '.$valClient->rpo_middle_name.' '.$valClient->rpo_custom_last_name.', '.$valClient->suffix;
                }
                else{
                    $this->owner[$valClient->id]=$valClient->rpo_first_name.' '.$valClient->rpo_middle_name.' '.$valClient->rpo_custom_last_name;
                }
            }
            $owner =$this->owner;
            $datas = $this->_bfpcertificate->getPrintDetails($id);
            // if(count($data)>0){
            //     $data = $data[0];
            // }
            
            foreach($datas as $data){
            $fsic=$data->bfpcert_no;
            // $address=$data->rpo_address_house_lot_no.','.$data->rpo_address_street_name.','.$data->rpo_address_subdivision.','.$data->brgy_name.','.$data->mun_desc.','.$data->prov_desc.','.$data->reg_region;
            $address=$data->brgy_name.', '.$data->mun_desc.', '.$data->prov_desc.', '.$data->reg_region;
            $area=$data->busn_bldg_total_floor_area;
            $bfpcert_date_expired2 = $data->bfpcert_date_expired;
            $bfpcert_date_expired= date('M d,Y', strtotime($bfpcert_date_expired2));

            $bfpcert_approved_date2 = $data->bfpcert_approved_date;
            $bfpcert_approved_date= date('M d,Y', strtotime($bfpcert_approved_date2));
            $rdescription = $data->rdescription;
            $adescription = $data->adescription;
            $orno = $data->orno;
            $oramount = $data->oramount;
            $ordate = $data->ordate;  
            $client = $owner[$data->client_id];
            $RECOMMEND = $employee[$data->bfpcert_approved_recommending];
            $APPROVED = $employee[$data->bfpcert_approved];
            $busn_name = $data->busn_name;
            $bfpcert_approved_recommending_position = $data->bfpcert_approved_recommending_position;
            $bfpcert_approved_position = $data->bfpcert_approved_position;
            }
            
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [214, 278]]);
            $mpdf->AddPage('p','','','','',10,10,10,4,10,10);
            $filename="";

            $html = file_get_contents(resource_path('views/layouts/templates/inspectioncertificateFireDynamic.html'));
            $ordateformart= date('M d,Y', strtotime($ordate));
            $logo = url('/assets/images/leftOrderLogo.png');
            $logoright = url('/assets/images/rightInspection.jpg');
            $checked = url('/assets/images/checkbox-checked.jpeg');
            $unchecked = url('/assets/images/checkbox-unchecked.jpg');
            $html = str_replace('{{busn_name}}',$busn_name, $html);
            $html = str_replace('{{bfpcert_approved_recommending_position}}',$bfpcert_approved_recommending_position, $html);
            $html = str_replace('{{bfpcert_approved_position}}',$bfpcert_approved_position, $html);
            $html = str_replace('{{checked}}',$checked, $html);
            $html = str_replace('{{unchecked}}',$unchecked, $html);
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{logoright}}',$logoright, $html);
            $html = str_replace('{{fsic}}',$fsic, $html);
            $html = str_replace('{{client}}',$client, $html);
            $html = str_replace('{{recommending}}',$RECOMMEND, $html);
            $html = str_replace('{{approved}}',$APPROVED, $html);
            $html = str_replace('{{rdescription}}',$rdescription, $html);
            $html = str_replace('{{adescription}}',$adescription, $html);
            $html = str_replace('{{address}}',$address, $html);
            $html = str_replace('{{area}}',$area, $html);
            if($bfpcert_date_expired=="Jan 01,1970"){
              $html = str_replace('{{bfpcert_date_expired}}',$bfpcert_date_expired2, $html);  
            }
            else{
                $html = str_replace('{{bfpcert_date_expired}}',$bfpcert_date_expired, $html);  
            }
            $html = str_replace('{{bfpcert_approved_date}}',$bfpcert_approved_date, $html);
            $html = str_replace('{{ordateformart}}',$ordateformart, $html);
            $html = str_replace('{{oramount}}',$oramount, $html);
            $html = str_replace('{{orno}}',$orno, $html);
            $mpdf->WriteHTML($html);

            $filename ='InspectionCertificate-'.$id.'.pdf';

            $arrSign= $this->_commonmodel->isSignApply('fire_protection_safety_inspection_certificate_recommended_by');
            $isSignVeified = isset($arrSign)?$arrSign->status:0;

            $arrCertified= $this->_commonmodel->isSignApply('fire_protection_safety_inspection_certificate_approved_by');
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
            $recommentdedid = $this->_commonmodel->getuseridbyempid($data->bfpcert_approved_recommending);
            $varifiedSignature = $this->_commonmodel->getuserSignature($recommentdedid->user_id);
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
            $approvedid = $this->_commonmodel->getuseridbyempid($data->bfpcert_approved);
            $certifiedSignature = $this->_commonmodel->getuserSignature($approvedid->user_id);
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
           
            //echo url('/uploads/digital_certificates/' . $filename);
    }
    
}
