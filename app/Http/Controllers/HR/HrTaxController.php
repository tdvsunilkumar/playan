<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrTax;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrTaxController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_hrtax= new HrTax(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrtt_description'=>'','hrtt_amount_from'=>'','hrtt_amount_to'=>'','hrtt_percentage'=>'','hrtt_fixed_amount'=> '','ewt_id'=>'');  
        $this->slugs = 'hr-tax'; 
        foreach ($this->_hrtax->getEwt() as $val) {
            $this->ewt[$val->id]=$val->name;
        } 
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('HR.hrtax.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrtax->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->hrtt_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrtt_description']=$row->hrtt_description;
			$arr[$i]['hrtt_amount_from']=currency_format($row->hrtt_amount_from);
			$arr[$i]['hrtt_amount_to']=currency_format($row->hrtt_amount_to);
			$arr[$i]['hrtt_fixed_amount']=currency_format($row->hrtt_fixed_amount);
			$arr[$i]['hrtt_percentage']=$row->hrtt_percentage;
            //$arr[$i]['is_active']=($row->hrtt_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-tax/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Hr Tax">
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
        $data=array('hrtt_status' => $is_activeinactive);
        $this->_hrtax->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Tax ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrtax->getEditDetails($request->input('id'));
        }
        $ewt=$this->ewt;
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrtax->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Tax Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated tax '".$this->data['hrtt_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hrtt_status'] = 1;
                $lastinsertid = $this->_hrtax->addData($this->data);
                $success_msg = 'Tax added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Tax '".$this->data['hrtt_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrtax.index')->with('success', __($success_msg));
    	}
        return view('HR.hrtax.create',compact('data','ewt'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrtt_description'=>'required|unique:hr_tax_table,hrtt_description,'.(int)$request->input('id'),
                'ewt_id'=>'required',
                'hrtt_amount_from'=>'required',
                'hrtt_amount_to'=>'required',
                'hrtt_percentage'=>'required',
                'hrtt_fixed_amount'=>'required'
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
