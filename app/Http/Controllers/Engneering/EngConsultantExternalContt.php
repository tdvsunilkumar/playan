<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\ConsultantExternal;
use App\Models\Engneering\EngProfessionType;
use App\Models\Engneering\EngSubProfession;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EngConsultantExternalContt extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){

        $this->_ConsultantExternal = new ConsultantExternal();
		$this->_EngProfessionType = new EngProfessionType();
		$this->_EngSubProfession = new EngSubProfession();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ept_id'=>'','esp_id'=>'','firstname'=>'','middlename'=>'','lastname'=>'','fullname'=>'','suffix'=>'','title'=>'','gender'=>'','birthdate'=>'','house_lot_no'=>'','street_name'=>'','subdivision'=>'','brgy_code'=>'','country'=>'','email_address'=>'','telephone_no'=>'','mobile_no'=>'','ptr_no'=>'','ptr_date_issued'=>'','prc_no'=>'','prc_validity'=>'','prc_date_issued'=>'','tin_no'=>'','iapoa_no'=>'','iapoa_or_no'=>'');  
        $this->slugs = 'engineering/consultantexternal';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Engneering.consultantexternal.index');
    }
    

    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read');
        $data=$this->_ConsultantExternal->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engineering/consultantexternal/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Consultant External">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
			$ept_id = array(""=>"Please Select");
			foreach ($this->_EngProfessionType->allAppType() as $val) {
				$ept_id[$val->id]=$val->ept_type;
			}
			$esp_id = array(""=>"Please Select");
			foreach ($this->_EngSubProfession->allAppType() as $val) {
				$esp_id[$val->id]=$val->esp_sub_type;
			}
			$brgy_code = array(""=>"Please Select");
			foreach ($this->_ConsultantExternal->alllists() as $val) {
				$brgy_code[$val->id] = $val->brgy_code;
			}
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ept_id']=$ept_id[$row->ept_id];
			$arr[$i]['esp_id']=$esp_id[$row->esp_id];
			$arr[$i]['firstname']=$row->firstname;
			$arr[$i]['middlename']=$row->middlename;
			$arr[$i]['lastname']=$row->lastname;
			$arr[$i]['fullname']=$row->fullname;
			$arr[$i]['suffix']=$row->suffix;
			$arr[$i]['title']=$row->title;
			$arr[$i]['gender']=$row->gender;
			$arr[$i]['birthdate']=$row->birthdate;
			$arr[$i]['house_lot_no']=$row->house_lot_no;
			$arr[$i]['street_name']=$row->street_name;
			$arr[$i]['subdivision']=$row->subdivision;
			$arr[$i]['brgy_code']= $brgy_code[$row->brgy_code];
			$arr[$i]['country']=$row->country;
			$arr[$i]['email_address']=$row->email_address;
			$arr[$i]['telephone_no']=$row->telephone_no;
			$arr[$i]['mobile_no']=$row->mobile_no;
			$arr[$i]['ptr_no']=$row->ptr_no;
			$arr[$i]['ptr_date_issued']=$row->ptr_date_issued;
			$arr[$i]['prc_no']=$row->prc_no;
			$arr[$i]['prc_validity']=$row->prc_validity;
			$arr[$i]['prc_date_issued']=$row->prc_date_issued;
			$arr[$i]['tin_no']=$row->tin_no;
			$arr[$i]['iapoa_no']=$row->iapoa_no;
			$arr[$i]['iapoa_or_no']=$row->iapoa_or_no;
			$arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':
			'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
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
        $this->_ConsultantExternal->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Consultant External ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

		$ept_id = array(""=>"Please Select");
        foreach ($this->_EngProfessionType->allAppType() as $val) {
            $ept_id[$val->id]=$val->ept_type;
        }
		
		$esp_id = array(""=>"Please Select");
        foreach ($this->_EngSubProfession->allAppType() as $val) {
            $esp_id[$val->id]=$val->esp_sub_type;
        }
		
		$brgy_code = array(""=>"Please Select");
		foreach ($this->_ConsultantExternal->alllists() as $val) {
			$brgy_code[$val->id] = $val->brgy_code;
		}
		
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_ConsultantExternal->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_ConsultantExternal->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Consultant External '".$this->data['id']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $request->id = $this->_ConsultantExternal->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Consultant External '".$this->data['id']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            //return redirect()->route('consultantexternal.index')->with('success', __($success_msg));
			return redirect()->back();
        }
        return view('Engneering.consultantexternal.create',compact('data','ept_id','esp_id','brgy_code'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ept_id'=>'required|:consultants,ept_id,'.(int)$request->input('id'),
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
