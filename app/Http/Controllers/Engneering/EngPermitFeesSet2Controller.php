<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngPermitFeesSet2;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EngPermitFeesSet2Controller extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_engpermitfeesset2= new EngPermitFeesSet2(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('ebpfs2_id'=>'','ebpfs2_range_from'=>'','ebpfs2_range_to'=>'','ebpfs2_fees'=>'');  
        $this->slugs = 'engbuildingfeesset2';
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('Engneering.PermitFeesSet2.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engpermitfeesset2->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->ebpfs2_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->ebpfs2_id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->ebpfs2_id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ebpfs2_range_from']=$row->ebpfs2_range_from;
			$arr[$i]['ebpfs2_range_to']=$row->ebpfs2_range_to;
			$arr[$i]['ebpfs2_fees']=$row->ebpfs2_fees;
            $arr[$i]['ebpfs2_status']=($row->ebpfs2_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbuildingfeesset2/store?ebpfs2_id='.$row->ebpfs2_id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Building Permit Fees Set1">
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
        $data=array('eef_status' => $is_activeinactive);
        $this->_engpermitfeesset2->updateActiveInactive($id,$data);

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
        if($request->input('ebpfs2_id')>0 && $request->input('submit')==""){
            $data = EngPermitFeesSet2::where('ebpfs2_id',$request->input('ebpfs2_id'))->select('*')->first();
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('ebpfs2_id')>0){
                $this->_engpermitfeesset2->updateData($request->input('ebpfs2_id'),$this->data);
                $lastinsertid = $request->input('ebpfs2_id');
                $success_msg = 'Building Permit Fees Set2 Updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebpfs2_status'] = 1;
                $lastinsertid = $this->_engpermitfeesset2->addData($this->data);
                $success_msg = 'Building Permit Fees Set2 added successfully.';
            }
            $logDetails['module_id'] =$request->ebpfs1_id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('permitfeesset2.index')->with('success', __($success_msg));
    	}
        return view('Engneering.PermitFeesSet2.create',compact('data'));
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
