<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrPagibigTable;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrPagibigController extends Controller
{
    public $data = [];
     public $postdata = [];
    
     public function __construct(){
		$this->_hrPagibigTable= new HrPagibigTable(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrpit_description'=>'','hrpit_amount_from'=>'','hrpit_amount_to'=>'','hrpit_personal_share'=>'','hrpit_gov_share'=>'','hrpit_personal_type'=>'','hrpit_gov_type'=>'');  
        $this->slugs = 'hr-pagibig'; 
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('HR.pagiBig.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_hrPagibigTable->getList($request);
        $arrholidaytype = config('constants.arrHolidaysType');
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['hrpit_description']=$row->hrpit_description;
            $arr[$i]['hrpit_amount_from']=currency_format($row->hrpit_amount_from);
            $arr[$i]['hrpit_amount_to']=currency_format($row->hrpit_amount_to);
            
            $arr[$i]['hrpit_personal']=$row->hrpit_personal_type === 1 ? 'PHP '.$row->hrpit_personal_share : $row->hrpit_personal_share . '%';
            $arr[$i]['hrpit_gov']=$row->hrpit_gov_type === 1 ? 'PHP '.$row->hrpit_gov_share : $row->hrpit_gov_share . '%';
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');                 
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-pagibig/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Holidays">
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
        $this->_hrPagibigTable->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Pagibig ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_hrPagibigTable->find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = currency_to_float($request->input($key));
            }
            // dd($this->data);
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrPagibigTable->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Data Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Pagibig '".$this->data['hrpit_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_hrPagibigTable->addData($this->data);
                $success_msg = 'Data Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Pagibig '".$this->data['hrpit_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrpagibig.index')->with('success', __($success_msg));
    	}
        return view('HR.pagiBig.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrpit_description'=>'required',
                'hrpit_amount_from'=>'required',
                'hrpit_amount_to'=>'required',
                'hrpit_personal_share'=>'required',
                'hrpit_gov_share'=>'required'
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
