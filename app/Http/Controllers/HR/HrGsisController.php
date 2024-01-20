<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrGsis;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrGsisController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_hrgsis= new HrGsis(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrgt_description'=>'','hrgt_amount_from'=>'','hrgt_amount_to'=>'','hrgt_personal_share'=>'','hrgt_gov_share'=>'','hrgt_personal_type'=>'','hrgt_gov_type'=>'');  
        $this->slugs = 'hr-gsis-benefits'; 
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('HR.hrgsis.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrgsis->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->hrgt_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrgt_description']=$row->hrgt_description;
			$arr[$i]['hrgt_amount_from']=currency_format($row->hrgt_amount_from);
			$arr[$i]['hrgt_amount_to']=currency_format($row->hrgt_amount_to);
			
            $arr[$i]['hrgt_personal']=$row->hrgt_personal_type === 1 ? 'PHP '.$row->hrgt_personal_share : $row->hrgt_personal_share . '%';
            $arr[$i]['hrgt_gov']=$row->hrgt_gov_type === 1 ? 'PHP '.$row->hrgt_gov_share : $row->hrgt_gov_share . '%';
            //$arr[$i]['hrgt_status']=($row->hrgt_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-gsis-benefits/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Hr GSIS">
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
        $data=array('hrgt_status' => $is_activeinactive);
        $this->_hrgsis->updateActiveInactive($id,$data);

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
            $data = $this->_hrgsis->getEditDetails($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrgsis->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'GSIS Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated GSIS '".$this->data['hrgt_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hrgt_status'] = 1;
                $lastinsertid = $this->_hrgsis->addData($this->data);
                $success_msg = 'GSIS added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added GSIS '".$this->data['hrgt_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrgsis.index')->with('success', __($success_msg));
    	}
        return view('HR.hrgsis.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
               'hrgt_description' => 'required|unique:hr_gsis_table,hrgt_description,'.(int)$request->input('id'),
                'hrgt_amount_from' => 'required|numeric|min:1', // 'hrgt_amount_from' should be numeric and minimum value is 1
                'hrgt_amount_to' => 'required|numeric|gte:hrgt_amount_from', // 'hrgt_amount_to' should be numeric and greater than or equal to 'hrgt_amount_from'
                'hrgt_personal_share' => 'required',
                'hrgt_gov_share' => 'required'
            ],
            [
            'hrgt_amount_from.min' => 'min. value should be greater than 0.',
            'hrgt_amount_to.gte' => 'The To Field should be greater than or equal to From Field.',
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
