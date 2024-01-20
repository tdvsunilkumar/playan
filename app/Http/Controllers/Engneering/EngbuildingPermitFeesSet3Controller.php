<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngPermitFeesSet3;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EngbuildingPermitFeesSet3Controller extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_engpermitfeesset3= new EngPermitFeesSet3(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('ebpfs3_id'=>'','ebpfs3_range_from'=>'','ebpfs3_range_to'=>'','ebpfs3_fees'=>'');  
        $this->slugs = 'engbuildingfeesset3';
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('Engneering.PermitFeesSet3.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engpermitfeesset3->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->ebpfs3_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->ebpfs3_id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->ebpfs3_id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ebpfs3_range_from']=$row->ebpfs3_range_from;
			$arr[$i]['ebpfs3_range_to']=$row->ebpfs3_range_to;
			$arr[$i]['ebpfs3_fees']=$row->ebpfs3_fees;
            $arr[$i]['ebpfs3_status']=($row->ebpfs3_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbuildingfeesset3/store?ebpfs3_id='.$row->ebpfs3_id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Building Permit Fees Set3">
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
        $data=array('ebpfs3_status' => $is_activeinactive);
        $this->_engpermitfeesset3->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Building Permit Fees Set1 ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('ebpfs3_id')>0 && $request->input('submit')==""){
            $data = EngPermitFeesSet3::where('ebpfs3_id',$request->input('ebpfs3_id'))->select('*')->first();
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('ebpfs3_id')>0){
                $this->_engpermitfeesset3->updateData($request->input('ebpfs3_id'),$this->data);
                $lastinsertid = $request->input('ebpfs3_id');
                $success_msg = 'Building Permit Fees Set3 Updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebpfs3_status'] = 1;
                $lastinsertid = $this->_engpermitfeesset3->addData($this->data);
                $success_msg = 'Building Permit Fees Set3 added successfully.';
            }
            $logDetails['module_id'] =$request->ebpfs3_id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('permitfeesset3.index')->with('success', __($success_msg));
    	}
        return view('Engneering.PermitFeesSet3.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                
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
