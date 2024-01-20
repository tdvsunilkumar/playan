<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\SoloParentID;
use App\Models\SocialWelfare\Citizen;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth;
use PDF;

class SoloParentIDController extends Controller
{
    public $data = [];
    public $postdata = [];
    private $slugs;

    public function __construct(){

        $this->_Citizen = new Citizen(); 
        $this->_SoloParentID = new SoloParentID(); 
        $this->_commonmodel = new CommonModelmaster();
        $lastNum = $this->_SoloParentID->next_number;
        $this->data = array(
            'id'=>'',
            'cit_id'=>'',
            'wspa_occupation'=>'',
            'wspa_monthly_income'=>'',
            'wspa_total_income'=>'',
            'wspa_classification'=>'',
            'wspa_is_renewal'=>'0',
            'wspa_id_number'=>Carbon::now()->format('y').'-'.$lastNum,
            'wspa_needs_problem'=>'',
            'wspa_family_resources'=>'',
        );  
        $this->slugs = 'social-welfare/solo-parent-id';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('SocialWelfare.SoloParentID.index');
    }
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_SoloParentID->find($request->input('id'));
        }
        if($request->input('submit')!=""){
            
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->_Citizen->updateData($request->input('cit_id'),$request->input('claimant'));
            if($request->input('id')>0){
                $this->_SoloParentID->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated "; 
                $logDetails['module_id'] =$request->id;
                $this->_SoloParentID->addRelation($request);
                $this->_commonmodel->updateLog($logDetails);
                return redirect()->back()->with('success', __($success_msg));
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_date'] = date('Y-m-d H:i:s');
                $this->data['wspa_is_active'] = 1;
                $request->id = $this->_SoloParentID->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added "; 
                $logDetails['module_id'] =$request->id;
                $this->_SoloParentID->addRelation($request);
                $this->_commonmodel->updateLog($logDetails);
                return redirect()->route('soloparent.index')->with('success', __($success_msg));
            }
        }
        $family = $this->_SoloParentID->familyCount();
        $data->family_count = $family;
        $requirements = config('constants.spFileRequirements');
        $educ = config('constants.citEducationalAttainment');
        $civilstat = config('constants.citCivilStatus');
        return view('SocialWelfare.SoloParentID.create',compact('data','civilstat', 'requirements','educ'));

    }

    public function formValidation(Request $request){
        $startIdNum = (int)Carbon::now()->format('y');
        $rule = [
                "cit_id" => "required|int",
                "wspa_id_number" => "required|regex:/^[0-9]{2}-[0-9]{4}/|max:7",
                // "require.*.file" => "required|file",
        ];
        $messages = [];
        // dd($startIdNum,  $request->id_search);
    //     dd(preg_match('/^'.$startIdNum.'/',$request->id_search,$matches)
    // ,'/^'.$startIdNum.'/', $matches);
        if ($request->wspa_is_renewal == 1 && isset($request->old_id)) {
            $rule = array_merge($rule, 
            [
                "id_search" => "required|exists:welfare_solo_parent_application,wspa_id_number|not_regex:/^".$startIdNum."/",
            ]);
            $messages = [
                'id_search.not_regex' => "no no Cannot Renew if the id is just created recently"
            ];
        }
        if (!$request->id) {
            $rule = array_merge($rule, ["wspa_id_number" => "required|unique:welfare_solo_parent_application,wspa_id_number|regex:/^[0-9]{2}-[0-9]{4}/|max:7"]);
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
            $title = explode(' ', $title);
            $title = array_slice($title,2);
            $arr['error'] = ucfirst(implode(' ', $title));
            $arr['ESTATUS'] = 1;
        }
        // if search id n save id not same
        if ($request->wspa_is_renewal == 1 && $request->id_search) {
            $lnNew = $request->wspa_id_number;
            $lnNew = (int)explode('-',$lnNew)[1];
            $lnSearch = $request->id_search;
            $lnSearch = (int)explode('-',$lnSearch)[1];
            if ($lnNew != $lnSearch) {
                $arr['field_name'] = 'id_search';
                $arr['error'] = 'Not same with Search Id';
                $arr['ESTATUS'] = 1;
            }
        }
        echo json_encode($arr);exit;
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_SoloParentID->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-size="md" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage Solo Parent ID" data-title="Manage Solo Parent ID">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->wspa_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Solo Parent ID"></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.' data-bs-toggle="tooltip" title="Restore Solo Parent ID"></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['fullname']=$row->claimant->cit_fullname;
            $arr[$i]['address']=$row->claimant->cit_full_address;
            $arr[$i]['age']=\Carbon\Carbon::parse($row->claimant->cit_date_of_birth)->age;
            $arr[$i]['id_number']=$row->wspa_id_number;
    
            $arr[$i]['is_active']=($row->wspa_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $data=array('wspa_is_active' => $is_activeinactive);
        $this->_SoloParentID->updateData($id,$data);
    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' SoloParentID ".$action; 
        $this->_commonmodel->updateLog($logDetails);
    }
    public function active(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $type = $request->input('type');
        $is_activeinactive = $request->input('is_activeinactive');
        $this->_SoloParentID->updateRelation($request);
    
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
        $ID = SoloParentID::with(['files','family','claimant'])->where('wspa_id_number',$id);
        $firstdata = SoloParentID::find(1);
        if ($firstdata->wspa_occupation === 'Test Data') {
            $ID = $ID->where('id','!=',1);
        }
        $ID = $ID->orderBy('id','desc')->first();
        $ID->wspa_id_number = Carbon::now()->format('y').'-'.substr($ID->wspa_id_number, -4);
        echo json_encode($ID);
    }
    public function print(Request $request, $id)
    {
        $data = SoloParentID::find($id);
        PDF::SetTitle('Justification for '.$data->claimant->cit_fullname.'');    
        PDF::SetMargins(20, 15, 20,true);    
        PDF::SetAutoPageBreak(TRUE, 0);
        PDF::AddPage('P', 'LEGAL');

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTML('<h3 style="text-align:center">Republic of the Philippines</h3>',true, false, false, false, 'center');
        
        PDF::SetFont('Helvetica', '', 15);
        PDF::writeHTML('<h1 style="text-align:center">City Social Welfare and Development Office</h1>',true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-align:center">Palayan City</p>',true, false, false, false, 'center');
        PDF::ln(5);
        PDF::writeHTML('<h3 style="text-align:center">Application Form for Solo Parents</h3>',true, false, false, false, 'center');

        PDF::ln(10);
        PDF::SetFont('Helvetica', '', 9);
        PDF::MultiCell(130, 5, 'Name: <b>'.$data->claimant->cit_fullname.'</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(25, 5, 'Age: <b>'.$data->claimant->age.'</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Sex: <b>'.$data->claimant->gender.'</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(70, 5, 'Date of Birth: <b>'.$data->claimant->cit_date_of_birth.'</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(70, 5, 'Place of Birth: <b>'.$data->claimant->cit_place_of_birth.'</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Blood Type: <b>'.$data->claimant->cit_blood_type.'</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Address: <b>'.$data->claimant->cit_full_address.'</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Highest Educational Attainment: <b>'.$data->claimant->education().'</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(100, 5, 'Occupation: <b>'.$data->wspa_occupation.'</b>', 0, 'L', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Monthly Income: <b>'.$data->wspa_monthly_income.'</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::MultiCell(0, 5, 'Total Monthly Family Income: <b>'.$data->wspa_total_income.'</b>', 0, 'L', 0, 1, '', '', true, 0, true);
        PDF::ln();
        
        // family composition
        $family = '
        <ol type="I">
            <li><h4>Family Composition</h4>
                <table border="1" style="text-align:center">
                    <tr>
                        <th><b>Name</b></th>
                        <th><b>Rel. to Client</b></th>
                        <th><b>Age</b></th>
                        <th><b>Status</b></th>
                        <th><b>Educational Attainment</b></th>
                        <th><b>Occupation/ Monthly Income</b></th>
                    </tr>';
        foreach ($data->family as $value) {
            $family .= '
                    <tr>
                        <td>'.$value->info->cit_fullname.'</td>
                        <td>'.$value->wsfc_relation.'</td>
                        <td>'.$value->info->age.'</td>
                        <td>'.$value->info->status().'</td>
                        <td>'.$value->info->education().'</td>
                        <td>'.$value->wsfc_occupation.' - '.$value->wsfc_monthly_income.'</td>
                    </tr>
            ';
        }
        $family .= '</table>
                <p><i>Includes family members and other members of the household</i></p>
            </li>

            <li><h4>Classification/Circumstances of being a Solo Parents</h4>
                <p style="text-indent: 30px">'.$data->wspa_classification.'</p>
            </li>
            <li><h4>Need/Problems of Solo Parents</h4>
                <p style="text-indent: 30px">'.$data->wspa_needs_problem.'</p>
            </li>
            <li><h4>Family Resources</h4>
                <p style="text-indent: 30px">'.$data->wspa_family_resources.'</p>
            </li>
        </ol>
        ';
        PDF::writeHTML($family,true, false, false, false, 'center');
        PDF::writeHTML('<p style="text-indent: 30px">I hereby certify that the information given above are true and correct. I further understand that any misinterpretation that may have made will subject me to criminal and civil liabilities provided for by existing laws.</p>',true, false, false, false, 'center');

        PDF::ln(20);
        PDF::MultiCell(58, 5, '_______________________________<br> Date: ', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(59, 5, '', 0, 'C', 0, 0, '', '', true, 0, true);
        PDF::MultiCell(59, 5, '________________________________<br>Signature/ Thumbmark <br> Over Printed Name ', 0, 'C', 0, 0, '', '', true, 0, true);


        PDF::Output('Solo_Parent_ID_Form'.$data->claimant->cit_fullname.'.pdf');
      
    }
}
