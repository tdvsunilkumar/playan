<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\SeniorCitizenID;
use App\Models\SocialWelfare\Citizen;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;
use PDF;
class SeniorCitizenIDController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;

    public function __construct(){

        $this->_SeniorCitizenID = new SeniorCitizenID(); 
        $this->_Citizen = new Citizen(); 
        $this->_commonmodel = new CommonModelmaster();
        $lastNum = $this->_SeniorCitizenID->next_number;
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'wsca_since_when'=>'',
            'wsca_previous_address'=>'',
            'wstor_id'=>'',
            'wsca_skill'=>'',
            'wsca_occupation'=>'',
            'wsca_monthly_income'=>'',
            'wsca_pension_amount'=>'',
            'wsca_name_of_spouse'=>'',
            'wsca_date_of_marriage'=>'',
            'wsca_place_of_marriage'=>'',
            'wsca_existing_senior'=>0,
            'wsca_is_renewal'=>'0',
            'wsca_existing_id'=>'',
            'wsca_existing_place_of_issue'=>'',
            'wsca_existing_date_of_issue'=>'',
            'wsca_remarks'=>'',
            'wsca_new_osca_id_no'=>Carbon::now()->format('y').'-'.$lastNum,
            'wsca_new_osca_id_no_date_issued'=>'',
            'wsca_fscap_id_no'=>'',
            'wsca_fscap_id_no_date_issued'=>'',
            'wsca_philhealth_no'=>'',
        );  
        $this->slugs = 'social-welfare/senior-citizen-id';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('SocialWelfare.SeniorCitizenID.index');
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_SeniorCitizenID->find($request->input('id'));
        }
        if($request->input('submit')!=""){
            unset($this->data['wswa_social_worker_name']);
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->_Citizen->updateData($request->input('cit_id'),$request->input('claimant'));
            if ($request->input('wsca_name_of_spouse')) {
                $this->_Citizen->updateData($request->input('wsca_name_of_spouse'),$request->input('spouse'));
                # code...
            }
            if($request->input('id')>0){
                $this->_SeniorCitizenID->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated "; 
                $logDetails['module_id'] =$request->id;
                $this->_SeniorCitizenID->addRelation($request);
                $this->_commonmodel->updateLog($logDetails);
                return redirect()->back()->with('success', __($success_msg));
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_date'] = date('Y-m-d H:i:s');
                $this->data['wsca_is_active'] = 1;
                $request->id = $this->_SeniorCitizenID->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
                $logDetails['module_id'] =$request->id;
                $this->_SeniorCitizenID->addRelation($request);
                $this->_commonmodel->updateLog($logDetails);
                return redirect()->route('senior.index')->with('success', __($success_msg));
            }
        }
        $family = $this->_SeniorCitizenID->familyCount();
        $data->family_count = $family;
        $associate = $this->_SeniorCitizenID->associateCount();
        $data->associate_count = $associate;
        $requirements = config('constants.scFileRequirements');
        $civilstat = config('constants.citCivilStatus');
        $educ = config('constants.citEducationalAttainment');
        $residencyType =  $this->_SeniorCitizenID->getAllResidenceType();
        return view('SocialWelfare.SeniorCitizenID.create',compact('data', 'requirements','residencyType','civilstat','educ'));

    }

    public function formValidation(Request $request){
        $startIdNum = (int)Carbon::now()->format('y');
        $rule = [
                "cit_id" => "required|int",
                "wsca_new_osca_id_no" => "required|regex:/^[0-9]{2}-[0-9]{4}/|max:7",
                // "require.*.file" => "required|file",
        ];
        $messages = [];
        if ($request->wsca_is_renewal == 1 && isset($request->old_id)) {
            $rule = array_merge($rule, [
                "osca_search" => "required|exists:welfare_seniors_citizen_application,wsca_new_osca_id_no|not_regex:/^".$startIdNum."/",
            ]);
            $messages = [
                'osca_search.not_regex' => "Cannot Renew if the id is just created recently"
            ];
        }
        if (!$request->id) {
            $rule = array_merge($rule, ["wsca_new_osca_id_no" => "required|unique:welfare_seniors_citizen_application,wsca_new_osca_id_no|regex:/^[0-9]{2}-[0-9]{4}/|max:7"]);
        }
        $validator = \Validator::make(
            $request->all(), $rule, $messages
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $fieldname = $messages->keys()[0];
            $arr['field_name'] = $fieldname;
            $title = $messages->all()[0];
            $arr['error'] = $title;
            $arr['ESTATUS'] = 1;
        }
        // if search id n save id not same
        if ($request->wsca_is_renewal == 1 && $request->osca_search) {
            $lnNew = $request->wsca_new_osca_id_no;
            $lnNew = (int)explode('-',$lnNew)[1];
            $lnSearch = $request->osca_search;
            $lnSearch = (int)explode('-',$lnSearch)[1];
            if ($lnNew != $lnSearch) {
                $arr['field_name'] = 'wsca_new_osca_id_no';
                $arr['error'] = 'Not same with Search Id';
                $arr['ESTATUS'] = 1;
            }
        }
        echo json_encode($arr);exit;
    }
    

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_SeniorCitizenID->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-size="md" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage Senior Citizen ID" data-title="Manage Senior Citizen ID">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->wsca_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Senior Citizen ID"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Senior Citizen ID"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['fullname']=$row->claimant->cit_fullname;
            $arr[$i]['address']=$row->claimant->cit_full_address;
            $arr[$i]['age']=\Carbon\Carbon::parse($row->claimant->cit_date_of_birth)->age;
    
            $arr[$i]['osca']=$row->wsca_new_osca_id_no;
            $arr[$i]['fscap']=$row->wsca_fscap_id_no;
            $arr[$i]['is_active']=($row->wsca_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('wsca_is_active' => $is_activeinactive);
        $this->_SeniorCitizenID->updateData($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' SeniorCitizenID ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }
    public function active(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $type = $request->input('type');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('wswa_is_active' => $is_activeinactive);
        $this->_SeniorCitizenID->updateRelation($request);
    
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
        $ID = SeniorCitizenID::with(['associate','files','family','claimant','spouse'])->where('wsca_new_osca_id_no',$id);
        $firstdata = SeniorCitizenID::find(1);
        if ($firstdata->wsca_remarks === 'Test Data') {
            $ID = $ID->where('id','!=',1);
        }
        $ID = $ID->orderBy('id','desc')->first();
        $ID->wsca_existing_id = $ID->wsca_new_osca_id_no;
        $ID->wsca_new_osca_id_no = Carbon::now()->format('y').'-'.substr($ID->wsca_new_osca_id_no, -4);
        $ID->wstor_name = isset($ID->residence->wstor_description)? $ID->residence->wstor_description:'Select Type';

        echo json_encode($ID);
    }
    public function print(Request $request, $id)
    {
        $data = SeniorCitizenID::find($id);
        PDF::SetTitle('Senior Citizen ID Form for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'FOLIO');
        PDF::SetFont('Helvetica', '', 10);
        $title_font = 8;
        $content_font = 9;
        
        // Header
        $top = 10;
        PDF::Image(public_path('/assets/images/department_logos/CSWD.png'),175, $top, 30, 30);
        PDF::Image(public_path('/assets/images/logo.png'),10, $top, 30, 30);
        PDF::MultiCell(0, 0, '<b>OFFICE OF THE SENIOR CITIZENS AFFAIR (OSCA)</b>', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Palayan City', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(3);
        PDF::MultiCell(0, 0, 'FEDERATION OF SENIOR CITIZENS ASSOCIATION OF THE PHILIPPINES', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 0, 'Palayan City Chapter', 0, 'C', 0, 1, '', '', true, 0, true);
        PDF::ln(5);
        PDF::MultiCell(0, 0, '<b>APPLICATION FORM</b>', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::ln(10);

        // Form
        // row 1
        PDF::SetLineStyle(array('width' => .4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        PDF::SetFont('Helvetica', 'B', $title_font);
        PDF::MultiCell(90, 5, 'Name', 'LT', 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 5, 'Age', 'LTR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, 'Date of Birth', 'LT', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Place of Birth', 'LTR', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('Helvetica', '', $content_font);
        PDF::MultiCell(30, 5, $data->claimant->cit_last_name, 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, $data->claimant->cit_first_name, 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, $data->claimant->cit_middle_name, 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 5, $data->claimant->age, 'LR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, $data->claimant->cit_date_of_birth, 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 5, $data->claimant->cit_place_of_birth, 'LR', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln();

        PDF::SetFont('Helvetica', 'B', $title_font);
        PDF::MultiCell(30, 5, 'Surname', 'LTB', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, 'First', 'TB', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, 'Middle Name', 'TBR', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(15, 5, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(30, 5, '', 'L', 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 5, '', 'LR', 'C', 0, 0, '', '', true, 0, true);
        PDF::ln();

        // row 2
        $table_info = '
        <table border="0" style="width:100%;padding:2; ">
            <tr>
                <td border="1" rowspan="2"             style="text-align:center;"><b>Current<br>Address</b></td>
                <td border="1"             colspan="4" style="height:40px"></td>
                <td border="1" rowspan="2"             style="text-align:center;">Since When</td>
                <td border="1" rowspan="3" colspan="3" >
                    Current Type of Residency (Check Box)<br>';
        $residencyType =  $this->_SeniorCitizenID->getAllResidenceType();
        foreach ($residencyType as $key => $value) {
            $check =($key === $data->wstor_id) ? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
            $table_info .= '&nbsp;&nbsp;&nbsp;'.$check.' '.$value.'<br>';
        }
        
        $table_info .= '<div style="font-size:1pt">&nbsp;</div></td>
            </tr>
            <tr>
                <td border="0"><b>No.</b></td>
                <td border="0" colspan="2" style="text-align:center;"><b>Street</b></td>
                <td border="0"><b>Barangay</b></td>
                <td border="0"></td>
            </tr>
            <tr>
                <td border="1" colspan="2"><b>Tel./Cell No.</b></td>
                <td border="1" colspan="4" style="height:40px"><b>Previous Address</b></td>
            </tr>
            <tr>
                <td border="1" rowspan="2" style="text-align:center;"><b>Civil Status</b></td>
                <td border="1" rowspan="2" style="text-align:center;"><b>Sex</b></td>
                <td border="1" rowspan="2" style="text-align:center;"><b>Height</b></td>
                <td border="1" rowspan="2" style="text-align:center;"><b>Weight</b></td>
                <td border="1" colspan="2" style="text-align:center;height:30px"><b>Educational Attainment</b></td>
                <td border="1" colspan="2" style="text-align:center;"><b>Occupation</b></td>
                <td border="1"  style="text-align:center;"><b>Pension</b></td>
            </tr>
            
            <tr>
                <td border="1" colspan="2" style="height:30px"><b>Skill</b></td>
                <td border="1" colspan="2" style="text-align:center;"><b>Monthly Income</b></td>
                <td border="1" style="text-align:center;"><b>Amount</b></td>
            </tr>
            
        </table>
        <table border="1" style="width:100%;padding:2; ">
            <tr>
                <td style="height:30px">Date of Marriage</td>
                <td>Place of Marriage</td>
                <td>Name of Spouse</td>
                <td>Birthdate of Spouse</td>
            </tr>
        </table>
        ';
        PDF::SetFont('Helvetica', 'B', $title_font);
        PDF::writeHTML($table_info,true, false, false, false, 'center');

        PDF::SetFont('Helvetica', '', $content_font);
        $b = 0;
        PDF::writeHTMLCell(25, 0, 40, 69,$data->claimant->cit_house_lot_no, $b, 0, 0, false, 'L', false); //house No
        PDF::writeHTMLCell(23, 0, 66, 69,$data->claimant->cit_street_name, $b, 0, 0, true, 'L', false); //Street
        PDF::writeHTMLCell(28, 0, 90, 69,$data->claimant->brgy->brgy_name, $b, 0, 0, true, 'L', false); //Brgy
        PDF::writeHTMLCell(17, 0, 119, 73,$data->wsca_since_when, $b, 0, 0, true, 'C', false); //Since when
        PDF::writeHTMLCell(25, 0, 25, 90,$data->claimant->cit_telephone_no, $b, 0, 0, true, 'L', false); //Tel
        PDF::writeHTMLCell(77, 0, 60, 88,$data->wsca_previous_address, $b, 0, 0, true, 'L', false); //Previous address
        
        PDF::writeHTMLCell(19, 0, 20, 105,$data->claimant->status(), $b, 0, 0, true, 'C', false); //Civil Status
        PDF::writeHTMLCell(19, 0, 40, 105,$data->claimant->gender, $b, 0, 0, true, 'C', false); //Gender
        PDF::writeHTMLCell(19, 0, 59, 105,$data->claimant->cit_height, $b, 0, 0, true, 'C', false); //Height
        PDF::writeHTMLCell(19, 0, 79, 105,$data->claimant->cit_weight, $b, 0, 0, true, 'C', false); //Weight
        
        PDF::writeHTMLCell(35, 0, 100, 104,$data->claimant->education(), $b, 0, 0, true, 'C', false); //Educ
        PDF::writeHTMLCell(35, 0, 140, 104,$data->wsca_occupation, $b, 0, 0, true, 'C', false); //Occupation
        PDF::writeHTMLCell(18, 0, 177, 104,number_format($data->wsca_pension_amount,2), $b, 0, 0, true, 'C', false); //Pension

        PDF::writeHTMLCell(35, 0, 100, 114,$data->wsca_skill, $b, 0, 0, true, 'C', false); //Skill
        PDF::writeHTMLCell(35, 0, 140, 114,number_format($data->wsca_monthly_income,2), $b, 0, 0, true, 'C', false); //Income
        PDF::writeHTMLCell(18, 0, 177, 114,'', $b, 0, 0, true, 'C', false); //Amount
        

        // 
        
        PDF::writeHTMLCell(35, 0, 25, 125,$data->wsca_date_of_marriage, $b, 0, 0, true, 'L', false); //Date of marriage
        PDF::writeHTMLCell(38, 0, 68, 125,$data->wsca_place_of_marriage, $b, 0, 0, true, 'L', false); //Place of marriage
        PDF::writeHTMLCell(40, 0, 110, 125,($data->spouse)?$data->spouse->cit_fullname:'', $b, 0, 0, true, 'L', false); //Name of spouse
        PDF::writeHTMLCell(40, 0, 155, 125,($data->spouse)?$data->spouse->cit_date_of_birth:'', $b, 0, 0, true, 'L', false); //bday of spouse
        PDF::ln(10);

        // family composition
        $table = '
        <table border="1" style="width:100%;padding:2;text-align:center;">
            <tr>
                <td colspan="5"><b>FAMILY COMPOSITION</b></td>
            </tr>
            <tr>
                <th><b>NAME</b></th>
                <th><b>RELATIONSHIP</b></th>
                <th><b>AGE</b></th>
                <th><b>CIVIL STATUS</b></th>
                <th colspan="2"><b>OCCUPATION / INCOME</b></th>
            </tr>';
        foreach ($data->family as $value) {
            $income = ($value->wsfc_monthly_income)? ' - '.number_format($value->wsfc_monthly_income,2) : '';
            $table .= '
            <tr>
                <td>'.$value->info->cit_fullname.'</td>
                <td>'.$value->wsfc_relation.'</td>
                <td>'.$value->info->age.'</td>
                <td>'.$value->info->status().'</td>
                <td>'.$value->wsfc_occupation.$income.'</td>
            </tr>
            ';
        }
        $table .= '</table>';
        PDF::writeHTML($table,false, false, false, false, 'center');
        PDF::ln();

        // Association
        $table = '
        <table border="1" style="width:100%;padding:2;text-align:center;">
            <tr>
                <td colspan="3"><b>MEMBERSHIP IN OTHER SENIOR CITIZENS ASSOCIATION</b></td>
            </tr>
            <tr>
                <th><b>NAME OF ASSOCIATION</b></th>
                <th><b>ADDRESS</b></th>
                <th><b>POSITION (IF OFFICER, DATE ELECTED)</b></th>
            </tr>';
        foreach ($data->associate as $value) {
            $table .= '
            <tr>
                <td>'.$value->wsa_association_name.'</td>
                <td>'.$value->wsa_assocation_address.'</td>
                <td>'.$value->wsa_association_position.'</td>
            </tr>
            ';
        }
        $table .= '</table>';
        PDF::writeHTML($table,false, false, false, false, 'center');
        PDF::ln();

        // old id?
        $yes_id = $data->wsca_existing_senior === 1? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $no_id = $data->wsca_existing_senior === 0? config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
        $issed_id = 'Have you been issued a senior citizens ID card before?  '.$yes_id.'  Yes   '.$no_id.'  No';
        PDF::MultiCell(0,0,$issed_id,0,'C',0,1,'','',true,0,true);

        if ($data->wsca_existing_senior) {
            PDF::MultiCell(65,0,'ID NO. <b>'.$data->wsca_existing_id.'</b>',0,'L',0,0,'','',true,0,true);
            PDF::MultiCell(65,0,'Place of Issue: <b>'.$data->wsca_existing_place_of_issue.'</b>',0,'L',0,0,'','',true,0,true);
            PDF::MultiCell(65,0,'Date of Issue: <b>'.$data->wsca_existing_date_of_issue.'</b>',0,'L',0,1,'','',true,0,true);
        }
        PDF::ln();

        // notes
        $note = '
        <p><b>QUALFIGATIONS:</b></p>
        <ol>
            <li>60 Years pld and above</li>
            <li>Filipino Citizen</li>
            <li>Registered Voter and an actual resident of Palayan City</li>
        </ol>
        <p><b>REQUIREMENTS:</b></p>
        <ol>
            <li>BARANGAY CLEARANCE</li>
            <li>COMELEC ID and/or Certificate of registration as voter of Palayan City</li>
            <li>Any of the following:
                <ol>
                    <li>Birth Certificate</li>
                    <li>Passport</li>
                    <li>Marriage Contract</li>
                    <li>SSS / GSIS / any ID with date of birth</li>
                </ol>
            </li>
            <li>Three (3 pcs) 1x1 Photos with white background</li>
        </ol>
        ';
        PDF::writeHTMLCell(70,85,'','',$note,1,0);
        PDF::MultiCell(0,0,'I CERTIFY that the above informations are true and correct to the best of my knowledge and belief','TR','C');
        PDF::writeHTMLCell(70,0,'','','',0,0);//space
        PDF::writeHTMLCell(40,0,'','','',0,0);//photo
        PDF::MultiCell(0,0,'<div style="font-size:30pt">&nbsp;</div><hr width="165">Thumb mark/ Signature of Senior Citizen','R','C',0,1,'','',true,0,true);
        PDF::writeHTMLCell(70,25,'','','',0,0);//space
        PDF::writeHTMLCell(40,0,'','','',0,0);//photo
        PDF::MultiCell(0,0,'<div style="font-size:5pt">&nbsp;</div>Date of Registration: '.Carbon::parse($data->created_date)->toDateString().'<div style="font-size:5pt">&nbsp;</div>','R','L',0,1,'','',true,0,true);
        PDF::writeHTMLCell(70,25,'','','',0,0);//space
        PDF::writeHTMLCell(40,0,'','','',0,0);//photo
        PDF::MultiCell(0,0,'<b>REVIEWED/ CHECKED AND NOTED BY:</b> <div style="font-size:20pt">&nbsp;</div>',1,'L',0,1,'','',true,0,true);
        PDF::writeHTMLCell(70,25,'','','',0,0);//space
        PDF::writeHTMLCell(40,0,'','','',0,0);//photo
        PDF::MultiCell(0,0,'Printed Name/ Signature of Unit President <div style="font-size:10pt">&nbsp;</div> Date: _____________________ <div style="font-size:5pt">&nbsp;</div>',1,'C',0,1,'','',true,0,true);
        PDF::writeHTMLCell(70,25,'','','',0,0);//space
        PDF::writeHTMLCell(0,18.7,'','','Remarks: '.$data->wswa_remarks,1,0);//remarks
        PDF::ln(22);

        // id information
        PDF::MultiCell(65,0,'OSCA I.D. NO. <b>'.$data->wsca_new_osca_id_no.'</b>',0,'L',0,0,'','',true,0,true);
        PDF::MultiCell(65,0,'FSCAP I.D. NO. <b>'.$data->wsca_fscap_id_no.'</b>',0,'L',0,0,'','',true,0,true);
        PDF::MultiCell(65,0,' ',0,'L',0,1,'','',true,0,true);

        PDF::MultiCell(65,0,'Date Issued: <b>'.$data->wsca_new_osca_id_no_date_issued.'</b>',0,'L',0,0,'','',true,0,true);
        PDF::MultiCell(65,0,'Date Issued: <b>'.$data->wsca_fscap_id_no_date_issued.'</b>',0,'L',0,0,'','',true,0,true);
        PDF::MultiCell(65,0,'Phil Health No: <b>'.$data->wsca_philhealth_no.'</b>',0,'L',0,1,'','',true,0,true);
        PDF::Output('Senior_ID_Form_'.$data->claimant->cit_fullname.'.pdf');
    }

}
