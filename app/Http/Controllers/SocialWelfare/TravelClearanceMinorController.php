<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\TravelClearanceMinor;
use App\Models\SocialWelfare\Citizen;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;
use PDF;

class TravelClearanceMinorController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;

    public function __construct(){

        $this->_Citizen = new Citizen(); 
        $this->_TravelClearanceMinor = new TravelClearanceMinor(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'wtcm_date_interviewed'=>Carbon::now(),
            'wtcm_child_status'=>0,
            'wtcm_background_info'=>'',
            'wtcm_present_situation'=>'',
            'wtcm_travel_purpose'=>'',
            'wtcm_companion_name'=>'',
            'wtcm_companion_date_of_birth'=>'',
            'wtcm_relation_to_minor'=>'',
            'wtcm_companion_address'=>'',
            'wtcm_recommendation'=>'',
            'wtcm_prepared_by'=>'',
            'wtcm_reviewed_by'=>'',
            'wtcm_approved_by'=>'',
            'wtcm_cashier_id'=>'',
            'or_date'=>'',
            'or_amount'=>'',
            
            'wtcm_status' =>'',
            'wtcm_validity' =>'',
            'wtcm_minor_address' =>'',
            'wtcm_adoption_no' =>'',
            'wtcm_foster_liscense' =>'',
            'wtcm_foster_validity' =>'',
            'wtcm_father_cit_id' =>'',
            'wtcm_mother_cit_id' =>'',
            'wtcm_father_id_num' =>'',
            'wtcm_mother_id_num' =>'',
            'wtcm_companion_relation' =>'',
            'wtcm_companion_contact' =>'',
            'wtcm_sponsor' =>'',
            'wtcm_sponsor_relation' =>'',
            'wtcm_sponsor_contact' =>'',
            'wtcm_sponsor_age' =>'',
            'wtcm_sponsor_occupation' =>'',
            'wtcm_sponsor_address' =>'',
            'wtcm_destination' =>'',
            'wtcm_travel_from' =>'',
            'wtcm_travel_to' =>'',
            'wtcm_reason_cant_accompany' =>'',
        );  
        $this->slugs = 'social-welfare/travel-clearance-minor';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('SocialWelfare.TravelClearanceMinor.index');
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
        $or_num = [];

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_TravelClearanceMinor->find($request->input('id'));
            $or_num =  $data->getOrNumber();
        }

        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            // $this->_Citizen->updateData($request->input('cit_id'),$request->input('claimant'));
            if ($request->input('wtcm_father_cit_id')) {
                $this->_Citizen->updateData($request->input('wtcm_father_cit_id'),$request->input('father'));
            }
            if ($request->input('wtcm_mother_cit_id')) {
                $this->_Citizen->updateData($request->input('wtcm_mother_cit_id'),$request->input('mother'));
            }
            if ($request->input('wtcm_companion_name')) {
                $this->_Citizen->updateData($request->input('wtcm_companion_name'),$request->input('companion'));
            }
            $transaction = $this->_TravelClearanceMinor->getOrNumberId($this->data['wtcm_cashier_id']);
            if ($transaction){
                $this->data['wtcm_cashierd_id']= $transaction->wtcm_cashierd_id;
                $this->data['or_no']= $transaction->or_no;
            }
            if($request->input('id')>0){
                $this->_TravelClearanceMinor->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated "; 
                $logDetails['module_id'] =$request->id;
                $this->_TravelClearanceMinor->addRelation($request);
                $this->_commonmodel->updateLog($logDetails);
                return redirect()->back()->with('success', __($success_msg));
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_date'] = date('Y-m-d H:i:s');
                $this->data['wtcm_is_active'] = 1;
                $request->id = $this->_TravelClearanceMinor->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
                $logDetails['module_id'] =$request->id;
                $this->_TravelClearanceMinor->addRelation($request);
                $this->_commonmodel->updateLog($logDetails);
                return redirect()->route('tcm.index')->with('success', __($success_msg));
            }
        }
        $requirements = config('constants.tcmFileRequirements');
        $educ = config('constants.citEducationalAttainment');
        $civilstat = config('constants.citCivilStatus');
        return view('SocialWelfare.TravelClearanceMinor.create',compact('data', 'requirements','educ','civilstat','or_num'));

    }

    public function formValidation(Request $request){
        $rule = [
            // "cit_id" => "required|int",
            // "wtcm_child_status" => "required|int",
            "wtcm_prepared_by" => "required|int",
            "wtcm_validity" => "required",
            "wtcm_status" => "required",
            "minors" => "required|array",
            "wtcm_companion_name" => "required",
            // "require.*.file" => "required|file",
            // "require.*.file" => "required|file",
        ];
        if (!$request->id) {
            $rule = array_merge($rule, ["wtcm_cashier_id" => "nullable|unique:welfare_travel_clearance_minor,wtcm_cashier_id"]);
        }
        $validator = \Validator::make(
            $request->all(), $rule
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $fieldname = $messages->keys()[0];
            $arr['field_name'] = $fieldname;
            $title = $messages->all()[0];
            $arr['error'] = $title;
            if ($fieldname==='wtcm_cashier_id') {
                $arr['error'] = 'OR number already taken';
            }
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_TravelClearanceMinor->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-size="md" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage Travel Clearance" data-title="Manage Travel Clearance">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->wtcm_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Travel Clearance"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Travel Clearance"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['companion_name']=($row->companion)? $row->companion->cit_fullname : '';
            $arr[$i]['companion_add']=($row->companion)? $row->companion->cit_full_address : '';
            $arr[$i]['companion_relation']=$row->wtcm_relation_to_minor;
            $arr[$i]['companion_age']=($row->wtcm_companion_date_of_birth)?\Carbon\Carbon::parse($row->wtcm_companion_date_of_birth)->age:'';
            $arr[$i]['is_active']=($row->wtcm_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('wtcm_is_active' => $is_activeinactive);
        $this->_TravelClearanceMinor->updateData($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' TravelClearanceMinor ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }
    public function active(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $type = $request->input('type');
        $is_activeinactive = $request->input('is_activeinactive');
        $this->_TravelClearanceMinor->updateRelation($request);
    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' ".$type." ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        echo json_encode('success');
    }   
    public function approve(Request $request)
    {
        $this->is_permitted($this->slugs, 'approve');
        $id = $request->input('id');
        $transaction = $this->_TravelClearanceMinor->find($id);
        $approve = $transaction->approve();
        // $data = [
        //     'transaction_id' => $transaction->transaction->transaction_no
        // ];
        // Log Details Start
        $action = 'Approve';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' TravelClearanceMinor ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        echo json_encode(['success','approve_by'=>$approve]);
    }

    public function getTransactionDetails(Request $request)
    {
        $id = $request->input('id');
        $transaction = $this->_TravelClearanceMinor->getOrNumberId($id);

        echo json_encode($transaction);
        
    }

    public function print(Request $request, $id)
    {
        $data = TravelClearanceMinor::find($id);
        // PDF::setHeaderCallback(function($pdf) {
        //     // Set font
        //     $pdf->SetFont('helvetica', 'B', 20);
        //     // Title
        //     $pdf->writeHTML('<h1 style="text-align:center">DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT</h1>',true, false, false, false, 'center');
        
        // });
        PDF::SetTitle('Travel Clearance for '.$data->companion->cit_fullname.'');    
        PDF::SetMargins(20, 20, 20,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::setFooterCallback(function($pdf) {
            // Position at 15 mm from bottom
            $pdf->SetY(-20);
            // Set font
            $pdf->SetFont('helvetica', 'B', 8);
            // Page number
            $pdf->Cell(0, 0, 'PAGE '.$pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages(), 'B', true, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(0, 0, 'DSWD Field Office III, Government Center, Maimpis, City of San Fernando, Pampanga, 200 Philippines', 'T', true, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(0, 0, 'Website: www.fo3.dawd.gov.ph Tel Nos.: (045) 961-2143', 0, true, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->Rect(177, 310, 30,20, 'F', array(), array(255,255,255));
            $pdf->Image(public_path('/assets/images/department_logos/ISO-PAB-Logo.jpg'),180, 310, 0,13,'JPG','','L',false,300,'',false,false,0);
        });
        PDF::AddPage('P', 'FOLIO');

        PDF::SetFont('Helvetica', '', 8);
        PDF::Image(public_path('/assets/images/department_logos/DSWD-Logo.png'),20, 20, 35);
        PDF::Image(public_path('/assets/images/department_logos/DSWD-Logo2.png'),60, 20, 50);
        PDF::ln(16);
        PDF::Cell(0, 0, 'DSWD-PMB-GF-005 \ REV 01 / 30 SEPT 2022', 'B', true, 'L', 0, '', 0, false, 'T', 'M');
        PDF::ln();

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h3 style="text-align:right"><u>ANNEX A</u></h3>');
        PDF::writeHTML('<h3 style="text-align:CENTER">APPLICATION FORM</h3></br>');
        
        PDF::MultiCell(18, 0, '<b>Time in:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        $travel_alone =  $data->wtcm_status === 1? config('constants.checkbox.checked'):config('constants.checkbox.unchecked');
        $one_year_valid = $data->wtcm_validity === 1? config('constants.checkbox.checked'):config('constants.checkbox.unchecked');
        PDF::MultiCell(55, 0, $travel_alone.' Traveling Alone', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $one_year_valid.' 1 year validity', 0, 'L', 0, 1, '', '', true, 0, true);

        PDF::MultiCell(18, 0, '<b>Time out:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, ' ', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, ' ', 0, 'L', 0, 0, '', '', true, 0, true);
        $travel_with = $data->wtcm_status === 2? config('constants.checkbox.checked'):config('constants.checkbox.unchecked');
        $two_year_valid = $data->wtcm_validity === 2? config('constants.checkbox.checked'):config('constants.checkbox.unchecked');
        PDF::MultiCell(55, 0, $travel_with.' With Companion', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $two_year_valid.' 2 years validity', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::writeHTML('<ol type="I" start="1"><li><b>Minor&#39;s Profile</b></li></ol>');//section
        PDF::ln(5);
        $minors = '
        <table border="1" style="width:100%;padding:2;">
            <tr>
                <td width="130px"><b>Name</b></td>
                <td width="60px"><b>Age</b></td>
                <td width="40px"><b>Sex</b></td>
                <td width="60px"><b>Birth Status</b></td>
                <td width="70px"><b>Date of Birth</b></td>
                <td width="70px"><b>Place of Birth</b></td>
                <td width="70px"><b>Status of Application</b></td>
            </tr>';
        foreach ($data->minors as $value) {
            $minors .= '
            <tr>
                <td>'.$value->info->cit_fullname.'</td>
                <td>'.$value->info->age_human.'</td>
                <td>'.$value->info->gender.'</td>
                <td>'.$value->info->status().'</td>
                <td>'.Carbon::parse($value->info->cit_date_of_birth)->toFormattedDateString().'</td>
                <td>'.$value->info->cit_place_of_birth.'</td>
                <td></td>
            </tr>
            ';
        }
        $minors .= '</table>';
        PDF::writeHTML($minors,false, false, false, false, 'center');
        PDF::ln();

        PDF::MultiCell(32, 0, '<b>Minor/s&#39; Address:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->wtcm_minor_address, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('Helvetica', '', 11);

        PDF::writeHTML('<i>If issued with Certificate of Finality of Adoption or under Legal Guardianship, please indicate Special Proceeding No: <u>'.$data->wtcm_adoption_no.'</u></i>',false, false, false, false, 'center');
        PDF::ln(7);
        $foster = $data->wtcm_foster_validity ? $data->wtcm_foster_liscense.' - '.$data->wtcm_foster_validity : $data->wtcm_foster_liscense;
        PDF::writeHTML('<i>If under Foster Care Placement, please indicate the Foster Care License and validity period: <u>'.$foster.'</u></i>',false, false, false, false, 'center');
        PDF::ln(10);

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<ol type="I" start="2"><li><b>Parents:</b></li></ol>');//section
        PDF::ln(5);

        // father info
        $father_name = ($data->father) ? $data->father->cit_fullname : '';
        $father_age = ($data->father) ? $data->father->age : '';
        $father_occu = ($data->father) ? $data->father->cit_occupation : '';
        $father_id = ($data->father) ? $data->wtcm_father_id_num : '';
        $father_add = ($data->father) ? $data->father->cit_full_address : '';
        $father_mobile = ($data->father) ? $data->father->cit_mobile_no : '';
        PDF::MultiCell(15, 0, '<b>Father:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $father_name, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '<b>Age:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, $father_age, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(23, 0, '<b>Occupation:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, $father_occu, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(12, 0, '<b>ID no:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->wtcm_father_id_num, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        PDF::MultiCell(18, 0, '<b>Address:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(100, 0, $father_add, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(23, 0, '<b>Contact no:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $father_mobile, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        // mother info
        $mother_name = ($data->mother) ? $data->mother->cit_fullname : '';
        $mother_age = ($data->mother) ? $data->mother->age : '';
        $mother_occu = ($data->mother) ? $data->mother->cit_occupation : '';
        $mother_id = ($data->mother) ? $data->wtcm_mother_id_num : '';
        $mother_add = ($data->mother) ? $data->mother->cit_full_address : '';
        $mother_mobile = ($data->mother) ? $data->mother->cit_mobile_no : '';
        PDF::MultiCell(15, 0, '<b>Mother:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $mother_name, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '<b>Age:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, $mother_age, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(23, 0, '<b>Occupation:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 0, $mother_occu, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(12, 0, '<b>ID no:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->wtcm_mother_id_num, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        PDF::MultiCell(18, 0, '<b>Address:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(100, 0, $mother_add, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(23, 0, '<b>Contact no:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $mother_mobile, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::writeHTML('<ol type="I" start="3"><li><b>TRAVELING COMPANION</b> <i>(not applicable to Minors Traveling Alone)</i>:</li></ol>');//section
        PDF::ln(5);

        // companion info
        $companion_name = ($data->companion) ? $data->companion->cit_fullname : '';
        $companion_add = ($data->companion) ? $data->companion->cit_full_address : '';
        $companion_mobile = ($data->companion) ? $data->companion->cit_mobile_no : '';
        PDF::MultiCell(55, 0, '<b>Name of Traveling Companion:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $companion_name, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(18, 0, '<b>Address:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $companion_add, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        PDF::MultiCell(40, 0, '<b>Relationship to minor:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $data->wtcm_relation_to_minor, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '<b>Contact No.:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $companion_mobile, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        // sponsor info
        PDF::MultiCell(34, 0, '<b>Name of Sponsor:</b> ', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $data->wtcm_sponsor, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '<b>Age:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, $data->wtcm_sponsor_age, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(40, 0, '<b>Relationship to minor:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->wtcm_sponsor_relation, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        PDF::MultiCell(19, 0, '<b>Address:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(105, 0, $data->wtcm_sponsor_address, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(23, 0, '<b>Occupation:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->wtcm_sponsor_occupation, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(15);

        PDF::AddPage();

        PDF::writeHTML('<ol type="I" start="4"><li><b>DESTINATION:</b> </ol>');//section
        PDF::ln(5);

        PDF::MultiCell(23, 0, '<b>Destination:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, $data->wtcm_destination, 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(61, 0, '<b>Length of Travel </b><i>(Inclusive Dates):</i>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $data->wtcm_travel_from.' - '.$data->wtcm_travel_to, 'B', 'L', 0, 1, '', '', true, 0, true);
        PDF::ln(5);

        PDF::writeHTML('<b>Reason for Travel Abroad </b>(Reason/s for bringing the minor):');
        PDF::ln();
        PDF::writeHTML('<p style="text-indent: 30px">'.nl2br($data->wtcm_travel_purpose).'</p></br>',true, false, false, false, 'center');
        PDF::ln();

        PDF::writeHTML('<p><b style="text-indent: 0">Reasons why parents or legal guardian cannot accompany minor:</b></p>');
        PDF::ln();
        PDF::writeHTML('<p style="text-indent: 30px">'.nl2br($data->wtcm_reason_cant_accompany).'</p></br>',true, false, false, false, 'center');
        PDF::ln();

        PDF::writeHTML('<p><b style="text-indent: 0">Place where the minor intends to stay during his/her travel and with whom </b><i>(please indicate name, complete address and phone numbers):</i></p>');
        PDF::ln();
        $destinations = '';
        foreach ($data->destinations as $value) {
            $destinations .= $value->wtcmd_place.' - '.$value->wtcmd_companion.' - '.$value->wtcmd_address.' - '.$value->wtcmd_contactno.',';
        }
        PDF::writeHTML($destinations,true, false, false, false, 'center');
        PDF::ln();

        PDF::ln(10);
        PDF::writeHTML('I hereby certify that the information given above are true and correct. I further understand that any misrepresentation that I may have will subject me to criminal and civil action provided under existing laws.',$ln=true, $fill=false, $reseth=false, $cell=false, $align='J');
        PDF::ln(10);

        PDF::MultiCell(80, 0, '', 'B', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, '', 'B', 'C', 0, 1, '', '', true, 0, true);//printed name

        PDF::MultiCell(80, 0, 'Date', '', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Signature Over Printed Name', '', 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(10);

        PDF::writeHTML('<i><u>Note to Appplicant:</u></i>');
        PDF::ln();
        PDF::writeHTML('This Form with multiple entries should only for siblings with the same set of parents. Please fill up a separate application form for minors with a different set of parents.');
        PDF::ln();
        PDF::writeHTML('<hr></hr>');
        PDF::ln();
        PDF::writeHTML('<b>This portion is to be fllled up by the Social Worker</b>',$ln=true, $fill=false, $reseth=false, $cell=false, $align='C');
        PDF::ln(10);
        PDF::writeHTML('Remarks to Applicable Documents');
        PDF::ln();
        PDF::writeHTML('( &nbsp; ) Travel Clearance for Minors Traveling Abroad');
        PDF::writeHTML('( &nbsp; ) Certificate of Exemption');
        PDF::ln();

        $reviewed_by = $data->reviewed_by ? $data->reviewed_by->fullname : '';
        PDF::MultiCell(30, 0, '<b>Date Reviewed:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(10, 0, '', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 0, '<b>Reviewed by:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 0, $reviewed_by, 'B', 'L', 0, 1, '', '', true, 0, true);

        PDF::ln();
        PDF::MultiCell(26, 0, '<b>Designation:</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(50, 0, '', 'B', 'L', 0, 0, '', '', true, 0, true);

        PDF::Output('Travel Clearance Print_'.$data->companion->cit_fullname.'.pdf');
    }
}
