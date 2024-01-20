<?php

namespace App\Http\Controllers;

use App\Models\ProfileProvince;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use Session;
class ProfileProvinceController extends Controller
{
    
     public $data = [];
     public $nofbusscode = array(""=>"Select Code");
  

    public function __construct(){
        $this->_profileprovince = new ProfileProvince();
		$this->slugs = 'administrative/address/province';
		$this->data = array('id'=>'','reg_no'=>'','prov_no'=>'','prov_desc'=>'','uacs_code'=>'');
            
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('profileprovince.index');
         
    }
    public function ProfileProvinceData(Request $request){
        $id= $request->input('id');
        $data = $this->_profileprovince->ProfileProvinceData($id);
        echo json_encode($data);
    }
    public function getList(Request $request){
        $data=$this->_profileprovince->getList($request);
        $arr=array();
        $i="0"; 
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['reg_region']=$row->reg_region.' - '.$row->reg_description;
            $arr[$i]['prov_no']=$row->prov_no;
            $arr[$i]['prov_desc']=$row->prov_desc;
           
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/profileprovince/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Province">
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
        $this->_profileprovince->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "profile_provinces",'action' =>"update",'id'=>$request->input('id')]);

}
    
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = ProfileProvince::find($request->input('id'));
			
        }
        foreach ($this->_profileprovince->getProfileProvince() as $val) {
            $this->nofbusscode[$val->id]=$val->reg_region.' - '.$val->reg_description;
        }

         $nofbusscode = $this->nofbusscode;

         foreach((array)$this->data as $key=>$val){
            $this->data[$key] = $request->input($key);
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_profileprovince->updateData($request->input('id'),$this->data);
                $success_msg = 'Profile Province updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "profile_provinces",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                // print_r($this->data);
                // exit;
                // $lastId=$this->_profileprovince->addData($this->data);
                // $accNo = str_pad($lastId, 2, '0', STR_PAD_LEFT);
                // $arr['reg_no']=$accNo;
                 
                // $this->_profileprovince->updateData($lastId,$arr);
                $lastId=$this->_profileprovince->addData($this->data);
                $success_msg = 'Profile Province added successfully.';
                Session::put('remort_serv_session_det', ['table' => "profile_provinces",'action' =>"store",'id'=>$lastId]);
            }
            return redirect()->route('profileprovince.index')->with('success', __($success_msg));
        }
        return view('profileprovince.create',compact('data','nofbusscode'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'reg_no'=>'required|unique:profile_provinces,reg_no,prov_desc,'.$request->input('id'),
                // 'prov_no'=>'required|unique:profile_provinces,prov_no,'.$request->input('id'),
                'prov_no'=>'required',
                'reg_no'=>'required',
                // 'uacs_code'=>'required',
               'prov_desc' => 'required|unique:profile_provinces,prov_desc,' .$request->input('id'). ',id,reg_no,' .$request->input('reg_no'),
               'uacs_code'=>'numeric|nullable'
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
            $ProfileProvince = ProfileProvince::find($id);
            if($ProfileProvince->created_by == \Auth::user()->creatorId()){
                $ProfileProvince->delete();
            }
    }
}
