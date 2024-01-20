<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Mpdf\Mpdf as PDF;
use File;
use App\Models\HrEmployee;
use App\Models\HoMedicalCertificate;
use App\Models\UrinalysisModel;
use App\Models\FecalysisModel;
use App\Models\SerologyModel;
use App\Models\CommonModelmaster;
use App\Models\HealthSafetySetupDataService;
use Carbon\Carbon;
use App\Models\HoLabRequest;

class HealthSafetyMpdfController extends Controller
{
	public function __construct(){
        $this->_SerologyModel = new SerologyModel(); 
        $this->_commonmodel = new CommonModelmaster();
    }
    public function medcert(Request $request, $id){
        $mpdf  = new PDF( [
            'mode' => 'utf-8',
            'format' => 'Folio',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $html = file_get_contents(resource_path('views/health-and-safety/prints/medicalcert.html')); 

        //data
        $data = HoMedicalCertificate::find($id);
        $new_date = Carbon::parse($data->med_cert_date)->format('F d, Y');
        $html = str_replace('{{logo}}',public_path('/assets/images/issuanceLogo.png'), $html);
        $html = str_replace('{{officer_name}}',strtoupper($data->officer->fullname), $html);
        $html = str_replace('{{position}}',strtoupper($data->med_officer_position), $html);
        $html = str_replace('{{liscense}}',$data->officer->identification_no, $html);
        $html = str_replace('{{name}}',$data->patient->cit_fullname, $html);
        $html = str_replace('{{age}}',$data->patient->age_human, $html);
        $html = str_replace('{{brgy}}',ucwords($data->patient->brgy->brgy_name), $html);
        $html = str_replace('{{date}}',$new_date, $html);

        $mpdf->WriteHTML($html);
        $mpdf->Output();
        // echo ;
    }

    public function urinalysis(Request $request, $id){
        $mpdf  = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
        // $mpdf  = new PDF( ['format' => 'A4' ]);  
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;
        // $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $html = file_get_contents(resource_path('views/health-and-safety/prints/urinalysis.html')); 

        //data
        $data = UrinalysisModel::find($id);
        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";
        // dd($lab_request->req_phys);
        $new_date2 = Carbon::parse($data->urin_date)->format('F d, Y');
        $html = str_replace('{{logo}}',public_path('/assets/images/issuanceLogo.png'), $html);
        $html = str_replace('{{watermark}}',public_path('/assets/images/cho-lab.png'), $html);
        $html = str_replace('{{physician}}',$lab_request->req_phys, $html);
        $html = str_replace('{{urin_lab_num}}',$data->urin_lab_num, $html);
        $html = str_replace('{{date}}',$new_date2, $html);
        $html = str_replace('{{name}}',strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name, $html);
        $html = str_replace('{{age}}',$data->patient->age_human, $html);
        $html = str_replace('{{gender}}',$data->patient->gender[0], $html);

        $html = str_replace('{{urin_color}}',$data->urin_color, $html);
        $html = str_replace('{{urin_appearance}}',$data->urin_appearance, $html);
        $html = str_replace('{{urin_leukocytes}}',$data->urin_leukocytes, $html);
        $html = str_replace('{{urin_nitrite}}',$data->urin_nitrite, $html);
        $html = str_replace('{{urin_urobilinogen}}',$data->urin_urobilinogen, $html);
        $html = str_replace('{{urin_protein}}',$data->urin_protein, $html);
        $html = str_replace('{{urin_reaction}}',$data->urin_reaction, $html);
        $html = str_replace('{{urin_blood}}',$data->urin_blood, $html);
        $html = str_replace('{{urin_sg}}',$data->urin_sg, $html);
        $html = str_replace('{{urin_ketones}}',$data->urin_ketones, $html);
        $html = str_replace('{{urin_bilirubin}}',$data->urin_bilirubin, $html);
        $html = str_replace('{{urin_glucose}}',$data->urin_glucose, $html);
        $html = str_replace('{{urin_rbc}}',$data->urin_rbc, $html);
        $html = str_replace('{{urin_pc}}',$data->urin_pc, $html);
        $html = str_replace('{{urin_bac}}',$data->urin_bac, $html);
        $html = str_replace('{{urin_yc}}',$data->urin_yc, $html);
        $html = str_replace('{{urin_ec}}',$data->urin_ec, $html);
        $html = str_replace('{{urin_mt}}',$data->urin_mt, $html);
        $html = str_replace('{{urin_aup}}',$data->urin_aup, $html);
        $html = str_replace('{{urin_others1}}',$data->urin_others1, $html);
        $html = str_replace('{{urin_cast}}',$data->urin_cast, $html);
        $html = str_replace('{{urin_others2}}',$data->urin_others2, $html);
        $html = str_replace('{{urin_remarks}}',$data->urin_remarks, $html);

        $html = str_replace('{{medical_tech}}',($data->m_tech) ? strtoupper($data->m_tech->fullname) : " ", $html);
        $html = str_replace('{{officer}}',($data->h_officer) ? strtoupper($data->h_officer->fullname) : " ", $html);
        $html = str_replace('{{med_tech_position}}',($data->med_tech_position) ? $data->med_tech_position : " ", $html);
        $html = str_replace('{{officer_position}}',($data->health_officer_position) ? $data->health_officer_position : " ", $html);

        //$mpdf->WriteHTML($html);        
        //$mpdf->Output();
        // echo ;
		$filename="";
        $mpdf->WriteHTML($html);
		
		$m_techId = HrEmployee::where('id', $data->m_tech->id)->first();
		$h_officerId = HrEmployee::where('id', $data->h_officer->id)->first();
		$filename = $data->id."-urinalysis.pdf";
        
		$arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_urinalysis_approved_medical_technologist');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_urinalysis_approved_health_officer');
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

        $varifiedSignature = $this->_commonmodel->getuserSignature($m_techId->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2 && $data->esign_is_approved ==1){
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

        $certifiedSignature = $this->_commonmodel->getuserSignature($h_officerId->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2 && $data->officer_is_approved==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->esign_is_approved ==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)){
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");
    }

    public function fecalysis(Request $request, $id){
        $mpdf  = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;
        // $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $html = file_get_contents(resource_path('views/health-and-safety/prints/fecalysis.html')); 
        
        //data
        $data = FecalysisModel::find($id);
        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";
        $new_date3 = Carbon::parse($data->fec_date)->format('F d, Y');
        $html = str_replace('{{logo}}',public_path('/assets/images/issuanceLogo.png'), $html);
        $html = str_replace('{{watermark}}',public_path('/assets/images/cho-lab.png'), $html);
        $html = str_replace('{{physician}}',$lab_request->req_phys, $html);
        $html = str_replace('{{fec_lab_num}}',$data->fec_lab_num, $html);
        $html = str_replace('{{date}}',$new_date3, $html);
        $html = str_replace('{{name}}',strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name, $html);
        $html = str_replace('{{age}}',$data->patient->age_human, $html);
        $html = str_replace('{{gender}}',$data->patient->gender[0], $html);

        $html = str_replace('{{fec_color}}',$data->fec_color, $html);
        $html = str_replace('{{fec_consistency}}',$data->fec_consistency, $html);
        $html = str_replace('{{fec_rbc}}',$data->fec_rbc, $html);
        $html = str_replace('{{fec_wbc}}',$data->fec_wbc, $html);
        $html = str_replace('{{fec_bacteria}}',$data->fec_bacteria, $html);
        $html = str_replace('{{fec_fat_glob}}',$data->fec_fat_glob, $html);
        $html = str_replace('{{fec_parasite}}',$data->fec_parasite, $html);
        $html = str_replace('{{fec_others}}',$data->fec_others, $html);

        $html = str_replace('{{medical_tech}}',($data->m_tech) ? strtoupper($data->m_tech->fullname) : " ", $html);
        $html = str_replace('{{officer}}',($data->h_officer) ? strtoupper($data->h_officer->fullname) : " ", $html);
        $html = str_replace('{{med_tech_position}}',($data->med_tech_position) ? $data->med_tech_position : " ", $html);
        $html = str_replace('{{officer_position}}',($data->health_officer_position) ? $data->health_officer_position : " ", $html);

        //$mpdf->WriteHTML($html);
        //$mpdf->Output();
		$filename="";
        $mpdf->WriteHTML($html);
		
		$m_techId = HrEmployee::where('id', $data->m_tech->id)->first();
		$h_officerId = HrEmployee::where('id', $data->h_officer->id)->first();
		$filename = $data->id."-fecalysis.pdf";
        
		$arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_fecalysis_approved_medical_technologist');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_fecalysis_approved_health_officer');
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

        $varifiedSignature = $this->_commonmodel->getuserSignature($m_techId->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2 && $data->esign_is_approved==1){
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

        $certifiedSignature = $this->_commonmodel->getuserSignature($h_officerId->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2 && $data->officer_is_approved==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->esign_is_approved==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)){
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");
    }

    public function serology(Request $request, $id){
        $mpdf  = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);     
        $mpdf->watermarkImgBehind = true;
        $mpdf->showWatermarkImage = true;
        // $mpdf->SetDisplayMode('fullpage');
        $mpdf->debug = true;
        $mpdf->showImageErrors = true;
        $mpdf->text_input_as_HTML = true;
        $html = file_get_contents(resource_path('views/health-and-safety/prints/serology.html')); 

        //data
        $data = SerologyModel::find($id);
        $lab_request = HoLabRequest::where('lab_control_no',$data->lab_control_no)->first();
        $last_name = ($lab_request->patient->cit_last_name) ? $lab_request->patient->cit_last_name : "";
        $first_name = ($lab_request->patient->cit_first_name) ? $lab_request->patient->cit_first_name : "";
        $middle_name = ($lab_request->patient->cit_middle_name) ? $lab_request->patient->cit_middle_name : "";
        $suffix_name = ($lab_request->patient->cit_suffix_name) ? ', '.$lab_request->patient->cit_suffix_name : "";
        
        $new_date1 = Carbon::parse($data->ser_date)->format('F d, Y');
        $html = str_replace('{{logo}}',public_path('/assets/images/issuanceLogo.png'), $html);
        $html = str_replace('{{watermark}}',public_path('/assets/images/cho-lab.png'), $html);
        $html = str_replace('{{physician}}',$lab_request->req_phys, $html);
        $html = str_replace('{{ser_lab_num}}',$data->ser_lab_num, $html);
        $html = str_replace('{{date}}',$new_date1, $html);
        $html = str_replace('{{name}}',strtoupper($last_name).', '.$first_name.' '.$middle_name.$suffix_name, $html);
        $html = str_replace('{{age}}',$data->patient->age_human, $html);
        $html = str_replace('{{gender}}',$data->patient->gender[0], $html);
        $html = str_replace('{{ser_remarks}}',$data->ser_remarks, $html);
        // $hepa_id = HealthSafetySetupDataService::where('ho_service_name','HBsAg Screening')->first()->id;
        // $hiv_id = HealthSafetySetupDataService::where('ho_service_name','HIV Screening Test')->first()->id;
        // $syphilis_id = HealthSafetySetupDataService::where('ho_service_name','Syphilis Screening')->first()->id;
        // if ($data->checkAvail($hepa_id)) {
        //     $html = str_replace('{{hepa_check}}', '/', $html);
        //     $html = str_replace('{{hepa_specimen}}',$data->dataField($hepa_id,'ser_specimen'), $html);
        //     $html = str_replace('{{hepa_brand}}',$data->dataField($hepa_id,'ser_brand'), $html);
        //     $html = str_replace('{{hepa_lot}}',$data->dataField($hepa_id,'ser_lot'), $html);
        //     $html = str_replace('{{hepa_exp}}',$data->dataField($hepa_id,'ser_exp'), $html);
        //     $html = str_replace('{{hepa_result}}',($data->dataField($hepa_id,'ser_result') === 1)?'Reactive':'Non-Reactive', $html);
        // } else {
        //     $html = str_replace('{{hepa_check}}', '', $html);
        //     $html = str_replace('{{hepa_specimen}}','', $html);
        //     $html = str_replace('{{hepa_brand}}','', $html);
        //     $html = str_replace('{{hepa_lot}}','', $html);
        //     $html = str_replace('{{hepa_exp}}','', $html);
        //     $html = str_replace('{{hepa_result}}','', $html);
        // }

        // if ($data->checkAvail($hiv_id)) {
        //     $html = str_replace('{{hiv_check}}', '/', $html);
        //     $html = str_replace('{{hiv_specimen}}',$data->dataField($hiv_id,'ser_specimen'), $html);
        //     $html = str_replace('{{hiv_brand}}',$data->dataField($hiv_id,'ser_brand'), $html);
        //     $html = str_replace('{{hiv_lot}}',$data->dataField($hiv_id,'ser_lot'), $html);
        //     $html = str_replace('{{hiv_exp}}',$data->dataField($hiv_id,'ser_exp'), $html);
        //     $html = str_replace('{{hiv_result}}',($data->dataField($hiv_id,'ser_result') === 1)?'Reactive':'Non-Reactive', $html);
        // } else {
        //     $html = str_replace('{{hiv_check}}', '', $html);
        //     $html = str_replace('{{hiv_specimen}}','', $html);
        //     $html = str_replace('{{hiv_brand}}','', $html);
        //     $html = str_replace('{{hiv_lot}}','', $html);
        //     $html = str_replace('{{hiv_exp}}','', $html);
        //     $html = str_replace('{{hiv_result}}','', $html);
        // }

        // if ($data->checkAvail($syphilis_id)) {
        //     $html = str_replace('{{syphilis_check}}', '/', $html);
        //     $html = str_replace('{{rpr_qualitative}}', ($data->checkMethod($syphilis_id,'RPR Qualitative Method'))?'/':'&nbsp;', $html);
        //     $html = str_replace('{{rpr_quantitative}}', ($data->checkMethod($syphilis_id,'RPR Quantitative Method'))?'/':'&nbsp;', $html);
        //     $html = str_replace('{{immonochromatography}}', ($data->checkMethod($syphilis_id,'Immunochromatography'))?'/':'&nbsp;', $html);
        //     $html = str_replace('{{syphilis_specimen}}',$data->dataField($syphilis_id,'ser_specimen'), $html);
        //     $html = str_replace('{{syphilis_brand}}',$data->dataField($syphilis_id,'ser_brand'), $html);
        //     $html = str_replace('{{syphilis_lot}}',$data->dataField($syphilis_id,'ser_lot'), $html);
        //     $html = str_replace('{{syphilis_exp}}',$data->dataField($syphilis_id,'ser_exp'), $html);
        //     $html = str_replace('{{syphilis_result}}',($data->dataField($syphilis_id,'ser_result') === 1)?'Reactive':'Non-Reactive', $html);
        // } else {
        //     $html = str_replace('{{rpr_qualitative}}', '&nbsp;', $html);
        //     $html = str_replace('{{rpr_quantitative}}', '&nbsp;', $html);
        //     $html = str_replace('{{immonochromatography}}', '&nbsp;', $html);
        //     $html = str_replace('{{syphilis_check}}', '', $html);
        //     $html = str_replace('{{syphilis_specimen}}','', $html);
        //     $html = str_replace('{{syphilis_brand}}','', $html);
        //     $html = str_replace('{{syphilis_lot}}','', $html);
        //     $html = str_replace('{{syphilis_exp}}','', $html);
        //     $html = str_replace('{{syphilis_result}}','', $html);
        // }
        $row = '';
        foreach ($data->details as $value) {
            // dd($value->service->ho_service_name);
            $result = $value->ser_specimen === 1 ?'Reactive':'Non-Reactive';
            $method = '';
            if ($value->sm_id != 0) {
                $method = '('.$value->method->ser_m_method.')';
            }
            $row .= '<tr>
                <td style="text-align:letf; ">
                    <strong>'.$value->service->ho_service_name.'</strong>
                    <p>(Immunochromatography)</p>
                </td>
                <td style="text-align:center; font-weight:bold;">'.$value->ser_specimen.'</td>
                <td style="text-align:center; font-weight:bold;">'.$value->ser_brand.'</td>
                <td style="text-align:center; font-weight:bold;">'.$value->ser_lot.'</td>
                <td style="text-align:center; font-weight:bold;">'.$value->ser_exp.'</td>
                <td style="text-align:center; font-weight:bold;">'.$result.'</td>
            </tr>';
            $html = str_replace('{{'.$value->service->ho_service_name.'}}', '/', $html);
        }
        $forms = SerologyModel::allService();
        foreach ($forms as $value) {
            $html = str_replace('{{'.$value->ho_service_name.'}}', '', $html);
        }
        // dd($row);

            $html = str_replace('{{test_results}}',$row, $html);
        // $html = str_replace('{{test_results}}','', $html);

        // $html = str_replace('{{hiv_check}}',$data->dataField($hepa_id,'ser_specimen'), $html);

        $html = str_replace('{{medical_tech}}',($data->m_tech) ? strtoupper($data->m_tech->fullname) : " ", $html);
        $html = str_replace('{{officer}}',($data->h_officer) ? strtoupper($data->h_officer->fullname) : " ", $html);
        $html = str_replace('{{med_tech_position}}',($data->med_tech_position) ? $data->med_tech_position : " ", $html);
        $html = str_replace('{{officer_position}}',($data->health_officer_position) ? $data->health_officer_position : " ", $html);
		$filename="";
        $mpdf->WriteHTML($html);
		
		$m_techId = HrEmployee::where('id', $data->m_tech->id)->first();
		$h_officerId = HrEmployee::where('id', $data->h_officer->id)->first();
		$filename = $data->id."-serology.pdf";
        
		$arrSign= $this->_commonmodel->isSignApply('health_safety_laboratory_request_serology_approved_medical_technologist');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;

        $arrCertified= $this->_commonmodel->isSignApply('health_safety_laboratory_request_serology_approved_health_officer');
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

        $varifiedSignature = $this->_commonmodel->getuserSignature($m_techId->user_id);
        $varifiedPath =  public_path().'/uploads/e-signature/'.$varifiedSignature;

        if($isSignVeified==1 && $signType==2 && $data->esign_is_approved == 1){
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

        $certifiedSignature = $this->_commonmodel->getuserSignature($h_officerId->user_id);
        $certifiedPath =  public_path().'/uploads/e-signature/'.$certifiedSignature;

        if($isSignCertified==1 && $signType==2 && $data->officer_is_approved==1){
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $arrData['isSavePdf'] = ($arrData['isSavePdf']==1)?0:1;
                $arrData['signerXyPage'] = $arrCertified->pos_x.','.$arrCertified->pos_y.','.$arrCertified->pos_x_end.','.$arrCertified->pos_y_end.','.$arrCertified->d_page_no;
                $arrData['isDisplayPdf'] = 1;
                $arrData['signaturePath'] = $certifiedSignature;
                return $this->_commonmodel->applyDigitalSignature($arrData);
            }
        }

        if($isSignCertified==1 && $signType==1 && $data->officer_is_approved==1){
            // Apply E-sign Here
            if(!empty($certifiedSignature) && File::exists($certifiedPath)){
                $mpdf->Image($certifiedPath,$arrCertified->esign_pos_x, $arrCertified->esign_pos_y, $arrCertified->esign_resolution);
            }
        }
        if($isSignVeified==1 && $signType==1 && $data->esign_is_approved ==1){
            // Apply E-sign Here
            if(!empty($varifiedSignature) && File::exists($varifiedPath)){
                $mpdf->Image($varifiedPath,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
            }
        }
        if($signType==2){
            if(File::exists($folder.$filename)){
                File::delete($folder.$filename);
            }
        }
        $mpdf->Output($filename,"I");
        //$mpdf->Output();
        // $filename="";
        // $permitfilename = $id.$filename."serology-results.pdf";
        // $folder =  public_path().'/uploads/healthsafety/serology/';
        // if(!File::exists($folder)) { 
        //     File::makeDirectory($folder, 0755, true, true);
        // }
        // $filename = public_path() . "/uploads/healthsafety/serology/" . $permitfilename;
        // $mpdf->Output($filename, "F");
        // @chmod($filename, 0777);
        // return redirect(url('/uploads/healthsafety/serology/' . $permitfilename));
        // echo ;
    }
}
