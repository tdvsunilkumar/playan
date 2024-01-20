<?php

namespace App\Http\Controllers;

use App\Models\HoWaterPotability;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Barangay;
use Illuminate\Foundation\Validation\ValidatesRequests;
use PDF;
use DB;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use File;
class HoWaterPotabilityController extends Controller
{
    
    public $data = [];
    public $arrorDate = array(""=>"Please Select");
    public $arrorAmount = array(""=>"Please Select");
    public $arrinspected = array(""=>"Please Select");
    public $busn_name = array(""=>"Please Select");
    public $cit_fullname = array(""=>"Please Select");
     public $employee = array(""=>"Please Select");

    public function __construct(){
        $this->_howaterpotability = new HoWaterPotability();
		$this->_commonmodel = new CommonModelmaster();
        $this->slugs = 'healthy-and-safety/water-potability';
        
    $this->data = array('id'=>'','business_id'=>'','brgy_id'=>'','certificate_no'=>'','cashierd_id'=>'','cashier_id'=>'','or_no'=>'',
    'or_date'=>'','or_amount'=>'','date_start'=>'','date_end'=>'','requestor_id'=>'','date_issued'=>'','inspected_by'=>'','inspector_position'=>'','approved_by'=>'',
    'approver_position'=>'','inspector_is_approved'=>'','is_approved'=>'','is_free'=>'','status'=>'');
        
        foreach ($this->_howaterpotability->getordateId() as $val) {
            $this->arrorDate[$val->cashier_or_date]=$val->cashier_or_date;
        }
        foreach ($this->_howaterpotability->getoramountId() as $val) {
            $this->arrorAmount[$val->id]=$val->tfc_amount;
        }
        foreach ($this->_howaterpotability->getinspectedId() as $val) {
            $this->arrinspected[$val->id]=$val->fullname;
        }
        foreach ($this->_howaterpotability->getBusiness() as $val) {
            $this->busn_name[$val->id]=$val->busn_name;
        }
        foreach ($this->_howaterpotability->getCitizenFullname() as $val) {
            $this->cit_fullname[$val->id]=$val->cit_fullname;
        }
        foreach ($this->_howaterpotability->getEmployee() as $val) {
           $this->employee[$val->id]=$val->fullname;
        }
    }
    
    
    public function index(Request $request)
    {
        return view('howaterpotability.index');
    }
    
