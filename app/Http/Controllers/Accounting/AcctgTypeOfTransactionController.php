<?php

namespace App\Http\Controllers\Accounting;
use App\Http\Controllers\Controller;
use App\Models\Accounting\LegaltypeTransaction;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AcctgTypeOfTransactionController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_LegaltypeTransaction= new LegaltypeTransaction(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array(
            'id'=>'',
            'type_of_transaction'=>'',
            'trigger_type'=>'',
            'trigger_count'=>1,
            'computation'=>'',
            'penalties'=>'',
        );  
        $this->slugs = 'type-of-transaction'; 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('accounting.setup-data.LegaltypeTransaction.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_LegaltypeTransaction->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['type_of_transaction']=$row->type_of_transaction;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/type-of-transaction/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lx" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Type of Transaction">
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
        $this->_LegaltypeTransaction->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Transaction Type ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_LegaltypeTransaction->findById($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
        $penalties = $this->_LegaltypeTransaction->getPenalties();
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_LegaltypeTransaction->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Transaction Type Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Transaction Type '".$this->data['type_of_transaction']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_LegaltypeTransaction->addData($this->data);
                $success_msg = 'Transaction Type added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Transaction Type '".$this->data['type_of_transaction']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('LegaltypeTransaction.index')->with('success', __($success_msg));
    	}
        return view('accounting.setup-data.LegaltypeTransaction.create',compact('data','penalties'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'type_of_transaction'=>'required|unique:eco_type_of_transaction,type_of_transaction,'.(int)$request->input('id'),
				'trigger_type'=>'required',
				'trigger_count'=>'required',
				'penalties'=>'required',
				'computation'=>'required',
            ],[
			  'type_of_transaction.required' => 'The Type of Transaction field is required.',
			  'type_of_transaction.unique' => 'The Type of Transaction has already been taken.',
			  'trigger_type.required' => 'The Triggers field is required.',
			  'trigger_count.required' => 'The Every field is required.',
			  'computation.required' => 'The Computation field is required.',
			  'penalties.required' => 'The Penalty field is required.',
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
