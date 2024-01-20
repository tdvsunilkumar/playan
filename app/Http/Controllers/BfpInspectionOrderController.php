<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BfpInspectionOrder;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use File;
use Auth;
class BfpInspectionOrderController extends Controller
{

    public $data = [];
    public $postdata = [];
    public $arrbfpapplication = array(""=>"Select App");
    public $arrBarangay = array("" => "Please Select");
    public $arrRegion = array("" => "Please Select");
    public $arrClient = array("" => "Please Select");
    public $arrBusiness = array("" => "Please Select");
    public $arrHrEmplyees = array("" => "Please Select");
    public $arrProvinces = array("" => "Please Select");
	public $arrYears = array(""=>"Select Year");
    
    public function __construct()
    {
		
        $this->_bfpinspectionorder = new BfpInspectionOrder();
        $this->_commonmodel = new CommonModelmaster();
		$this->data = array('id' => '','bend_id'=>'','bff_id'=>'','client_id'=>'','brgy_id'=>'','busn_id'=>'','bio_date'=>'','bio_year'=>date("Y"),'bio_inspection_duration'=>'','bio_inspection_proceed'=>'','bio_inspection_purpose'=>'',
		'bio_remarks'=>'','bio_assigned_to'=>'','bio_recommending_approval'=>'','bio_approved'=>'','bio_recommending_position'=>'','bio_approved_position'=>'','bio_recommending_status'=>'','bio_approved_status'=>'','created_by'=>'');
        foreach ($this->_bfpinspectionorder->getBarangay() as $val) {
            $this->arrBarangay[$val->id] = $val->brgy_name;
        }
		$this->_Barangay = new Barangay();
		$arrYrs = $this->_bfpinspectionorder->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bio_year] =$val->bio_year;
        }
		foreach ($this->_bfpinspectionorder->getHRemployees() as $val) {
            $this->arrHrEmplyees[$val->id]=$val->fullname;
        }
		
		foreach ($this->_bfpinspectionorder->getBfpApplications() as $val) {
            $this->arrbfpapplication[$val->id]=$val->bff_application_no;
        }
		$this->slugs = 'fire-protection/inspection-order';
    }
    public function index(Request $request)
    {	
		$this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        return view('inspection.index',compact('arrYears'));
    }
		

	public function store(Request $request){
		$busn_id =  $request->input('busn_id');
        $bend_id =  $request->input('bbendo_id');
		$sent_year =  $request->input('year');
		$arrHrEmplyees =$this->arrHrEmplyees; 
		$bfpapplications =$this->arrbfpapplication;
		$current_user_login = \Auth::user()->id;
		$data = (object)$this->data;
		$arrbploBusiness  = $this->_bfpinspectionorder->getbploBusiness($busn_id);
        $bff_id  = $this->_bfpinspectionorder->getBfpAppformid($busn_id);
        
		$complete_address = $this->_Barangay->findDetails($arrbploBusiness->busn_office_main_barangay_id);
		if($request->input('id')>0 && $request->input('submit')==""){
			$data = BfpInspectionOrder::find($request->input('id'));
	
		}elseif(DB::table('bfp_inspection_orders')->where('busn_id',$busn_id)->where('bend_id',$bend_id)->where('bio_year',$sent_year)->exists()){						
			$data = DB::table('bfp_inspection_orders')->where('busn_id',$busn_id)
										->where('bend_id',$bend_id)
										->where('bio_year',$sent_year)
										->select('*')->first();
		}else{
             $getdatausersave = $this->_bfpinspectionorder->CheckFormdataExist('2',\Auth::user()->id);
               if(count($getdatausersave)>0){
                  $usersaved = json_decode($getdatausersave[0]->is_data);
                  $data->bio_recommending_approval = $usersaved->bio_recommending_approval;
                  $data->bio_recommending_position = $usersaved->bio_recommending_position;
                  $data->bio_approved = $usersaved->bio_approved;
                  $data->bio_approved_position = $usersaved->bio_approved_position;
                  $data->bio_assigned_to = $usersaved->bio_assigned_to;
               } 
   
        }
		
		if(!empty($data->bio_document)){
			$arrdocDtls = $this->generateDocumentListInspection($data->bio_document,$data->bend_id,$data->is_active);
			if(isset($arrdocDtls)){
				$data->bio_document = $arrdocDtls;
			}
		}
		if($request->input('submit')!=""){
			foreach((array)$this->data as $key=>$val){
				$this->data[$key] = $request->input($key);
			}
			$this->data['updated_by']=\Auth::user()->id;
			$this->data['updated_at'] = date('Y-m-d H:i:s');
			if($request->input('id')>0){
				$this->_bfpinspectionorder->updateData($request->input('id'),$this->data);
				$success_msg = 'BFP Inspection Order updated successfully.';	
			}else{
				$curr_years = date('Y');
				$appali_no=$this->generateApplictionNumber($curr_years."-");
				$this->data['bio_inspection_no']= $appali_no;
                if(isset($bff_id->id)){
                   $this->data['bff_id']=$bff_id->id; 
                }else{
                   $this->data['bff_id']=0;  
                }
				$this->data['bio_recommending_status']=($this->data['bio_recommending_status']=="")?'0':'1';
				$this->data['bio_approved_status']= ($this->data['bio_approved_status']=="")?'0':'1';
				$this->data['bio_date'] = date('Y-m-d');
				$this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['created_by'] =\Auth::user()->id;
				$this->data['is_active']= 1;
				$lastId=$this->_bfpinspectionorder->addData($this->data);

                $user_savedata = array();
                $user_savedata['bio_recommending_approval'] = $request->input('bio_recommending_approval');
                $user_savedata['bio_recommending_position'] = $request->input('bio_recommending_position');
                $user_savedata['bio_approved'] = $request->input('bio_approved');
                $user_savedata['bio_approved_position'] = $request->input('bio_approved_position');
                $user_savedata['bio_assigned_to'] = $request->input('bio_assigned_to');
                $userlastdata = array();
                $userlastdata['form_id'] = 2;
                $userlastdata['user_id'] = \Auth::user()->id;
                $userlastdata['is_data'] = json_encode($user_savedata);
                $userlastdata['created_at'] = date('Y-m-d H:i:s');
                $userlastdata['updated_at'] = date('Y-m-d H:i:s');
                $checkisexist = $this->_bfpinspectionorder->CheckFormdataExist('2',\Auth::user()->id);
                if(count($checkisexist) >0){
                    $this->_bfpinspectionorder->updateusersavedataData($checkisexist[0]->id,$userlastdata);
                }else{
                    $this->_bfpinspectionorder->addusersaveData($userlastdata);
                }
                //$accNo = str_pad($lastId, 6, '0', STR_PAD_LEFT);
                //$arr['bio_inspection_no']=$curr_years."".$accNo;
                //$this->_bfpinspectionorder->updateData($lastId,$arr);
				$success_msg = 'BFP Inspection Order added successfully.';
			}
			return redirect()->back();
		}
		if($data->bio_assigned_to && $data->bio_recommending_approval && $data->bio_approved){
			$bioassigned_user_id  = $this->_bfpinspectionorder->selectHRemployees($data->bio_assigned_to);
			$recommending_user_id  = $this->_bfpinspectionorder->selectHRemployees($data->bio_recommending_approval);
			$approved_user_id  = $this->_bfpinspectionorder->selectHRemployees($data->bio_approved);
			
		}else{
			$bioassigned_user_id  = 0;
			$recommending_user_id  =0;
			$approved_user_id  = 0;
		}
		return view('inspection.create',compact('data','bioassigned_user_id','recommending_user_id','approved_user_id','current_user_login','busn_id','bend_id','bfpapplications','arrbploBusiness','complete_address','arrHrEmplyees'));
		
	}
	public function generateApplictionNumber($company_code) {
		$curr_years = date('Y');
        $prefix = $company_code;
        $last_bookingq=DB::table('bfp_inspection_orders')->orderBy('id','desc');
        
            if($last_bookingq->count() > 0){
                $last_booking=$last_bookingq->first()->bio_inspection_no;
            } else {
              $last_booking=$curr_years."-";
            }
            if($last_booking){
                $last_booking=$last_booking;
            } else {
                $last_booking=$curr_years."-";
            }
            
        $last_number = str_replace($prefix, "", $last_booking);
        $counter = intval(ltrim($last_number, "0")) + 1;
        $appliction_no = $prefix . str_pad($counter, 4, 0, STR_PAD_LEFT);
        return $appliction_no;
    }
    
	public function getPosition(Request $request){
    	$id= $request->input('id');
        $data = $this->_bfpinspectionorder->getPosition($id);
        echo json_encode($data);
    }
	
	public function approvedsataus(Request $request){
    	$id= $request->input('id');
		$data=DB::table('bfp_inspection_orders')
            ->where('id', $id)
            ->update(['bio_approved' =>$request->input('bio_approved'),'bio_approved_status' =>1,'bio_approved_date'=> date('Y-m-d H:i:s')]);
			
       return response()->json(['success' => $data]);
    }
	public function biorecommendingapproval(Request $request){
		$id= $request->input('id');
		$data=DB::table('bfp_inspection_orders')
            ->where('id', $id)
            ->update(['bio_recommending_approval' =>$request->input('bio_recommending_approval'),'bio_recommending_status' =>1,'bio_recommending_approva_date'=> date('Y-m-d H:i:s')]);
       return response()->json(['success' => $data]);
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
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
    
	
    public function getList(Request $request)
    {
        $data = $this->_bfpinspectionorder->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row) {
			$sr_no=$sr_no+1;
			$actions = '';
			$arrbploBusiness=$this->_bfpinspectionorder->getbploBusiness($row->busn_id);
			$complete_address=$this->_Barangay->findDetails($arrbploBusiness->busn_office_main_barangay_id);
            $arrHrEmplyees =$this->arrHrEmplyees;
			$actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center getPositionslected" data-url="' . url('/bfpinspectionorder/store?id='.$row->id).'&busn_id='.$row->busn_id.'&bbendo_id='.$row->bend_id.'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Inspection Order">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
			if($row->bio_recommending_status == 1 && $row->bio_approved_status == 1){
				   $actions .= '<div class="action-btn bg-info ms-2">
						<a href="#" title="Print Inspection Order"  data-title="Print Inspection Order" class="mx-3 btn print btn-sm" id="'.$row->id.'">
							<i class="ti-printer text-white"></i>
						</a>
					</div>';
			}	
			$actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
						'<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
			
			$arr[$i]['srno']=$sr_no;
            $arr[$i]['bio_year'] = $row->bio_year;
            $arr[$i]['busn_name'] = $row->busn_name;
			$arr[$i]['tex_payer'] = $row->ownar_name;
			$arr[$i]['complete_address'] = $complete_address;
			$arr[$i]['bio_inspection_no'] = $row->bio_inspection_no;
			$arr[$i]['bio_recommending_approval'] =$arrHrEmplyees[$row->bio_approved];
			//($row->bio_approved_status > 0 && $row->bio_approved > 0)?$arrHrEmplyees[$row->bio_approved]:''; 
            $arr[$i]['action'] = $actions;
			
			
             
            $i++;
        }

        $totalRecords = $data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
	
    
	public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_bfpinspectionorder->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Inspection Order ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
    

    public function inspectionPrint(Request $request){
            $id= $request->input('id');
            $data = $this->_bfpinspectionorder->getPrintDetails($id);
            if(count($data)>0){
                $data = $data[0];
            }
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->text_input_as_HTML = true;
            $filename="";
            $html = file_get_contents(resource_path('views/layouts/templates/inspectioncertificate.html'));
			
            $logo = url('/assets/images/leftOrderLogo.png');
            $logoright = url('/assets/images/rightInspection.jpg');
            $sign = url('/assets/images/cpd-sign1.png');
            $signeture = url('/assets/images/signeture2.png');
            $html = str_replace('{{LOGO}}',$logo, $html);
            $html = str_replace('{{logoright}}',$logoright, $html);
            $html = str_replace('{{sign}}',$sign, $html);

			foreach ($this->_bfpinspectionorder->getHRemployees() as $val) {
            if($val->suffix){
                    $this->arrHrEmplyees[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname.', '.$val->suffix;
                }else{
                    $this->arrHrEmplyees[$val->id]=$val->title.' '.$val->firstname.' '.$val->middlename.' '.$val->lastname;
                }
            }
            if($data->suffix){
              $owner = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name.', '.$data->suffix;
            }else{
                $owner = $data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
            }
			
            $address=$data->brgy_name.','.$data->mun_desc.','.$data->prov_desc.','.$data->reg_region;
			$arrHrEmplyees =$this->arrHrEmplyees;
			$bio_assigned_to = $arrHrEmplyees[$data->bio_assigned_to];
			$RECOMMEND = $arrHrEmplyees[$data->bio_recommending_approval];
			$APPROVED = $arrHrEmplyees[$data->bio_approved];
			$bio_inspection_no = $data->bio_inspection_no;
			$rpc_or_date= $data->created_at;
			$bio_inspection_purpose = $data->bio_inspection_purpose;
			$rpc_or_dateformatted_date = date("jS", strtotime($rpc_or_date));
            $datefooter = date("d.m.y", strtotime($rpc_or_date));
            $formatted_dateMont = date("F, Y", strtotime($rpc_or_date));
			$formatted_dateMontTime = date("F, Y /h:i A", strtotime($rpc_or_date));
			$bio_inspection_duration = $data->bio_inspection_duration.'Days';
            $bio_remarks= $data->bio_remarks;
			$bio_inspection_proceed= $data->bio_inspection_proceed;
            $lines = explode(',', $bio_inspection_proceed);
            $line1 = trim($lines[0]);
            $line2 = trim($lines[1]);
            $busn_name=$data->busn_name;
            $modifiedString = str_replace('.', ', ', $line2);
            $line3 = trim(implode(',', array_slice($lines, 2)));
			$bio_recommending_position=$data->bio_recommending_position;
            $bio_approved_position=$data->bio_approved_position;
            $html = str_replace('{{bio_inspection_proceed2}}',$owner, $html);
            $html = str_replace('{{bio_inspection_proceed3}}',$address, $html);
            $html = str_replace('{{bio_inspection_proceed}}',$busn_name, $html);
           
           $html = str_replace('{{datefooter}}',strtoupper($datefooter), $html);
		   $html = str_replace('{{RECOMMEND}}',strtoupper($RECOMMEND), $html);
           $html = str_replace('{{APPROVED}}',strtoupper($APPROVED), $html);
		   $html = str_replace('{{bio_inspection_duration}}',$bio_inspection_duration, $html);
           $html = str_replace('{{bio_remarks}}',strtoupper($bio_remarks), $html);
		   $html = str_replace('{{bio_inspection_purpose}}',strtoupper($bio_inspection_purpose), $html);
           $html = str_replace('{{bio_assigned_to}}',strtoupper($bio_assigned_to), $html);
           $html = str_replace('{{bio_inspection_no}}',strtoupper($bio_inspection_no), $html);
		   $html = str_replace('{{rpc_or_dateformatted_date}}',$rpc_or_dateformatted_date, $html);
		   $html = str_replace('{{formatted_dateMont}}',$formatted_dateMont, $html);
           $html = str_replace('{{formatted_dateMontTime}}',$formatted_dateMontTime, $html);
		   $html = str_replace('{{representative}}',strtoupper($RECOMMEND), $html);
		   // $html = str_replace('{{bio_inspection_proceed}}',strtoupper($line1), $html);
           // $html = str_replace('{{bio_inspection_proceed2}}',strtoupper($modifiedString), $html);
           // $html = str_replace('{{bio_inspection_proceed3}}',strtoupper($line3), $html);
           $html = str_replace('{{bio_recommending_position}}',strtoupper($bio_recommending_position), $html);
           $html = str_replace('{{bio_approved_position}}',strtoupper($bio_approved_position), $html);
           $mpdf->WriteHTML($html);
           $applicantname = "Inspection Order";
           $folder =  public_path().'/uploads/inspectioncertificatePdf/';
            // if(!File::exists($folder)) { 
            //     File::makeDirectory($folder, 0777, true, true);
            // }
            $filename = public_path() . "/uploads/inspectioncertificatePdf/" . $applicantname;
            $mpdf->Output($filename, "F");
            // @chmod($filename,  0777);
            echo url('/uploads/inspectioncertificatePdf/' . $applicantname);
    }
    public function getAccountnumber(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpinspectionorder->getBfpAppform($id);
        echo json_encode($data);
    }
    public function getBusinessId(Request $request){
        $id= $request->input('id');
        $data = $this->_bfpinspectionorder->getBendId($id);
        echo json_encode($data);
    }
	
	public function uploadAttachmentInspection(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('bio_year');
        $bbendo_id =  $request->input('bend_id');
		
        $arrEndrosment = $this->_bfpinspectionorder->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
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
                    $arrJson = json_decode($arrEndrosment->bio_document,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['bio_document'] = json_encode($finalJsone);
				
                $this->_bfpinspectionorder->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['bio_document'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
	
	public function generateDocumentListInspection($arrJson,$bbendo_id, $is_active=''){
        $html = "";
        $dclass = ($is_active==2 || $is_active==3)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $html .= "<tr>
                  <td>".$val['filename']." </td>
                  <td>
				  <a class='btn' href='".asset('uploads/document_requirement').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
				  <div class='action-btn bg-danger ms-2'>
					<a href='#' class='mx-3 btn btn-sm deleteEndrosmentInspections ti-trash text-white text-white ' rid='".$val['filename']."'".$dclass." bbendo_id='".$bbendo_id."'></a>
				  </div>
				  </td>
                </tr>";
            }
        }
        return $html;
    }
	public function deleteEndrosmentInspectionAttachment(Request $request){
        $rid = $request->input('rid');
        $busn_id = $request->input('id');
        $year = $request->input('year');
        $bbendo_id = $request->input('bbendo_id');
		
        $arrEndrosment = $this->_bfpinspectionorder->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->bio_document,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['bio_document'] = json_encode($arrJson);
                    $this->_bfpinspectionorder->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }
}