    public function getList(Request $request){
        $data=$this->_howaterpotability->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
             
			
            $brgy=Barangay::BarangayDetails($row->brgy_id);
            
            $brgyName=(!empty($brgy->brgyName)) ? $brgy->brgyName.', ' : " "; 
			$business_mun=(!empty($brgy->municipal)) ? $brgy->municipal.'' : " ";
			
			$arr[$i]['no']=$j;
            $arr[$i]['name']=$row->busn_name;
            $arr[$i]['address']=$brgyName.$business_mun;
            $arr[$i]['cert_no']=$row->certificate_no;
            $arr[$i]['or_no']=$row->or_no;
            //$arr[$i]['or_date']=Carbon::parse($row->or_date)->format('M d, Y');
            //$arr[$i]['or_amount']=$row->or_amount;
            //$arr[$i]['start_date']=Carbon::parse($row->date_start)->format('M d, Y');
            //$arr[$i]['end_date']=Carbon::parse($row->date_end)->format('M d, Y');
            //$arr[$i]['requestor_name']=$row->cit_fullname;
            $arr[$i]['issuance_date']=Carbon::parse($row->date_issued)->format('M d, Y');
            $arr[$i]['inspected_by']=$row->fullname;
            //$arr[$i]['inspector_position']=$row->inspector_position;
            //$arr[$i]['approved_by']=$row->app_fullname;
            //$arr[$i]['approver_position']=$row->approver_position;
            //$arr[$i]['approval']= ($row->is_approved==1 ? 'Approved': 'Pending');
            //$arr[$i]['free']=($row->is_free==1 ? 'Free': '-');
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
							 <div class="action-btn bg-warning ms-2">
								<a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/healthy-and-safety/water-potability/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Water Potability">
									<i class="ti-pencil text-white"></i>
								</a>
							</div>
						   </div>
						   <div class="action-btn bg-info ms-2">
								<a title="Print Water Potability"  data-title="Print Water Potability" class="mx-3 btn print btn-sm  align-items-center digital-sign-btn" target="_blank" href="'.url('/healthy-and-safety/water-potability/print/'.(int)$row->id).'" >
									<i class="ti-printer text-white"></i>
								</a>
							</div>
                    '.$status.'
                </div>
                ';
                //  <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                //     </a>
                // </div>
                
            
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

    
   public function ActiveInactive(Request $request)
   {
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('status' => $bt_is_activeinactive);
        $this->_howaterpotability->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        $citizens = (object)[
            'cit_id'=>null,
            'cit_fullname'=>null,
        ];
        $select_or_nos = ['' => 'Select OR No'];
        $arrorDate = $this->arrorDate;
        $arrorAmount = $this->arrorAmount;
        $arrinspected = $this->arrinspected; 
        $busn_name = $this->busn_name;
        $cit_fullname = $this->cit_fullname;
        $inspected_by_name = "";
        $brgy_id = "";
        $employee =$this->employee;
        if(isset($request->citizen_id)){
            $citizen_id = $request->citizen_id;//for or number
            $citizens = $this->_howaterpotability->getCitizenAddress($citizen_id);
        }
        if (isset($citizen_id)){
            // Getting the or no
            $or_nos = $this->_howaterpotability->getOrNumbers($citizen_id);
            foreach ($or_nos->get() as $key => $value) {
                $select_or_nos[$value->or_no] = $value->or_no;
            };
        }
        if(!isset($this->data['or_no'])){
            $this->data['or_date'] = '';
            $this->data['or_amount'] = '';
            $this->data['or_no'] = '';
        }
        // $selected = [
           
        //     "or_no" => null,
        //     "or_date" => null,
        //     "amount" => null,
        //     "is_free" => null,
        // ];
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoWaterPotability::find($request->input('id'));
            $inspected_by_name = HoWaterPotability::getinspectedname($data->inspected_by);
            $brgy_data = $this->_howaterpotability->getBrgyDetails($data->id);        
            $brgy=Barangay::findDetails($brgy_data->busn_office_barangay_id);
            $house_no=(!empty($brgy->cit_house_lot_no)) ? $brgy->cit_house_lot_no.',' : "";
            $cit_street_name=(!empty($brgy->cit_street_name)) ? $brgy->cit_street_name.',' : "";
            $cit_subdivision=(!empty($brgy->cit_subdivision)) ? $brgy->cit_subdivision.',' : "";
            $brgy_data=(!empty($brgy)) ? $brgy : "";
            $brgy_id=$house_no.$cit_street_name.$cit_subdivision.$brgy_data;
            $citizens = $this->_howaterpotability->getCitizenAddress($data->requestor_id);
            // Getting the or no
            $or_nos = $this->_howaterpotability->getOrNumbers($data->requestor_id);
            foreach ($or_nos->get() as $key => $value) {
                $select_or_nos[$value->or_no] = $value->or_no;
            };
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['status'] = 1;
            if($request->input('id')>0){
                $this->_howaterpotability->updateData($request->input('id'),$this->data);
                $success_msg = 'Water Potability updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                // dd($this->data);
                $this->_howaterpotability->addData($this->data);
                $success_msg = 'Water Potability added successfully.';
            }
            return redirect()->back()->with('success', __($success_msg));	
        }
		if(!empty($data->doc_json)){
        $arrdocDtls = $this->generateDocumentList($data->doc_json,$data->id);
            if(isset($arrdocDtls)){
                $data->arrDocumentDetailsHtml = $arrdocDtls;
            }
        }else{
            $data->arrDocumentDetailsHtml ="";
        }
		if($data->inspected_by > 0){ 
            $esignisinspected_by = $this->_howaterpotability->selectHRemployees($data->inspected_by); 
        }else{
            $esignisinspected_by = 0;
        }
		if($data->approved_by > 0){ 
            $esignisapproveds = $this->_howaterpotability->selectHRemployees($data->approved_by); 
        }else{
            $esignisapproveds = 0;
        }
        return view('howaterpotability.create',compact('data','citizens','inspected_by_name','arrorDate','select_or_nos','arrorAmount','arrinspected','busn_name','cit_fullname','brgy_id','employee','esignisapproveds','esignisinspected_by'));
        
    }
    
