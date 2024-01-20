<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrIncomeDeductionType;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrIncomeDeductionTypeController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_hrIncomeDeductionType= new HrIncomeDeductionType(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'hridt_description'=>'',
            'hridt_type'=>0,
            'sl_id'=>null,
            'gl_id'=>null,
            'sl_id_debit'=>null,
            'gl_id_debit'=>null
        );  
        $this->slugs = 'hr-income-deduction-type'; 
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('HR.HrIncomeDeductionType.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrIncomeDeductionType->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hridt_description']=$row->hridt_description;
            $arr[$i]['gl']=($row->gl)?$row->gl->description:'';
            $arr[$i]['sl']=($row->sl)?$row->sl->description:'';
            $arr[$i]['gl_debit']=($row->gl_debit)?$row->gl_debit->description:'';
            $arr[$i]['sl_debit']=($row->sl_debit)?$row->sl_debit->description:'';
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-income-deduction-type/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Hr Income Deduction Type">
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
    
    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_hrIncomeDeductionType->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Income Deduction Type ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        $sl = [];
        $gl = [];
        $sl_debit = [];
        $gl_debit = [];
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrIncomeDeductionType->find($request->input('id'));
            $sl = $data->sl ? [$data->sl_id=>'['.$data->sl->code.'] '.$data->sl->description] : [];
            $gl = $data->gl ? [$data->gl_id=>'['.$data->gl->code.'] '.$data->gl->description] : [];
            $sl_debit = $data->sl_debit ? [$data->sl_id_debit=>'['.$data->sl_debit->code.'] '.$data->sl_debit->description] : [];
            $gl_debit = $data->gl_debit ? [$data->gl_id_debit=>'['.$data->gl_debit->code.'] '.$data->gl_debit->description] : [];
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
                $sl = $data->sl_id ? [$data->sl_id=>'['.$data->sl->code.'] '.$data->sl->description] : [];
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrIncomeDeductionType->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Income Deduction Type Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Income Deduction Type '".$this->data['hridt_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_hrIncomeDeductionType->addData($this->data);
                $success_msg = 'Income Deduction Type added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Income Deduction Type '".$this->data['hridt_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('HrIncomeDeductionType.index')->with('success', __($success_msg));
    	}
        return view('HR.HrIncomeDeductionType.create',compact('data','sl','gl','sl_debit','gl_debit'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hridt_description'=>'required|unique:hr_income_deduction_type,hridt_description,'.(int)$request->input('id'),
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
    
}
