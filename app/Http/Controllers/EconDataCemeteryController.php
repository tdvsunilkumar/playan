<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use App\Models\EcoDataCemetery;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class EconDataCemeteryController extends Controller
{
	 public $data = [];
     public $postdata = [];
     private $slugs;
	 public $barangay = array(""=>"Select Barangay");

    public function __construct(){
		 $this->_commonmodel = new CommonModelmaster();
		$this->_EcoDataCemetery = new EcoDataCemetery();
		$this->data = array('id'=>'','brgy_id'=>'','cem_name'=>'','remark'=>'','status'=>'');
		$this->slugs = 'econ-data-cemetery';
    }
    
    public function index(Request $request)
    { 
       return view('econdatacemetery.index'); 
    }

    public function getList(Request $request){
		$this->is_permitted($this->slugs, 'read');
        $data=$this->_EcoDataCemetery->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;        
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['cem_name']=$row->cem_name;
            $arr[$i]['remark']=$row->remark;
            $arr[$i]['is_active']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/EcoDataCemetery/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Cemetery">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
                // <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                //     </a>
                // </div>
            
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
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('status' => $is_activeinactive);
        $this->_EcoDataCemetery->updateActiveInactive($id,$data);
    }
    public function store(Request $request){
        $data = (object)$this->data;
        $muncipality = $this->_EcoDataCemetery->getMuncipality();
		
        if(isset($muncipality->mun_no)){		
			$mun_no= $muncipality->mun_no;
	    }else{ 
			$mun_no = 200;
		}
		
        foreach ($this->_EcoDataCemetery->getBarangay($mun_no) as $val) {
            $this->barangay[$val->id]=$val->brgy_name;
        }
        $barangay = $this->barangay;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EcoDataCemetery::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                unset($this->data['status']);
                $this->_EcoDataCemetery->updateData($request->input('id'),$this->data);
                $success_msg = 'Cemetery updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
               
                $this->_EcoDataCemetery->addData($this->data);
                $success_msg = 'Cemetery added successfully.';
            }
            return redirect('econ-data-cemetery')->with('success', __($success_msg));
        }
        return view('econdatacemetery.create',compact('data','barangay'));
        
    }


    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'brgy_id'=>'required',
                'cem_name'=>'required',  
            ],[
			  'brgy_id.required' => 'The barangay location field is required.',
			  'cem_name.unique' => 'The cemetery name has already been taken.'
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

   
    public function Delete(Request $request){
        $id = $request->input('id');
            $Country = EcoDataCemetery::find($id);
            if($Country->created_by == \Auth::user()->creatorId()){
                $Country->delete();
            }
    }
}
