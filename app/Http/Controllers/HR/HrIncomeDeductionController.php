<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrIncomeDeduction;
use App\Models\HR\HrIncomeDeductionType;
use App\Models\HR\HrAppointment;
use App\Models\HR\HrTax;
use App\Models\HR\HrPhilHealth;
use App\Models\HR\HrPagibigTable;
use App\Models\HR\HrGsis;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class HrIncomeDeductionController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $app_type = array(""=>"Please Select");
    // public $cycle = array(""=>"Please Select");
    
     public function __construct(){
		$this->_hrIncomeDeduction= new HrIncomeDeduction(); 
        $this->_hrEmployee= new HrEmployee(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('hridt_id'=>'','hriad_ref_no'=>'','hriad_description'=>'','hriad_amount'=>'','hrlc_id'=>'','emp_id'=>'','hriad_effectivity_date'=>'','hriad_balance'=>'','hriad_approved_by'=>'','hriad_approved_date'=>'');  
        $this->slugs = 'hr-income-deduction';
        foreach ($this->_hrIncomeDeduction->getAppType() as $val) {
            $this->app_type[$val->id]=$val->hridt_description;
        } 
        foreach ($this->_hrIncomeDeduction->getCyle() as $val) {
            $this->cycle[$val->id]=$val->hrlc_month;
        } 
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('HR.hrIncomeDeduction.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrIncomeDeduction->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        // dd($data['data']);
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status = '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hriad_ref_no']=$row->hriad_ref_no;
            $arr[$i]['app_type']=$row->app_type;
            $arr[$i]['hriad_description']=$row->hriad_description;
            $arr[$i]['cycle']=$row->cycle;
            $arr[$i]['hriad_balance']=$row->hriad_balance;
            //$arr[$i]['hrlc_status']=($row->hrlc_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-income-deduction/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Income and Deduction">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
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
    public function getEmpList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $selectedValues=$request->input('selectedValues');
        // dd($selectedValues);
        $data=$this->_hrIncomeDeduction->getEmpList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        // dd($data['data']);
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $checkBox="<input type='checkbox' class='selected_emp_id row-checkbox' name='selected_emp[]' value=".$row->id.">";
            $status = '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>';  
            // if(!empty($selectedValues))
            // {
            //     if (in_array($row->id, $selectedValues)) {
            //         $checkBox="<input type='checkbox' checked class='selected_emp_id row-checkbox' name='selected_emp[]' value=".$row->id.">";
            //     } else {
            //         $checkBox="<input type='checkbox' class='selected_emp_id row-checkbox' name='selected_emp[]' value=".$row->id.">";
            //     }
            // }
            // else {
            //     $checkBox="<input type='checkbox' class='selected_emp_id row-checkbox' name='selected_emp[]' value=".$row->id.">";
            // }
            
            $arr[$i]['select']=$checkBox;
            $arr[$i]['fullname']=$row->fullname;
            $arr[$i]['dept_name']=$row->dept_name;
            $arr[$i]['designation']=$row->designation;
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
    public function getSelEmpList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $originalArray = $request->selectedValues;
        // $empIds = array_map(function ($item) {
        //     return $item['emp_id'];
        // }, $originalArray);
        
        $data=$this->_hrIncomeDeduction->getSelEmpList($request);
        $amt =currency_to_float($request->amt);
        $cycle = $request->cycle;
        $app_type = $request->type;
        $type_data = HrIncomeDeductionType::find($app_type);
        $ref_no = $request->ref_no;
        if($amt != null &&  $cycle != null)
        {
            $cycle_val=$this->_hrIncomeDeduction->getCycleById($cycle);
            $deduct_amt= $amt / $cycle_val;
            $balance = $amt - $deduct_amt;
        }
        else{
            $deduct_amt= 0.00;
            $balance = 0.00;
        }


        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->start-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        // dd($request->all());
        // $emp = array_slice($data['data'],0,10);
        $desc = explode(' ',strtolower($type_data->hridt_description));
        $special_type_list = 'tax pagibig philhealth gsis';
        $hridt_type = 2;
        if ($type_data->hridt_type === 0) {
            $hridt_type = 0;
        }
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $eft_date = null;
            if(!empty($originalArray)){
                // dd($originalArray);
                foreach ($originalArray as $item) {
                    if ($item['emp_id'] == $row->id) {
                        $eft_date = $item['hriad_effectivity_date'];
                        $amt = isset($item['hriad_balance']) ? $item['hriad_balance'] :null;
                            // $deduction = isset($item['deduction']) ? $item['deduction'] :null;
                        if (preg_match_all('['.implode('|', $desc).']', $special_type_list)) {
                            $deduct_amt = isset($item['hriad_deduct']) && !empty($item['hriad_deduct']) ? $item['hriad_deduct'] :null;
                        } else {
                            $amt =currency_to_float($request->amt);
                        }
                        break; // Once found, exit the loop
                    }
                }
            }

            if ($eft_date != null) {
                $effectiveDate="<input type='date' class='form-control selected_efp_date' name='selected_efp_date[]' value=".$eft_date.">";
            } else {
                $effectiveDate="<input type='date' class='form-control selected_efp_date' name='selected_efp_date[]' value='2023-08-08'>";
            }
            // dd($desc);
            if (preg_match_all('['.implode('|', $desc).']', $special_type_list)) {
                $hridt_type = 1;
                if ($deduct_amt) {
                    $deduct_amt = number_format($deduct_amt,2);
                } else {
                    $deduct_amt = number_format($this->getGovDeduction($item['emp_id'],$app_type),2);
                }
                $deductInput="<input type='text' class='form-control selected_efp_deduct' name='selected_efp_deduct[]' value=".$deduct_amt." >";
            } else {
                $deductInput = "<input type='text' class='form-control selected_efp_deduct' name='selected_efp_deduct[]' value=".number_format($deduct_amt,2)." readonly>";
            }
            $balanceInput = "<input type='text' class='form-control selected_efp_balance' name='selected_efp_balance[]' value=".number_format($amt,2)." readonly>";
            
            $action = '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm remvSelData ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a></div>';  
            $arr[$i]['srno']=$sr_no."<input type='hidden' class='selected_emp_id_f' name='selected_emp_f[]' value=".$row->id.">";
            $arr[$i]['fullname']=$row->fullname;
            $arr[$i]['dept_name']=$row->dept_name;
            $arr[$i]['designation']=$row->designation;
            $arr[$i]['efective_date']=$effectiveDate;
            $arr[$i]['deduct_amt']=$deductInput;
            $arr[$i]['balance']=$balanceInput;
            $arr[$i]['action']=$action;
            $i++;
        }
        
        $totalRecords=$data['data_cnt'];
        $json_data = array(
            "recordsTotal"    => intval( $totalRecords ),  
            "recordsFiltered" => intval($totalRecords),
            "selectedValues"  => $request->selectedValues,
            "hridt_type"  => $hridt_type,
            "data"            => $arr   // total data array
        );
        echo json_encode($json_data);
    }
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('hrlc_status' => $is_activeinactive);
        $this->_hrIncomeDeduction->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Tax ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        $data = (object)$this->data;
        $app_type =$this->app_type;
        $cycle=  $this->cycle;
        $selectedEmp=[];
        $id="";

        $currentYear = date('Y');
        $lastData = DB::table('hr_income_and_deduction')->where('hriad_ref_no', 'like', "{$currentYear}%")
                           ->orderByDesc('hriad_ref_no')
                           ->first();
            if ($lastData) {
                $lastNumber = intval(substr($lastData->hriad_ref_no, -6));
                $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '000001';
            }
        $hriad_ref_no = $currentYear . '-' . $newNumber;


        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrIncomeDeduction->getEditDetails($request->input('id'));
            $selectedEmp=$this->_hrIncomeDeduction->getSelEmpByRef($data->hriad_ref_no);
            // dd($selectedEmp);
            $id=$request->input('id');
            $hriad_ref_no = $data->hriad_ref_no;
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            // dd($this->_hrIncomeDeduction->getSL($this->data['hridt_id']));
            $this->data['sl_id'] = $this->_hrIncomeDeduction->getType($this->data['hridt_id'],'sl_id');
            $this->data['gl_id'] = $this->_hrIncomeDeduction->getType($this->data['hridt_id'],'gl_id');
            $this->data['sl_id_debit'] = $this->_hrIncomeDeduction->getType($this->data['hridt_id'],'sl_id_debit');
            $this->data['gl_id_debit'] = $this->_hrIncomeDeduction->getType($this->data['hridt_id'],'gl_id_debit');
            $this->data['hridt_type'] = $this->_hrIncomeDeduction->getType($this->data['hridt_id'],'hridt_type');
            $amt =currency_to_float($this->data['hriad_amount']);
            // dd($_POST['selected_emp_f']);
           
            if($request->input('id')>0){
                $sel_data=$_POST['selected_emp_f'];
                $this->_hrIncomeDeduction->deleteByUnselect($this->data['hriad_ref_no'],$sel_data);
                $cycle = $this->data['hrlc_id'];
                    $cycle_val=$this->_hrIncomeDeduction->getCycleById($cycle);
                    $deduct_amt= $amt / $cycle_val;
                    $balance = $amt - $deduct_amt;
                    $this->data['hriad_balance'] =  $balance;
            
                if(!empty($_POST['selected_emp_f'])){
                    $loop = count($_POST['selected_emp_f']); 
                    $healthcertreq = array();
                    for($i=0; $i < $loop;$i++){
                        // dd($i);
                        $this->data['emp_id'] = $_POST['selected_emp_f'][$i];
                        $hrEmp = $this->_hrEmployee->empDataById($_POST['selected_emp_f'][$i]);
                        // if (!isset($hrEmp->acctg_department_id)) {
                        //     dd($hrEmp);
                        //     # code...
                        // }
                        $this->data['hrla_department_id'] = $hrEmp->acctg_department_id;
                        $this->data['hrla_division_id'] = $hrEmp->acctg_department_division_id;
                        $this->data['hriad_effectivity_date'] = $_POST['selected_efp_date'][$i];
                        $this->data['hriad_amount'] = $amt;
                        $this->data['hriad_deduct'] = str_replace(',','',$_POST['selected_efp_deduct'][$i]);
                        $this->data['hriad_balance'] = str_replace(',','',$_POST['selected_efp_balance'][$i]);
                        // dd($this->data);
                        $this->_hrIncomeDeduction->updateData($this->data['hriad_ref_no'], $this->data['emp_id'],$this->data);
                    }
                }

                $lastinsertid = $request->input('id');
                $success_msg = 'Income and deduction Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Income and deduction '".$this->data['hriad_ref_no']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $cycle = $this->data['hrlc_id'];
                    $cycle_val=$this->_hrIncomeDeduction->getCycleById($cycle);
                    $deduct_amt= $amt / $cycle_val;
                    $balance = $amt - $deduct_amt;
                    $this->data['hriad_balance'] =  $balance;
                if(!empty($_POST['selected_emp_f'])){
                    $loop = count($_POST['selected_emp_f']); 
                    $healthcertreq = array();
                    for($i=0; $i < $loop;$i++){
                        $this->data['emp_id'] = $_POST['selected_emp_f'][$i];
                        $hrEmp = $this->_hrEmployee->empDataById($_POST['selected_emp_f'][$i]);
                        $this->data['hrla_department_id'] = $hrEmp->acctg_department_id;
                        $this->data['hrla_division_id'] = $hrEmp->acctg_department_division_id;
                        $this->data['hriad_effectivity_date'] = $_POST['selected_efp_date'][$i];
                        $this->data['hriad_amount'] = $amt;
                        $this->data['hriad_deduct'] = str_replace(',','',$_POST['selected_efp_deduct'][$i]);
                        $this->data['hriad_balance'] = str_replace(',','',$_POST['selected_efp_balance'][$i]);
                        $this->_hrIncomeDeduction->addData($this->data);
                       }
                    }
               
                $success_msg = 'Income and deduction added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Income and deduction '".$this->data['hriad_ref_no']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrIncomeDeduction.index')->with('success', __($success_msg));
    	}
        return view('HR.hrIncomeDeduction.create',compact('data','app_type','cycle','hriad_ref_no','selectedEmp','id'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hriad_ref_no'=>'required',
                'hriad_description'=>'required',
                // 'hriad_amount'=>'required',
                'hridt_id'=>'required',
                // 'hrlc_id'=>'required'
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

    public function getGovDeduction($emp_id, $type)
    {
        $employee = HrAppointment::where('hr_emp_id',$emp_id)->first();
        $type = HrIncomeDeductionType::find($type);
        $type_desc = $type->hridt_description;
        $deduction = 0;
        // dd($type_desc);
        if ($employee) {
            switch (true) {
                case stristr($type_desc,'pagibig'):
                    if ($type->hridt_type === 3) {
                        $deduction = HrPagibigTable::getAmountScope($employee->hra_monthly_rate, 'personal');
                    } elseif ($type->hridt_type === 2) {
                        $deduction = HrPagibigTable::getAmountScope($employee->hra_monthly_rate, 'gov');
                    }
                    break;
                case stristr($type_desc,'gsis'):
                    if ($type->hridt_type === 3) {
                        $deduction = HrGsis::getAmountScope($employee->hra_monthly_rate, 'personal');
                    } elseif ($type->hridt_type === 2) {
                        $deduction = HrGsis::getAmountScope($employee->hra_monthly_rate, 'gov');
                    }
                    break;
                case stristr($type_desc,'philhealth'):
                    if ($type->hridt_type === 3) {
                        $deduction = HrPhilHealth::getAmountScope($employee->hra_monthly_rate, 'personal');
                    } elseif ($type->hridt_type === 2) {
                        $deduction = HrPhilHealth::getAmountScope($employee->hra_monthly_rate, 'gov');
                    }
                    break;
                case stristr($type_desc,'tax'):
                    $tax = HrTax::getAmountScope($employee->hra_annual_rate);
                    $fixed = $tax->hrtt_fixed_amount;
                    $percent = $tax->hrtt_percentage / 100;
                    $min_amount = $tax->hrtt_amount_from;
                    $excess = ($employee->hra_annual_rate - $min_amount) * $percent;
                    $deduction =($excess + $fixed)/12;
                    break;
            }
        }
        return $deduction;
    }
}