    public function getBrgyDetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_howaterpotability->getBrgyDetails($id);
        $brgy=Barangay::findDetails($data->busn_office_main_barangay_id);
        $house_no=(!empty($data->cit_house_lot_no)) ? $data->cit_house_lot_no.',' : "";
        $cit_street_name=(!empty($data->cit_street_name)) ? $data->cit_street_name.',' : "";
        $cit_subdivision=(!empty($data->cit_subdivision)) ? $data->cit_subdivision.',' : "";
        $brgy_data=(!empty($brgy)) ? $brgy : "";
        $addreass=$house_no.$cit_street_name.$cit_subdivision.$brgy_data;
        $details=[
                    'addreass' => $addreass,
					'brgy_id'=>$data->busn_office_main_barangay_id
        ];
        echo json_encode($details);
    }
    public function getInsPosDetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_howaterpotability->getInsPosDetails($id);
        $inspector_position=$data->description;
       
        $details=[
                    'inspector_position' => $inspector_position,
        ];
        echo json_encode($details);
    }
    public function getAppPosDetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_howaterpotability->getAppPosDetails($id);
        $approver_position=$data->description;
       
        $details=[
                    'approver_position' => $approver_position,
        ];
        echo 
        json_encode($details);
    }

    public function getORNoDetails($or_no){
        try {
            $or_details = $this->_howaterpotability->getOrNumberDetails($or_no);
            return response()->json(['status' => 200, 'data' => $or_details]);
        } catch (\Exception $e) {
            return ($e->getMessage());
        }
    }

    public function getOr(Request $request, $citizen_id){
        $data = [];
        $or_nos = $this->_howaterpotability->getOrNumbers($citizen_id);
                foreach ($or_nos->get() as $key => $value) {
            $data['data'][$key]['id']=$value->or_no;
            $data['data'][$key]['text']=$value->or_no;
                };
        $data['data_cnt']=$or_nos->count();
        echo json_encode($data);
    }
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'business_id'=>'required',
				//'brgy_id'=>'required',
				'certificate_no'=>'required',
				'date_issued'=>'required',
				'date_start'=>'required',
				'date_end'=>'required',
				'requestor_id'=>'required',
				'inspected_by'=>'required',
				'approved_by'=>'required',
				'inspector_position'=>'required',
				'approver_position'=>'required',
              ],[
				"business_id.required" => "Business name is required",
				"certificate_no.required" => "Certificate no is required",
				"date_issued.required" => "Issuance date is required",
                "date_start.required" => "Start date is required",
				"date_end.required" => "End date is required",
				"requestor_id.required" => "Requestor name is required",
				"inspected_by.required" => "Inspected by is required",
                "approved_by.required" => "Approved by is required",
				"inspector_position.required" => "Inspector position is required",
				"approver_position.required" => "Approver position is required",
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
    public function Print(Request $request,$id) // certificate of potability
    {
		$data = $this->_howaterpotability->getDetailsofwater($id);
		$brgy=Barangay::BarangayDetails($data->brgy_id);
		//echo '<pre>';
		//print_r($brgy);die;
        PDF::SetTitle('Certificate of potability');    
        PDF::SetMargins(15, 20, 15,true);   
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('helvetica','',9);

        $border = 0;
        $cell_height = 5;
        // $cell_height = 5;
        // 195.9 max width
		
        $business_name = strtoupper($data->busn_name);
        $business_brgy = $brgy->brgyName;
        $business_mun = $brgy->municipal;
        $business_prov = $brgy->province;
        $date_issued = Carbon::parse($data->date_issued)->format('F d, Y');
        $date_start = Carbon::parse($data->date_start)->format('F d, Y');
        $date_end = Carbon::parse($data->date_end)->format('F d, Y');
        $inspected_by = strtoupper($data->fullname);
        $inspector_position = strtoupper($data->inspector_position);
        $approved_by = strtoupper($data->app_fullname);
        $approver_position = strtoupper($data->approver_position);
        $certificate_no = $data->certificate_no;
        $or_no = $data->or_no;
        $or_date_issued = Carbon::parse($data->or_date)->format('F d, Y');

        $palayan_logo = public_path('assets/images/issuanceLogo.png');
        PDF::Image( $palayan_logo, $x = 35, $y = 22, $w = 22, $h = 0, $type = 'PNG');

        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(0, 0, "Republic of the Philippines", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "Province of Nueva Ecija", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, "<b>City of Palayan</b>", '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);
        
        PDF::SetFont('helvetica','',11);
        PDF::MultiCell(0, $cell_height, "CITY HEALTH OFFICE", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(0, $cell_height, "<B>CERTIFICATE OF POTABILITY</B>", 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(20);

        PDF::MultiCell(0, $cell_height, '<p style="text-indent:50px;">This is to certify that the results of water sample collected from <b>'.$business_name."</b> , located at Barangay ".$business_brgy.", ".$business_mun.", ".$business_prov." on ".$date_start." and ".$date_end." , showed that the source of water have passed the requirements set by the Philippine National Standard for Drinking Water (PNSWD) for Physical, Chemical and Bacteriological quality.</p>", 0, 'J', 0, 1, '', '', true, 0, true);
        
        PDF::ln();

        PDF::MultiCell(0, $cell_height, '<p style="text-indent:50px;">Based on the results, the City Health Office hereby recommends the issuance of this Certificate to <b>'.$business_name.".</b></p>", 0, 'J', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(0, $cell_height, '<p style="text-indent:50px;">Issued this '.$date_issued.".</p>", 0, 'J', 0, 1, '', '', true, 0, true);

        PDF::ln(15);

        PDF::Cell(25, $cell_height, 'Inspected by:', $border, 0, 'C');

        PDF::Cell(90, $cell_height, '', $border, 0, 'C');

        PDF::Cell(25, $cell_height, 'Approved by:', $border, 0, 'C');
        PDF::ln(25);

        PDF::Cell(5, $cell_height, '', $border, 0, 'C');
        PDF::Cell(65, $cell_height, $inspected_by, $border, 0, 'C');

        PDF::Cell(45, $cell_height, '', $border, 0, 'C');

        PDF::Cell(65, $cell_height, $approved_by, $border, 0, 'C');
        PDF::ln();

        PDF::Cell(5, $cell_height, '', $border, 0, 'C');
        PDF::Cell(65, $cell_height, $inspector_position, "T", 0, 'C');

        PDF::Cell(45, $cell_height, '', $border, 0, 'C');

        PDF::Cell(65, $cell_height, $approver_position, "T", 0, 'C');
        PDF::ln(20);

        PDF::Cell(35, $cell_height, 'Certificate No:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $certificate_no, $border, 1, 'L');

        PDF::Cell(35, $cell_height, 'Date Issued:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $date_issued, $border, 1, 'L');

        PDF::Cell(35, $cell_height, 'O.R. No.:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $or_no, $border, 1, 'L');

        PDF::Cell(35, $cell_height, 'O.R. Date Issued:', $border, 0, 'L');
        PDF::Cell(40, $cell_height, $or_date_issued, $border, 1, 'L');
        PDF::ln();

        PDF::MultiCell(0, $cell_height, "<b>Notes:</b>", 0, 'J', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(17, $cell_height, "1.", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(150, $cell_height, "This certificate must be re-validated every after examination based on the standard interval of frequency of sampling.", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(17, $cell_height, "2.", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(150, $cell_height, "Copy of results of laboratory examination must be submitted to the City Health Office for information and reference.", 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(17, $cell_height, "3.", 0, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(150, $cell_height, "Examination of drinking water must be conducted by a Department of Health Accredited Laboratory.", 0, 'L', 0, 1, '', '', true, 0, true);

        //PDF::Output('certificate_of_potability.pdf');
		
		$filename ='certificate_of_potability'.$id.'.pdf';
        // $Cityhealthoffice = ($data->h_officer) ? $data->h_officer->fullname : " ";
        // $medicaltechnologiest = ($data->m_tech) ? $data->m_tech->fullname : " ";
		$arrCertified= $this->_commonmodel->isSignApply('health_safety_water_potability_inspected_by');
        $isSignCertified = isset($arrCertified)?$arrCertified->status:0;

        $arrSign= $this->_commonmodel->isSignApply('health_safety_water_potability_approved_by');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

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
		
		$certifiedSignature = $this->_commonmodel->getuserSignature($data->user_id_inspected_by);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;
		
        $varifiedSignature = $this->_commonmodel->getuserSignature($data->user_id_approved_by);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        
          
        if($isSignVeified==1 && $signType==2 && $data->is_approved==1){
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

        if($isSignCertified==1 && $signType==2 && $data->inspector_is_approved ==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }
        if($isSignCertified==1 && $signType==1 && $data->inspector_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                PDF::Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, 35);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->is_approved==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                PDF::Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, 35);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)) { 
                File::delete($folder.$filename);
            }
        }
        PDF::Output($filename,"I");
    }
	
	
//     public function Delete(Request $request){
//         $id = $request->input('id');
//             $HematologyRange = HoHematologyRange::find($id);
//             if($HematologyRange->created_by == \Auth::user()->creatorId()){
//                 $HematologyRange->delete();
//             }
//     }

		public function uploadDocument(Request $request){
        $healthCertId =  $request->input('healthCertId');
        $arrEndrosment = HoWaterPotability::find($healthCertId);
        
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        
        if(isset($arrEndrosment)){
            $arrJson = (array)json_decode($arrEndrosment->doc_json,true);
        }
        if(empty($message)){
            if($image = $request->file('file')) {
                $destinationPath =  public_path().'/uploads/waterpotability/';
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
                $this->_howaterpotability->updateData($healthCertId,$data);
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
                                <a class='btn' href='".asset('uploads/waterpotability').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a>
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
        $arrEndrosment = HoWaterPotability::find($healthCertid);
        
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->doc_json,true);
            if(isset($arrJson)){
                $key  = array_search($doc_id, array_column($arrJson, 'doc_id'));
                if($key !== false){
                    $path =  public_path().'/uploads/waterpotability/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['doc_json'] = json_encode($arrJson);
                    $this->_howaterpotability->updateData($healthCertid,$data);
                    echo "deleted";
                }
            }
        }
    }

}
