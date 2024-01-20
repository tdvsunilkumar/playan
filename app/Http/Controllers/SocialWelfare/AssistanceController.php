<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\Assistance;
use App\Models\SocialWelfare\AssistanceType;
use App\Models\SocialWelfare\StatusTypeModel;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;
use PDF;
use File;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use App\Models\BploBusiness;
class AssistanceController extends Controller
{

    public $data = [];
    public $postdata = [];
    private $slugs;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon;
        $this->_BploBusiness = new BploBusiness(); 
        $this->_Assistance = new Assistance(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'wsat_id'=>'',
            'wswa_amount'=>'',
            'wswa_date_applied'=>Carbon::now(),
            'wsst_id'=>'',
            'head_cit_id'=>'',
            'wswa_remarks'=>'',
            'wswa_is_active'=>1,
            'wswa_social_worker'=>'',
        );  
        $this->slugs = 'social-welfare/assistance';
        $this->mayor = user_mayor()->fullname;
        $this->mayor_lastname = user_mayor()->lastname;
        $this->email = 'cswdopalayancity@yahoo.com';
        $this->tel = '(044)940-5210';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('SocialWelfare.Assistance.index');
    }

    public function store(Request $request){
        $this->data['wswa_social_worker_name']= \Auth::user()->hr_employee->fullname;
        $this->data['wswa_social_worker']= \Auth::user()->hr_employee->id;
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
        $data->slug = $this->slugs;
        $approveBtn = [];
        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_Assistance->find($request->input('id'));
            $data->wswa_social_worker_name = $data->socialWorker->fullname;
            $data->wswa_social_worker = $data->socialWorker->id;
            $approveBtn = approveButton($this->slugs, $data->wswa_approve_status + 1);
        }


        if($request->isMethod('post')!=""){
         
            if (isset($request['wsst_id'])) {
                $request['wsst_id'] = implode(',',$request['wsst_id']);
            }
            unset($this->data['wswa_social_worker_name']);
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['modified_by']=\Auth::user()->creatorId();
            $this->data['modified_date'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_Assistance->updateData($request->input('id'),$this->data);

                $success_msg = 'Updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_date'] = date('Y-m-d H:i:s');
                $this->data['wswa_is_active'] = 1;
                $request->id = $this->_Assistance->addData($this->data);
                $success_msg = 'Added successfully.';
            }
            
                // approving
                if ($request->input('button') === 'submit') {
                    $this->_Assistance->find($request->input('id'))->approve($request->approve_sequence);
                    $success_msg = 'Approved';
                    $arrData = $this->_Assistance->find($request->input('id'));
                    $smsTemplate=SmsTemplate::searchBySlug($this->slugs)->first();
                    if(!empty($smsTemplate) && $arrData->claimant->cit_mobile_no != null)
                    {
                        $receipient=$arrData->claimant->cit_mobile_no;
                        $msg=$smsTemplate->template;
                        $msg = str_replace('<NAME>', $arrData->claimant->cit_fullname,$msg);
                        $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
                        $msg = str_replace('<ASSISTANCE_TYPE>', $arrData->assistanceType->wsat_description,$msg);
                        $this->send($msg, $receipient);
                    }
                }
            $this->_Assistance->addRelation($request);
            if ((float)$request->wswa_amount >= (float)$this->_Assistance->assistance_amount_limit) {
                $this->_Assistance->addCaseStudy($request->id, $request->socialcase);
            }
            // return json_encode(
            //     [
            //         'ESTATUS'=>0,
            //         'msg'=>$success_msg,
            //         'data' => $data
            //     ]
            // );
            return redirect()->route('assistance.index')->with('success', __($success_msg));
        }

        $assistanceType =  $this->_Assistance->getAllAssistanceType();
        $assistanceStatus =  StatusTypeModel::where('wsst_is_active',1)->get()->chunk(3);
        $dependents = $this->_Assistance->dependentCount();
        $files = $this->_Assistance->fileCount();
        $data->dependent_count = $dependents;
        $data->file_count = $files;
        $educ = config('constants.citEducationalAttainment');
        $civilstat = config('constants.citCivilStatus');
        // link and text for print
        $prints = [
            'print-justification/' => 'Justification',
            'print-eligibility/' => 'Eligibility',
            'print-application/' => 'Application',
            'print-request-letter/' => 'Request Letter',
        ];
        // dd($data->wswa_amount <= $this->_Assistance->assistance_amount_limit);
        if (isset($this->_Assistance->assistance_amount_limit)) {
            if ($data->wswa_amount <= $this->_Assistance->assistance_amount_limit) {
                $prints = array_merge($prints, [
                    'print-case-study/' => 'Case Study',
                ]);
            }
        }
        $data->amount_limit = $this->_Assistance->assistance_amount_limit;
        return view('SocialWelfare.Assistance.create',compact('data', 'assistanceType', 'assistanceStatus','civilstat','educ','prints','approveBtn'));
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

    
    public function requestLetter(Request $request,$id)
    {
        $data = (object)[
            'wswa_id' => $id,
            'wswart_body' => ''
        ];
        $assistance = $this->_Assistance->find($id);
            // dd($assistance->request_letter);
        if ($assistance->request_letter) {
            $data = $assistance->request_letter;
        }
        if($request->isMethod('post')!=""){
            try {
                $msg = $this->_Assistance->sendRequestLetter($request);
            } catch (\Throwable $th) {
                $msg = $th;
            }
            return json_encode(
                [
                    'ESTATUS'=>0,
                    'msg'=>$msg,
                    'data' => $data
                ]
            );
        }
        return view('SocialWelfare.Assistance.request-letter',compact('data'));
        
    }

    public function letterValidation(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                "wswart_body" => "required",
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

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                "cit_id" => "required|int",
                "wsat_id" => "required|int",
                // 'wswa_amount' => 'required|regex:/^[0-9]{1,3}(,[0-9]{3})*\.[0-9]+$/',
                'wswa_amount' => 'required',
                'wswa_date_applied' => 'required|date',
                "head_cit_id" => "required|int",
                'wswa_social_worker' => 'required|int',
                'dependent.*.relation' => 'required',//not working lol
                'require.*.req_id' => 'required',//not working lol
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

    public function getRequirements(Request $request)
    {
        $id = $request->id;
        $data = AssistanceType::find($id)->getRequirements();
        echo json_encode($data);
    }

    public function getRequireList(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Citizen = AssistanceType::selectRequirement($q);
        foreach ($Citizen['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->req_description;
        }
        $data['data_cnt']=$Citizen['data_cnt'];
        echo json_encode($data);
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_Assistance->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-size="md" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage Assistance" data-title="Manage Assistance">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->wswa_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Assistance"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white" data-bs-toggle="tooltip" title="Restore Assistance"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $apporve = 'Draft';
            if ($row->wswa_approve_status === 1) {
                $apporve = 'Submitted';
            } elseif ($row->wswa_approve_status === 2) {
                $apporve = 'Approve';
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['fullname']=$row->claimant->cit_fullname;
            $arr[$i]['age']=$row->claimant->cit_age;
            $arr[$i]['address']=$row->claimant->cit_full_address;
            $arr[$i]['assistance']=$row->assistanceType->wsat_description;
            $arr[$i]['amount']=currency_format($row->wswa_amount);
            $arr[$i]['is_active']=($row->wswa_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['approver']='<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">'.$apporve.'</span>';
    
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
    public function selectEmployee(Request $request)
    {
        $q = $request->input('search');
        $data = [];
        $Citizen = Assistance::getEmployee($q);
        foreach ($Citizen['data'] as $key => $value) {
            $data['data'][$key]['id']=$value->id;
            $data['data'][$key]['text']=$value->fullname;
        }
        $data['data_cnt']=$Citizen['data_cnt'];
        echo json_encode($data);
    }
    

    public function ActiveInactive(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('wswa_is_active' => $is_activeinactive);
        $this->_Assistance->updateData($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Assistance ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }
    public function active(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $type = $request->input('type');
        $is_activeinactive = $request->input('is_activeinactive');
        $this->_Assistance->updateRelation($request);
    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' ".$type." ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        echo json_encode('success');
    }

    public function approve(Request $request)
    {
        $id = $request->input('id');
        $this->is_permitted($this->slugs, 'approve');
        $hr_id = Auth::user()->hr_employee->id;
        $data=array('wswa_approved_by' => $hr_id);
        $this->_Assistance->updateData($id,$data);

        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Approve Assistance:#".$id; 
        $this->_commonmodel->updateLog($logDetails);
        echo json_encode('success');
    }

    public function printJustification(Request $request, $id)
    {
        $data = Assistance::find($id);
        PDF::SetTitle('Justification for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 20, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h3 style="text-align:center">City of Palayan</h3>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 15);
        PDF::writeHTML('<h1 style="text-align:center">City Social Welfare and Development Office</h1>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('</br></br><h2 style="text-align:center">Justification</h2></br></br>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h2 style="text-align:center">Re:'.$data->claimant->cit_fullname.'</h2>',true, false, false, false, 'center');
        PDF::writeHTML('<h2 style="text-align:center">'.$data->claimant->age.' years old</h2>',true, false, false, false, 'center');
        PDF::writeHTML('<h2 style="text-align:center">Brgy '.$data->claimant->brgy->brgy_name.'</h2></br>',true, false, false, false, 'center');
        
        $remarks = preg_split('/\r\n|\r|\n/',$data->wswa_remarks);
        foreach ($remarks as $p) {
            PDF::writeHTML('<p style="text-indent: 30px">'.$p.'</p></br>',true, false, false, false, 'center');

        }
        $socialWorker = ($data->socialWorker) ? $data->socialWorker->fullname : '';
        PDF::writeHTML('<p style="text-align:right">Prepare By:</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:right">'.$socialWorker.'</p>',true, false, false, false, 'center');
        PDF::writeHTML('<b style="text-align:right">Social Worker</b></br></br></br>',true, false, false, false, 'center');

        $approver = ($data->approver) ? $data->approver->fullname : '';
        PDF::writeHTML('<p style="text-align:left">Noted By:</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:left">'.$approver.'</p>',true, false, false, false, 'center');
        PDF::writeHTML('<b style="text-align:left">CSWDO</b></br></br></br>',true, false, false, false, 'center');
        
        // PDF::Output('justification_'.$data->claimant->cit_fullname.'.pdf');
        $filename = 'justification_'.$data->claimant->cit_fullname.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_justification_noted_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('welfare_assistance_justification_prepared_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
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

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
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
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }

    public function printRequestLetter(Request $request, $id)
    {
        $data = Assistance::find($id);
        PDF::SetTitle('Request Letter for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 20, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::Image(public_path('/assets/images/department_logos/CSWD.png'),170, 20, 25, 25);

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h3 style="text-align:center">City of Palayan</h3>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h1 style="text-align:center">Social Welfare and Development Office</h1>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:center">Email Address: <u>'.$this->email.'</u></p>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:center">Telephone Number: '.$this->tel.'</p>',true, false, false, false, 'center');
        
        PDF::ln(7);
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<hr>',true, false, false, false, 'center');
        PDF::ln(3);

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<p style="text-align:right">'.Carbon::now()->format('F j, Y').'</p>',true, false, false, false, 'center');
        PDF::writeHTML('<h4>'.$this->mayor.'</h4>',true, false, false, false, 'center');
        PDF::writeHTML('<p>City Mayor</p>',true, false, false, false, 'center');
        PDF::writeHTML('<p>Palayan City</p></br>',true, false, false, false, 'center');
        
        PDF::writeHTMLCell(30, 0, '', '','',0, 0, false, false, 'center');
        PDF::writeHTMLCell(10, 0, '', '','Re:',0, 0, false, false, 'left');
        PDF::writeHTMLCell(0, 0, '', '','<b>'.$data->claimant->cit_fullname.'</b>',0, 1, false, false, 'left');

        PDF::writeHTMLCell(40, 0, '', '','',0, 0, false, false, 'center');
        PDF::writeHTMLCell(0, 0, '', '',$data->claimant->age.' years old',0, 1, false, false, 'center');

        PDF::writeHTMLCell(40, 0, '', '','',0, 0, false, false, 'center');
        PDF::writeHTMLCell(0, 0, '', '',$data->claimant->cit_full_address,0, 1, false, false, 'center');
        PDF::ln(10);
        
        PDF::writeHTML('<p>Dear Mayor '.$this->mayor_lastname.':</p></br>',true, false, false, false, 'center');
        if ($data->request_letter) {
            PDF::writeHTML('<p style="text-indent: 30px">'.nl2br($data->request_letter->wswart_body).'</p></br>',true, false, false, false, 'center');
        }

        $socialWorker = ($data->socialWorker) ? $data->socialWorker->fullname : '';
        PDF::writeHTMLCell(120, 0, '', '','',0, 0, false, false, 'center');
        PDF::writeHTMLCell(0, 0, '', '','Very truly yours,',0, 1, false, false, 'left');
        PDF::ln(10);

        PDF::writeHTMLCell(120, 0, '', '','',0, 0, false, false, 'center');
        PDF::writeHTMLCell(0, 0, '', '','<p style="text-align:right"><b>'.$socialWorker.'</b></p>',0, 1, false, false, 'center');

        PDF::writeHTMLCell(120, 0, '', '','',0, 0, false, false, 'center');
        PDF::writeHTMLCell(0, 0, '', '','<p style="text-align:right">City Social Welfare & Devt. Officer</p>',0, 1, false, false, 'center');


        // PDF::Output('request_letter_'.$data->claimant->cit_fullname.'.pdf');
        $filename = 'request_letter_'.$data->claimant->cit_fullname.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_request_letter_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_request_letter_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        
        PDF::Output($folder.$filename,"I");
    }

    public function printCaseStudy(Request $request, $id)
    {
        $data = Assistance::find($id);
        PDF::SetTitle('Case Study for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 20, 30, true);    
        PDF::SetAutoPageBreak(TRUE, 20);
        PDF::AddPage('P', 'FOLIO');
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h1 style="text-align:center">Social Case Study Report</h1>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('</br></br><h2 style="text-align:center">Request Letter</h2></br></br>',true, false, false, false, 'center');
        $family = '';
        if (isset($data->casestudy->family)) {
            foreach ($data->casestudy->family as $key => $value) {
                $family .= '<tr>
                                <td>'.$value->info->cit_fullname.'</td>
                                <td>'.$value->info->age.'</td>
                                <td>'.$value->info->gender.'</td>
                                <td>'.$value->info->status().'</td>
                                <td>'.$value->wswscd_relation.'</td>
                                <td>'.$value->info->education().'</td>
                                <td>'.$value->info->cit_occupation.'</td>
                                <td>'.$value->wswscd_health_status.'</td>
                            </tr>';
            }
        }

        $plan = '';
        if (isset($data->casestudy->treatment)) {
            foreach ($data->casestudy->treatment as $key => $value) {
            $plan .= '<tr>
                            <td>'.$value->wswsc_treatment_plan_objectives.'</td>
                            <td>'.$value->wswsc_treatment_plan_activities.'</td>
                            <td>'.$value->wswsc_treatment_plan_strategies.'</td>
                            <td>'.$value->wswsc_treatment_plan_timeframe.'</td>
                        </tr>';
            }
        }

        $wswsc_problem_presented = $data->casestudy ? nl2br($data->casestudy->wswsc_problem_presented) : '';
        $wswsc_family_background = $data->casestudy ? nl2br($data->casestudy->wswsc_family_background) : '';
        $wswsc_diagnostic_impression = $data->casestudy ? nl2br($data->casestudy->wswsc_diagnostic_impression) : '';
        $wswsc_reco = $data->casestudy ? nl2br($data->casestudy->wswsc_reco) : '';
        $form = '
        <ol type="I">
            <li>
                <b>Identifying Data</b>
                <p>Minor: <b>'.$data->claimant->cit_fullname.'</b> 
                    Sex: <b>'.$data->claimant->gender.'</b> 
                    Age: <b>'.$data->claimant->age.'</b>
                    Date of Birth: <b>'.Carbon::parse($data->claimant->cit_date_of_birth)->format('F j, Y').'</b>
                </p>
                <p>Place of Birth: <b>'.$data->claimant->cit_place_of_birth.'</b></p>
                <p>Address: <b>'.$data->claimant->full_add().'</b></p>
                <p>Date of Interviewed: <b>'.Carbon::parse($data->create_at)->format('F j, Y').'</b>
                    Education Attainment: <b>'.$data->claimant->education().'</b> 
                </p>
                <p></p>
            </li>
            <li>
                <b>Family Composition</b>
                <br>
                <br>
                <table border="1" style="padding=1px">
                    <tr>
                        <th width="100px" style="text-align:center;"><b>Name</b></th>
                        <th width="25px" style="text-align:center;"><b>Age</b></th>
                        <th width="45px" style="text-align:center;"><b>Gender</b></th>
                        <th width="65px" style="text-align:center;"><b>Civil Status</b></th>
                        <th width="50px" style="text-align:center;"><b>Rel. to Client</b></th>
                        <th width="50px" style="text-align:center;"><b>Educl. Attmt.</b></th>
                        <th width="70px" style="text-align:center;"><b>Occupation / income</b></th>
                        <th width="50px" style="text-align:center;"><b>Health Status</b></th>
                    </tr>
                    '.$family.'
                </table>
            </li>
            <li>
                <b>Problems Presented</b>
                <p style="text-indent: 30px">'.$wswsc_problem_presented.'</p>
            </li>
            <li>
                <b>Family Background</b>
                <p style="text-indent: 30px">'.$wswsc_family_background.'</p>
            </li>
            <li>
                <b>Diagnostic Impression</b>
                <p style="text-indent: 30px">'.$wswsc_diagnostic_impression.'</p>
            </li>
            <li>
                <b>Treatment Plan</b>
                <br>
                <br>
                <table border="1" style="padding=1px">
                    <tr>
                        <th style="text-align:center;"><b>Objectives</b></th>
                        <th style="text-align:center;"><b>Activities</b></th>
                        <th style="text-align:center;"><b>Stratigies</b></th>
                        <th style="text-align:center;"><b>Timeframe</b></th>
                    </tr>
                    '.$plan.'
                </table>
            </li>
            <li>
                <b>Recommendation:</b>
                <p style="text-indent: 30px">'.$wswsc_reco.'</p></br>
            </li>
            
            
        </ol>
        ';
        PDF::writeHTML($form,true, false, false, false, 'center');

        $socialWorker = ($data->socialWorker) ? $data->socialWorker->fullname : '';
        PDF::writeHTML('<p style="text-align:right">Prepare By:</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:right">'.$socialWorker.'</p>',true, false, false, false, 'center');
        PDF::writeHTML('<b style="text-align:right">Social Worker</b></br></br></br>',true, false, false, false, 'center');

        $approver = ($data->approver) ? $data->approver->fullname : '';
        PDF::writeHTML('<p style="text-align:left">Recommending Approval:</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:left"></p>',true, false, false, false, 'center');
        PDF::writeHTML('<b style="text-align:left">City Social Welfare & Devt. Officer</b></br></br></br>',true, false, false, false, 'center');

        PDF::ln(10);
        PDF::writeHTML('<b style="text-align:center">'.$this->mayor.'</b></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:center">City Mayor</p>',true, false, false, false, 'center');

        // PDF::Output('request_letter_'.$data->claimant->cit_fullname.'.pdf');
        $filename = 'request_letter_'.$data->claimant->cit_fullname.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_case_study_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('welfare_assistance_case_study_prepared_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        $apporveId=user_mayor()->user_id;
        $varifiedSignature = $this->_commonmodel->getuserSignature($apporveId);
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
        $apporveId=user_mayor()->user_id;
        $certifiedSignature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
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
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }
    
    public function printEligibility(Request $request, $id)
    {
        $data = Assistance::find($id);
        PDF::SetTitle('Certificate of Eligibility for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 20, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('L', 'LETTER');

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h3 style="text-align:center">Republic of the Philippines</h3>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 15);
        PDF::writeHTML('<h1 style="text-align:center">City Social Welfare and Development Office</h1>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:center">Palayan City</p>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 8);
        PDF::writeHTML('<h4>BA Form 206</h4>',true, false, false, false, 'center');
        PDF::writeHTML('<h4>Series of 974</h4>',true, false, false, false, 'center');

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('</br></br><h2 style="text-align:center">CERTIFICATE OF ELIGIBILITY</h2></br></br>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<p style="text-indent: 30px">This is to certify that <b>'.$data->claimant->cit_fullname.',</b> residing at Barangay '.$data->claimant->brgy->brgy_name.' has been found eligible for AICS under bureau of Assistance after interview and case study has been made. Records of case study dated '.Carbon::parse($data->wswa_date_applied)->format('jS F Y').' are in the confidential File of Unit</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-indent: 30px">Client is recommended for assistance in the amount of <b>PHP '.number_format((float)$data->wswa_amount,2).'</b> for '.$data->assistanceType->wsat_description.'.</p></br></br></br>',true, false, false, false, 'center');


        $socialWorker = ($data->socialWorker) ? $data->socialWorker->fullname : '';
        PDF::writeHTML('<p style="text-align:right">Prepare By:</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:right">'.$socialWorker.'</p>',true, false, false, false, 'center');
        PDF::writeHTML('<b style="text-align:right">Social Worker</b></br></br></br>',true, false, false, false, 'center');

        $approver = ($data->approver) ? $data->approver->fullname : '';
        PDF::writeHTML('<p style="text-align:left">Approved By:</p></br></br></br>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:left">'.$approver.'</p>',true, false, false, false, 'center');
        PDF::writeHTML('<b style="text-align:left">CSWD Officer</b></br></br></br>',true, false, false, false, 'center');

        // PDF::Output('certficate_of_eligibility_'.$data->claimant->cit_fullname.'.pdf');
        $filename = 'certficate_of_eligibility_'.$data->claimant->cit_fullname.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_eligibility_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('welfare_assistance_eligibility_prepared_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        if($signType==2){
            PDF::Output($folder.$filename,'F');
            @chmod($folder.$filename, 0777);
        }
        $arrData['filename'] = $filename;
        $arrData['isMultipleSign'] = 1;
        $arrData['isDisplayPdf'] = 0;
        $arrData['isSavePdf'] = 0;
        
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
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

        $certifiedSignature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
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
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }

    public function printApplication(Request $request, $id)
    {
        $data = Assistance::find($id);
        PDF::SetTitle('Assistance Application Form '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 20, 20,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('L', 'LETTER');

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h3 style="text-align:center">Republic of the Philippines</h3>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 15);
        PDF::writeHTML('<h1 style="text-align:center">City Social Welfare and Development Office</h1>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:center">Palayan City</p>',true, false, false, false, 'center');

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('</br></br><h2>Application Form</h2></br></br>',true, false, false, false, 'center');
        
        // row 1
        $assistanceStatus =  StatusTypeModel::where('wsst_is_active',1)->get()->chunk(3);
        $status = '<table>';
        foreach($assistanceStatus as $chunk){
            $status .= '<tr>';
                foreach($chunk as $stat){
                    $check = in_array($stat->id,explode(',',$data->wsst_id)) ? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
                    $status .= '<td>
                        '.$check. '
                        '.$stat->wsst_description.'
                    </td>';
                }
            $status .= '</tr>';
        }
        $status .= '</table>';
        PDF::writeHTML($status,true, false, false, false, 'center');

        // row 2
        $head = '
        <table>
            <tr>
                <td width="90px"><b>Assistance Type:</b></td>
                <td>'.$data->assistanceType->wsat_description.'</td>
            </tr>
            <tr>
                <td><b>Family Head:</b></td>
                <td>'.$data->head->cit_fullname.'</td>
            </tr>
            <tr>
                <td><b>Address:</b></td>
                <td>'.$data->head->full_add().'</td>
            </tr>
        </table>
        ';
        PDF::writeHTML($head,true, false, false, false, 'center');

        // row 3
        $family = '<table>
            <tr>
                <th><b>Dependent Name</b></th>
                <th><b>Relation to Head</b></th>
                <th><b>Age</b></th>
            </tr>
        ';
        foreach($data->dependents as $dependent){
            $family .= '<tr>';
            $family .= '<td>'.$dependent->dependent->cit_fullname.'</td>';
            $family .= '<td>'.$dependent->wsd_relation.'</td>';
            $family .= '<td>'.$dependent->dependent->age.'</td>';
            $family .= '</tr>';
        }
        $family .= '</table>';
        PDF::writeHTML($family,true, false, false, false, 'center');

        // row 4
        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('</br><h4>Remarks:</h4>',true, false, false, false, 'center');
        $remarks = preg_split('/\r\n|\r|\n/',$data->wswa_remarks);
        foreach ($remarks as $p) {
            PDF::writeHTML('<p style="text-indent: 30px">'.$p.'</p></br>',true, false, false, false, 'center');

        }

        $socialWorker = ($data->socialWorker) ? $data->socialWorker->fullname : '';
        // sign
        $sign = '<table>
                    <tr>
                        <td>'.$data->claimant->cit_fullname.'</td>
                        <td style="text-align:right">'.$socialWorker.'</td>
                    </tr>
                    <tr>
                        <td><b>Applicant</b></td>
                        <td style="text-align:right"><b>Social Worker</b></td>
                    </tr>
                </table>
        ';
        PDF::writeHTML($sign,true, false, false, false, 'center');

        // PDF::Output('Assistance_Application_Form_'.$data->claimant->cit_fullname.'.pdf');
        $filename = 'Assistance_Application_Form_'.$data->claimant->cit_fullname.'.pdf';
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_application_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $arrSign= $this->_commonmodel->isSignApply('welfare_assistance_application_prepared_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        $signType = $this->_commonmodel->getSettingData('sign_settings');
        $signature = $this->_commonmodel->getuserSignature($data->socialWorker->user_id);
        $path =  public_path().'/uploads/e-signature/'.$signature;
        if($isSignVeified==1 && $signType==2){
            $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
            // echo $signature;exit;
            if(!empty($signature) && File::exists($path)){
                // Apply Digital Signature
                PDF::Output($folder.$filename,'F');
                $arrData['signaturePath'] = $signature;
                $arrData['filename'] = $filename;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignVeified==1 && $signType==1){
            // Apply E-Signature
            if(!empty($signature) && File::exists($path)){
                PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        
        PDF::Output($folder.$filename,"I");

    }
}

