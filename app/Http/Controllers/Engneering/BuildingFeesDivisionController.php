<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\BuildingFeesDivision;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BuildingFeesDivisionController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrgetClients = array(""=>"Please Select");
    public $arrfeescategory = [];
    public function __construct(){
		$this->_buildingfeesdivision= new BuildingFeesDivision(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ebpfd_group'=>'','ebpfd_description'=>'','ebpfc_id'=>'','ebpfd_feessetid'=>'');  
        //$this->slugs = 'buildingfeesdivision';
		$this->slugs = 'formula';
		foreach ($this->_buildingfeesdivision->feescategory() as $val) {
            $this->arrfeescategory[$val->id]=$val->ebpfc_description;
        }
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('Engneering.BuildingFeesDivision.index');
    }


    public function getList(Request $request){
		$arrfeescategory =$this->arrfeescategory;
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_buildingfeesdivision->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->ebpfd_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ebpfd_group']=$row->ebpfd_group;
			$arr[$i]['ebpfd_description']=$row->ebpfd_description;
			$arr[$i]['ebpfc_id']=$arrfeescategory[$row->ebpfc_id];
            $arr[$i]['ebpfd_status']=($row->ebpfd_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/buildingfeesdivision/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Building Fees Division">
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
        $data=array('ebpfd_status' => $is_activeinactive);
        $this->_buildingfeesdivision->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Building Fees Division ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        $arrfeescategory =$this->arrfeescategory;
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BuildingFeesDivision::find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_buildingfeesdivision->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Building Fees Division Updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebpfd_status'] = 1;
                $lastinsertid = $this->_buildingfeesdivision->addData($this->data);
                $success_msg = 'Building Fees Division added successfully.';
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('buildingfeesdivision.index')->with('success', __($success_msg));
    	}
        return view('Engneering.BuildingFeesDivision.create',compact('data','arrfeescategory'));
	}
    
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'ebpfd_description'=>'required|unique:eng_building_permit_fees_division,ebpfd_description,'.(int)$request->input('id'),
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
