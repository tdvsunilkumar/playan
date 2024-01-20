<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\PWD;
use App\Models\SocialWelfare\Citizen;
use App\Models\SocialWelfare\CauseDisability;
use App\Models\SocialWelfare\CauseDisabilityAquire;
use App\Models\SocialWelfare\TypeOccupation;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;
use PDF;

class PWDController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;

    public function __construct(){

        $this->_Citizen = new Citizen(); 
        $this->_PWD = new PWD(); 
        $this->_commonmodel = new CommonModelmaster();
        
        // might rework
        $this->_defaultRegion = $this->_PWD->default_region;
        $this->_defaultProvince = $this->_PWD->default_province;
        $this->_defaultMunicipality = $this->_PWD->default_Municipal;
        // 
        $this->_PWDID = $this->_defaultRegion.'-'.$this->_defaultProvince.$this->_defaultMunicipality.'-';
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'wpaf_application_type'=>0,
            'wptoo_id'=>'',
            'wpaf_pwd_id_number'=> '',
            'wpaf_date_applied'=>Carbon::now(),
            'wptod_id'=>'',
            'pwd_cause_type'=>'',
            'wpcodi_id'=>'',
            'wpcoda_id'=>'',
            'wpaf_brgy_id'=>'',
            'wpaf_municipal'=>'',
            'wpaf_province'=>'',
            'wpaf_region'=>'',
            'wpsoe_id'=>'',
            'wpcoe_id'=>'',
            'wptoe_id'=>'',
            'wpaf_sss'=>'',
            'wpaf_gsis'=>'',
            'wpaf_pagibig'=>'',
            'wpaf_psn'=>'',
            'wpaf_philhealth'=>'',
            'wpaf_fathersname'=>'',
            'wpaf_mothersname'=>'',
            'wpaf_guardiansname'=>'',
            'wpaf_accomplished_type'=>'',
            'wpaf_accomplished_by'=>'',
            'wpaf_physician'=>'',
            'wpaf_physician_license'=>'',
            'wpaf_processing_officer'=>'',
            'wpaf_approving_officer'=>'',
            'wpaf_encoder'=>'',
            'wpaf_reporting_unit'=>'',
            'wpaf_control_no'=>'',
            'loc_local_code'=>$this->_PWD->locality()->locality->loc_local_code,
            'barangay_uacs_code'=>'',
            'barangay_pwd_no'=>$this->_PWD->next_number,
        );  
        $this->slugs = 'social-welfare/pwd-id';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('SocialWelfare.PWD.index');
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_PWD->find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $claimant = array_merge($request->input('claimant'),[
                'brgy_id' => $this->data['wpaf_brgy_id'],
                'cit_sss_no' => $this->data['wpaf_sss'],
                'cit_gsis_no' => $this->data['wpaf_gsis'],
                'cit_pagibig_no' => $this->data['wpaf_pagibig'],
                'cit_psn_no' => $this->data['wpaf_psn'],
                'cit_philhealth_no' => $this->data['wpaf_philhealth'],
            ]);
            // dd($claimant);
            $this->_Citizen->updateData($request->input('cit_id'),$claimant);
            if($request->input('id')>0){
                $this->_PWD->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_date'] = date('Y-m-d H:i:s');
                $this->data['wpaf_is_active'] = 1;
                $request->id = $this->_PWD->addData($this->data);
                $success_msg = 'Added successfully.';
            }
            $this->_PWD->addRelation($request);
            return redirect()->route('pwd.index')->with('success', __($success_msg));
        }
        $requirements = config('constants.pwdFileRequirements');
        $educ = config('constants.citEducationalAttainment');
        $civilstat = config('constants.citCivilStatus');
        $employStatus = PWD::getEmployStatus();
        $typeDisability = PWD::getDisability();
        $employCategory = PWD::getEmployCategory();
        $employType = PWD::getEmployType();
        $occupation = PWD::getOccupation();
        $causeInborn = PWD::getCauseInborn();
        $causeAcquire = PWD::getCauseAcquire();
        $barangays = Citizen::getBrgyMunicipalOnly();
        $associate = $this->_PWD->associateCount();
        $data->associate_count = $associate;
        
        return view('SocialWelfare.PWD.create',compact('data', 'requirements','educ','employStatus','civilstat','typeDisability','barangays','employCategory','employType','occupation','causeInborn','causeAcquire'));

    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                "cit_id" => "required",
                "pwd_cause_type" => "required",
                "wpaf_pwd_id_number" => "required",
                "wpaf_processing_officer" => "required",
                "wpaf_accomplished_type" => "required",
                "wpaf_accomplished_by" => "required",
                "old_date_applied" => "nullable|date|before:".Carbon::parse($request->wpaf_date_applied)->subYear(1),
                // "require.*.file" => "required|file",
            ],[
                'old_date_applied.before'=>'Cannot Renew if the id is just created recently'
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $fieldname = $messages->keys()[0];
            $arr['field_name'] = $fieldname;
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_PWD->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="Manage PWD ID" data-title="Manage PWD ID">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->wpaf_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove PWD ID"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore PWD ID"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['fullname']=$row->claimant->cit_fullname;
            $arr[$i]['address']=$row->claimant->cit_full_address;
            $arr[$i]['type']=$row->apply_type;
            $arr[$i]['number']=$row->wpaf_pwd_id_number;
            $arr[$i]['disability']=$row->wptod_description;
            $arr[$i]['age']=\Carbon\Carbon::parse($row->claimant->cit_date_of_birth)->age;
    
            $arr[$i]['is_active']=($row->wpaf_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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

    public function ActiveInactive(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('wpaf_is_active' => $is_activeinactive);
        $this->_PWD->updateData($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' PWD ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }
    public function active(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $type = $request->input('type');
        $is_activeinactive = $request->input('is_activeinactive');
        $this->_PWD->updateRelation($request);
    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' ".$type." ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        echo json_encode('success');
    }

    public function getLastID(Request $request)
    {
        $id = $request->input('id');
        $ID = PWD::with(['associate','files','claimant','mother','father','guardian'])->where('wpaf_pwd_id_number',$id)->orderBy('id','desc')->first();
        $ID->select = $ID->select_name;
        $ID->wpaf_processing_officer = null;
        $ID->wpaf_encoder = null;
        $ID->wpaf_approving_officer = null;

        echo json_encode($ID);
    }

    public function getBrgyDetails(Request $request)
    {
        $id = $request->input('brgy');
        $pwdId = $request->input('id');
        $brgy = $this->_PWD->getBrgyData($id);
        $iastnum = $this->_PWD->next_number;
        if ($pwdId) {
            $iastnum = $this->_PWD->find($pwdId)->barangay_pwd_no;
        }
        $local_code = $this->_PWD->locality()->locality->loc_local_code;
        $data = [
            'region'=>$brgy->region->reg_region,
            'province'=>$brgy->province->prov_desc,
            'municipality'=>$brgy->municipality->mun_desc,
            
            'region_id'=>$brgy->region->id,
            'province_id'=>$brgy->province->id,
            'municipality_id'=>$brgy->municipality->id,

            'pwd_id' => $this->_PWDID.$brgy->uacs_code.'-'.$iastnum,
            'loc_local_code' => $local_code,
            'barangay_uacs_code' => $brgy->uacs_code,
            'barangay_pwd_no' => $iastnum,
        ];
        echo json_encode($data);
    }

    public function print(Request $request, $id)
    {
        $data = PWD::find($id);
        PDF::SetTitle('PWD ID Form for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'LEGAL');
        PDF::SetFont('Helvetica', '', 10);
        $title_font = 8;
        $content_font = 7;

        // Header
        $top = 10;
        PDF::Image(public_path('assets/images/department_logos/DOH.png'),25, $top, 25, 25);
        PDF::MultiCell(0, 0, '<b>DEPARTMENT OF HEALTH</b>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Philippine Registry For Persons with Disabilities Version 4.0 ', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(3);
        PDF::MultiCell(0, 0, '<b>Application Form</b>', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(16);

        // Form
        // row 1
        $height2 = 9;
        PDF::SetLineStyle(array('width' => .4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        PDF::SetFont('Helvetica', '', $content_font);
        PDF::setCellPaddings(1,1);
        $newapplicant = ($data->wpaf_application_type === 0)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $renewal = ($data->wpaf_application_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::MultiCell(145, $height2, '1. &nbsp; &nbsp; &nbsp; '.$newapplicant.'  <b>NEW APPLICANT</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$renewal.'  <b>RENEWAL</b> * ', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, $height2, '<i>Place 1"x1" <br> Photo Here</i>', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(100, $height2, '2. <b>PERSONS WITH DISABILITY NUMBER [RR-PPMM-BBB-NNNNNNN] *</b> <br> '.$data->wpaf_pwd_id_number, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(45, $height2, '3. <b>DATE APPLIED: *</b>(mm/dd/yyyy) <br> '.Carbon::parse($data->wpaf_date_applied)->format('m/d/Y'), 1, 'L', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(145, 0, '4. <b>PERSONAL INFORMATION *</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(36.25, $height2, '<b>LAST NAME: *</b><br> '.$data->claimant->cit_last_name, 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(36.25, $height2, '<b>FIRST NAME: *</b><br> '.$data->claimant->cit_first_name, 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(36.25, $height2, '<b>MIDDLE NAME: *</b><br> '.$data->claimant->cit_middle_name, 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(36.25, $height2, '<b>SUFFIX: *</b><br> '.$data->claimant->cit_suffix_name, 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(90, $height2, '5. <b>DATE OF BIRTH: *</b>(mm/dd/yyyy) &nbsp; '.Carbon::parse($data->claimant->cit_date_of_birth)->format('m/d/Y'), 1, 'L', 0, 0, '', '', true, 0, true);
        $sex = config('constants.citGender');
        $gender = '';
        foreach ($sex as $key => $value) {
            $check = ($key === (int)$data->claimant->cit_gender)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $gender .= ''.$check.'  '.$value .' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';
        }
        PDF::MultiCell(55, $height2, '6. <b>SEX: *</b><br> &nbsp; &nbsp; &nbsp; '.$gender, 1, 'L', 0, 1, '', '', true, 0, true);

        $civil = config('constants.citCivilStatus');
        $civilstat = '';
        foreach ($civil as $key => $value) {
            $check = ($key === $data->claimant->ccs_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $civilstat .= ''.$check.' '.$value .' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';
        }
        PDF::MultiCell(0, $height2, '7. <b>CIVIL STATUS: *</b><br> &nbsp; &nbsp; &nbsp; '.$civilstat, 1, 'L', 0, 1, '', '', true, 0, true);

        //one row for Disability
        PDF::MultiCell(88, 0, '8. <b>TYPE OF DISABILITY</b>', 'LR', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '9. <b>CAUSE OF DISABILITY</b>', 'LR', 'L', 0, 1, '', '', true, 0, true);

        $typeDisability = PWD::getDisability();
        $typeDisability = array_chunk($typeDisability,2,true);
        $disability = '<table>';
        foreach ($typeDisability as $key => $value) {
            $disability .= '<tr>';
            foreach ($value as $id => $val) {
                $check = ($key === (int)$data->wptod_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
                $disability .= '<td> '.$check.' '.$val.'</td>';
            }
            $disability .= '</tr>';
        }
        $disability .= '</table><br>';
        PDF::MultiCell(88, 20, $disability, 'LR', 'L', 0, 0, '', '', true, 0, true);

        $causeInbornList = ['ADHD','Cerebral Palsy','Down Syndrome'];
        $causeInborn = CauseDisability::whereIn('wpcodi_description',$causeInbornList)->get();
        $causeAquireList = ['Chronic Illness','Cerebral Palsy','Injury'];
        $causeAcquire = CauseDisabilityAquire::whereIn('wpcoda_description',$causeAquireList)->get();
        $inborn = ($data->pwd_cause_type === 0)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $aquire = ($data->pwd_cause_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $cause = '<table><tr>';
        $cause .= '<td> '.$inborn.'  <b>Congenital / Inborn</b><br>';

        foreach ($causeInborn as $key => $value) {
            $check = ($value->id === (int)$data->wpcodi_id && $data->pwd_cause_type === 0)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $cause .= ' &nbsp; &nbsp; &nbsp; '.$check.'  '.$value->wpcodi_description.'<br>';
        }
        $others = CauseDisability::whereNotIn('wpcodi_description',$causeInbornList)->where('id',$data->wpcodi_id)->first();
        $check = config('constants.checkbox.unchecked');
        $other_name = '';
        if ($others) {
            $check = config('constants.checkbox.checked');
            $other_name = $others->wpcodi_description;
        }
        $cause .= ' &nbsp; &nbsp; &nbsp; '.$check.'  Others, Specify: <b>'.$other_name.'</b><br>';

        $cause .= '</td>';
        $cause .= '<td> &nbsp; '.$aquire.'  <b>Acquired</b><br>';

        foreach ($causeAcquire as $key => $value) {
            $check = ($value->id === (int)$data->wpcoda_id && $data->pwd_cause_type === 1)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $cause .= ' &nbsp; &nbsp; &nbsp; '.$check.'  '.$value->wpcoda_description.'<br>';
        }
        $others = CauseDisabilityAquire::whereNotIn('wpcoda_description',$causeAquireList)->where('id',$data->wpcoda_id)->first();
        $check = config('constants.checkbox.unchecked');
        $other_name = '';
        if ($others) {
            $check = config('constants.checkbox.checked');
            $other_name = $others->wpcoda_description;
        }
        $cause .= ' &nbsp; &nbsp; &nbsp; '.$check.'  Others, Specify: <b>'.$other_name.'</b><br>';

        $cause .= '</td>';
        $cause .= '</tr></table>';

        PDF::MultiCell(0, 0, $cause, 'LR', 'L', 0, 1, '', '', true, 0, true);
        // one row disability end

        PDF::MultiCell(0, 0, '10. <b>RESIDENCE ADDRESS *</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(35.19, $height2, '<b>House no. and Streets: *</b><br> '.$data->claimant->cit_house_lot_no.' '.$data->claimant->cit_street_name, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35.19, $height2, '<b>Barangay: *</b><br> '.$data->claimant->brgy->brgy_name, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35.19, $height2, '<b>Municipality: *</b><br> '.$data->claimant->brgy->municipality->mun_desc, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35.19, $height2, '<b>Province: *</b><br> '.$data->claimant->brgy->province->prov_desc, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, $height2, '<b>Region: *</b><br> '.$data->claimant->brgy->region->reg_region, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '11. <b>CONTACT DETAILS</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(58.6, $height2, '<b>Landline No.: *</b><br> '.$data->claimant->cit_telephone_no, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(58.6, $height2, '<b>Mobile No.: *</b><br> '.$data->claimant->cit_mobile_no, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, $height2, '<b>Email Address: *</b><br> '.$data->claimant->cit_email_address, 1, 'L', 0, 1, '', '', true, 0, true);

        // ROW START employment
        $typeDisability = config('constants.citEducationalAttainment');
        $typeDisability = array_chunk($typeDisability,2,true);
        $disability = '<table>';
        foreach ($typeDisability as $key => $value) {
            $disability .= '<tr>';
            foreach ($value as $id => $val) {
                $check = ($key === (int)$data->wptod_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
                $disability .= '<td> '.$check.'  '.$val.'</td>';
            }
            $disability .= '</tr>';
        }
        $disability .= '</table><br>';
        PDF::MultiCell(117.2, $height2, '12. <b>EDUCATIONAL ATTAINMENT: *</b><br>'.$disability, 1, 'L', 0, 1, '', '', true, 0, true);
        $employ = '
        <table border="1" style="padding:3px">
            <tr>
                <td> 13. <b>STATUS OF EMPLOYMENT: *</b><br>';
        $employStatus = PWD::getEmployStatus();
        foreach ($employStatus as $key => $value) {
            $check = ($key === (int)$data->wpsoe_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $employ .= ' '.$check.'  '.$value.'<br>';
        }
        $employ .= '</td>
                <td rowspan="2">13 b. <b>TYPES OF EMPLOYMENT: *</b><br>';
                $employType = PWD::getEmployType();
                foreach ($employType as $key => $value) {
                    $check = ($key === (int)$data->wptoe_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
                    $employ .= ' '.$check.'  '.$value.'<br>';
                }
        $employ .= '</td>
            </tr>
            <tr>
                <td>13 a. <b>CATEGORY OF EMPLOYMENT: *</b><br>';
                $employCategory = PWD::getEmployCategory();
                foreach ($employCategory as $key => $value) {
                    $check = ($key === (int)$data->wpcoe_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
                    $employ .= ' '.$check.'  '.$value.'<br>';
                }
        $employ .= '</td>
            </tr>
        </table>
        ';
        PDF::setCellPaddings(0,0,0);
        PDF::MultiCell(117.2, 0, $employ, 1, 'L', 0, 1, '', '', true, 0, true);
        PDF::setCellPaddings(1,1);
        // ROW END


        PDF::MultiCell(0, 0, '15. <b>ORGANITION INFORMATION</b>', 1, 'L', 0, 1, '', '', true, 0, true);
        $org ='
        <table border="1" style="padding:3px">
            <tr>
                <td><b>Organization Affliated:</b></td>
                <td><b>Contact Person:</b></td>
                <td><b>Office Address:</b></td>
                <td><b>Tel No.:</b></td>
            </tr>';
            foreach ($data->associate as $key => $value) {
                $key = $key+1;
                $org .='
                    <tr>
                        <td>'.$key.'. '.$value->wpo_organization.'</td>
                        <td>'.$value->wpo_contact_person.'</td>
                        <td>'.$value->wpo_office_address.'</td>
                        <td>'.$value->wpo_contact_number.'</td>
                    </tr>
                ';
            }
        $org .='</table>';
        PDF::setCellPaddings(0,0,0);
        PDF::MultiCell(0, 0, $org, 0, 'L', 0, 1, '', '', true, 0, true,false);

        PDF::setCellPaddings(1,1);
        PDF::MultiCell(0, 0, '16. <b>ID REFERENCE NO.:</b>', 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(35.15, $height2, '<b>SSS NO.: *</b><br> '.$data->wpaf_sss, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35.15, $height2, '<b>GSIS NO.: *</b><br> '.$data->wpaf_gsis, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35.15, $height2, '<b>PAGIBIG NO.: *</b><br> '.$data->wpaf_pagibig, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(35.15, $height2, '<b>PSN NO.: *</b><br> '.$data->wpaf_psn, 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, $height2, '<b>PHILHEALTH NO.: *</b><br> '.$data->wpaf_philhealth, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, '17. <b>FAMILY BACKGROUND:</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, '<b>LAST NAME: </b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, '<b>FIRST NAME: </b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>MIDDLE NAME: </b>', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, "FATHER'S NAME: &nbsp; ", 1, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->father)? $data->father->cit_last_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->father)? $data->father->cit_first_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->father)? $data->father->cit_middle_name:'', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, "MOTHER'S NAME: &nbsp; ", 1, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->mother)? $data->mother->cit_last_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->mother)? $data->mother->cit_first_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->mother)? $data->mother->cit_middle_name:'', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, "GUARDIAN'S NAME: &nbsp; ", 1, 'R', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->guardian)? $data->guardian->cit_last_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->guardian)? $data->guardian->cit_first_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->guardian)? $data->guardian->cit_middle_name:'', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, '18. <b>ACCOMPLISHED BY: *</b>', 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, '<b>LAST NAME: </b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, '<b>FIRST NAME: </b>', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '<b>MIDDLE NAME: </b>', 1, 'C', 0, 1, '', '', true, 0, true);

        $applicant = ($data->wpaf_accomplished_type === '0')? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::MultiCell(44, 0, " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ".$applicant."  APPLICANT", 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->wpaf_accomplished_type === '0')? $data->claimant->cit_last_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->wpaf_accomplished_type === '0')? $data->claimant->cit_first_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->wpaf_accomplished_type === '0')? $data->claimant->cit_middle_name:'', 1, 'C', 0, 1, '', '', true, 0, true);

        $guardian = ($data->wpaf_accomplished_type === '1')? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::MultiCell(44, 0, " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ".$guardian."  GUARDIAN", 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->wpaf_accomplished_type === '1')? $data->guardian->cit_last_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->wpaf_accomplished_type === '1')? $data->guardian->cit_first_name:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->wpaf_accomplished_type === '1')? $data->guardian->cit_middle_name:'', 1, 'C', 0, 1, '', '', true, 0, true);

        $representative = ($data->wpaf_accomplished_type === '2')? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        PDF::MultiCell(44, 0, " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ".$representative."  REPRESENTATIVE", 'L', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->wpaf_accomplished_type === '2')? $data->wpaf_accomplished_by:'', 1, 'C', 0, 1, '', '', true, 0, true);
        
        PDF::MultiCell(44, $height2, '19. <b>NAME OF CERTIFYING PHYSICIAN:</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(88, $height2, $data->wpaf_physician, 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, $height2, '<b>LICENSE NO.: </b><br>'.$data->wpaf_physician_license, 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, '20. <b>PROCESSING OFFICER: *</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, $data->processing->lastname, 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, $data->processing->firstname, 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->processing->middlename, 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, '21. <b>APPROVING OFFICER: *</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->approver)?$data->approver->lastname:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->approver)?$data->approver->firstname:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->approver)?$data->approver->middlename:'', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(44, 0, '22. <b>ENCODER: *</b>', 1, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->encoder)?$data->encoder->lastname:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(44, 0, ($data->encoder)?$data->encoder->firstname:'', 1, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, ($data->encoder)?$data->encoder->middlename:'', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '23. <b>NAME OF REPORTING UNIT (OFFICE/SECTION): *</b> &nbsp; '.$data->wpaf_reporting_unit, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(0, 0, '24. <b>CONTROL NO.: *</b> &nbsp; '.$data->wpaf_control_no, 1, 'L', 0, 1, '', '', true, 0, true);

        PDF::ln(16);
        PDF::MultiCell(130, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Revised as of August 1, 2021', 1, 'C', 0, 1, '', '', true, 0, true);

        PDF::writeHTMLCell(35.9, 40, 160,50,'', 'R', 0, 0, true, 'C', false);

        $occupationList = ['Managers','Professionals','Technicians and Associate Professionals','Celerical Support Workers','Service and Sales Workers','Skilled Agricultural, Forestry and Fishery Workers','Craft and Related Trade Workers','Plant And Machine Operators and Assemblers','Elementary Occupations','Armed Forces Occupations'];
        $occupation = TypeOccupation::whereIn('wptoo_description',$occupationList)->get();
        $employ = '';
        foreach ($occupation as $key => $value) {
            $check = ($value->id === (int)$data->wptoo_id)? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $employ .= ' '.$check.'  '.$value->wptoo_description.'<br>';
        }
        $others = TypeOccupation::whereNotIn('wptoo_description',$occupationList)->where('id',$data->wptoo_id)->first();
        $check =  config('constants.checkbox.unchecked');
        $other_name = '';
        if ($others) {
            $check =  config('constants.checkbox.checked');
            $other_name = $others->wptoo_description;
        }
        $employ .= ' '.$check.'  Others, Specify: <b>'.$other_name.'</b><br>';
        PDF::MultiCell(58.6, 100, '14. <b>OCCUPATION: *</b><br>'.$employ, 'R', 'L', 0, 1, 137.3,142.45, true, 0, true);


        PDF::Output('PWD_ID_Form_'.$data->claimant->cit_fullname.'.pdf');
    }

}
