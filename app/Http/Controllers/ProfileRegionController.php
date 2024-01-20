<?php

namespace App\Http\Controllers;

use App\Models\ProfileRegion;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use Session;
class ProfileRegionController extends Controller
{
    
     public $data = [];
  

    public function __construct(){
        $this->_profileregion = new ProfileRegion();
        $this->slugs = 'administrative/address/region';
		$this->data = array('id'=>'','reg_no'=>'','reg_region'=>'','reg_description'=>'','uacs_code'=>'');
            
    }
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('profileregion.index');
        
    }
    public function getList(Request $request){
		$this->is_permitted($this->slugs, 'read');
        $data=$this->_profileregion->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['reg_no']=$row->reg_no;
            $arr[$i]['reg_region']=$row->reg_region;
            $arr[$i]['reg_description']=$row->reg_description;
           
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/profileregion/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Region">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>' ;
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
		$this->is_permitted($this->slugs, 'update');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_profileregion->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "profile_regions",'action' =>"update",'id'=>$request->input('id')]);
}
    
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = ProfileRegion::find($request->input('id'));
           
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_profileregion->updateData($request->input('id'),$this->data);
                $success_msg = 'Profile Region updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "profile_regions",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastId=$this->_profileregion->addData($this->data);
                Session::put('remort_serv_session_det', ['table' => "profile_regions",'action' =>"store",'id'=>$lastId]);
                $accNo = str_pad($lastId, 2, '0', STR_PAD_LEFT);
                $arr['reg_no']=$accNo;
                $this->_profileregion->updateData($lastId,$arr);
                $success_msg = 'Profile Region added successfully.';
            }
            return redirect()->route('profileregion.index')->with('success', __($success_msg));
        }
        return view('profileregion.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'reg_no'=>'required|unique:profile_regions,reg_no,'.$request->input('id'),
                'reg_region'=>'required',
                'reg_description'=>'required',
                'uacs_code'=>'numeric|nullable',
                
               // 'uacs_code'=>'required'
                
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
            $ProfileRegion = ProfileRegion::find($id);
            if($ProfileRegion->created_by == \Auth::user()->creatorId()){
                $ProfileRegion->delete();
            }
    }
}
