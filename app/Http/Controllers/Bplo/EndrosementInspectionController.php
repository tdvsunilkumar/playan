<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\Endrosement;
use App\Models\BfpApplicationForm;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
class EndrosementInspectionController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrprofile = array(""=>"Select Owner");
    public $yeararr = array(""=>"Select Year");
    public $arrBarangay = array(""=>"Please Select");
    public $accountnos = array();
    public $nofbusscode = array(""=>"Select Code");
    public $arrOcupancy = array(""=>"Please Select");
    private $slugs;
    
    public $endrlDeptDtls = [];
    public function __construct(){
        $this->_Endrosement = new Endrosement(); 
        $this->_bfpapplicationform = new BfpApplicationForm();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busns_id_no'=>'','busn_name'=>'','arrAssesment'=>array(),'totalSurcharges'=>'0','totalInterest'=>'0','end_fee_name'=>'','end_tfoc_id'=>'','enddept_fee'=>'','bbendo_id'=>'','document_details'=>'','document_detailsInspection'=>'','bplo_documents'=>array(),'bend_status'=>'','inspection_report_attachment'=>'','bend_year'=>'');
        $this->slugs = 'fire-protection/endorsement';
        
    }

    public function firePrint(Request $request){
		$id= $request->input('id');
		// $data = $this->_bfpinspectionorder->getPrintDetails($id);
		 
		// if(count($data)>0){
		//     $data = $data[0];
		// }
		
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->debug = true;
		$mpdf->showImageErrors = true;
		$mpdf->text_input_as_HTML = true;
		$filename="";
		$html = file_get_contents(resource_path('views/layouts/templates/inspectioncertificateFire.html'));
		$logo = url('/assets/images/logo.png');
		$signeture = url('/assets/images/signeture2.png');
		$html = str_replace('{{LOGO}}',$logo, $html);
		
		$mpdf->WriteHTML($html);
		$applicantname = "inspectioncertificateFire.pdf";
		$folder =  public_path().'/uploads/certoflandholdingprint/';
		// if(!File::exists($folder)) { 
		//     File::makeDirectory($folder, 0777, true, true);
		// }
		$filename = public_path() . "/uploads/certoflandholdingprint/" . $applicantname;
		$mpdf->Output($filename, "F");
		// @chmod($filename,  0777);
		echo url('/uploads/certoflandholdingprint/' . $applicantname);
    }
   
   
   
    public function application(Request $request){
        $this->is_permitted($this->slugs, 'update');
        $bbendo_id =  $request->input('bbendo_id');
        $year =  $request->input('year');
        $data = (object)$this->data;
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_Endrosement->getEditDetails($request->input('id'),$bbendo_id,$year);
            $arrdocDtls = $this->generateDocumentListInspection($data->inspection_report_attachment,$data->bbendo_id,$data->bend_status);
            if(isset($arrdocDtls)){
                $data->document_detailsInspection = $arrdocDtls;
            }
        }
        return view('Bplo.endorsement.inspectionApplication',compact('data'));
    }

    
    
    public function uploadAttachmentInspection(Request $request){
        $busn_id =  $request->input('busn_id');
        $year =  $request->input('year');
        $bbendo_id =  $request->input('bbendo_id');
        $arrEndrosment = $this->_Endrosement->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
        $message='';
        $ESTATUS=0;
        $arrDocumentList='';
        // if(isset($arrEndrosment)){
        //     $arrJson = (array)json_decode($arrEndrosment->inspection_report_attachment,true);
            
        // }
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
                    $arrJson = json_decode($arrEndrosment->inspection_report_attachment,true);
                    if(isset($arrJson)){
                        $arrJson[] = $arrData;
                        $finalJsone = $arrJson;
                    }
                }
                $data['inspection_report_attachment'] = json_encode($finalJsone);
                $this->_Endrosement->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                $arrDocumentList = $this->generateDocumentListInspection($data['inspection_report_attachment'],$bbendo_id);
            }
        }
        $arr['ESTATUS']=$ESTATUS;
        $arr['message']=$message;
        $arr['documentList']=$arrDocumentList;
        echo json_encode($arr);exit;
    }
    

    
    public function generateDocumentListInspection($arrJson,$bbendo_id, $bend_status=''){
        $html = "";
        $dclass = ($bend_status==2 || $bend_status==3)?'disabled-status':'';
        if(isset($arrJson)){
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $html .= "<tr>
                  <td>".$val['filename']." </td>
                  <td><a class='btn' href='".asset('uploads/document_requirement').'/'.$val['filename']."' target='_blank'><i class='ti-download'></i></a></td>
                   <td>
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
        $arrEndrosment = $this->_Endrosement->getBusinessEndorsementDetailsInspection($busn_id,$bbendo_id,$year);
        if(isset($arrEndrosment)){
            $arrJson = json_decode($arrEndrosment->inspection_report_attachment,true);
            if(isset($arrJson)){
                $key  = array_search($rid, array_column($arrJson, 'filename'));
                if($key !== false){
                    $path =  public_path().'/uploads/document_requirement/'.$arrJson[$key]['filename'];
                    if(File::exists($path)) { 
                        unlink($path);

                    }
                    unset($arrJson[$key]);
                    array_splice($arrJson,100);
                    $data['inspection_report_attachment'] = json_encode($arrJson);
                    $this->_Endrosement->updateBusinessEndorsement($busn_id,$bbendo_id,$data,$year);
                    echo "deleted";
                }
            }
        }
    }
}
    