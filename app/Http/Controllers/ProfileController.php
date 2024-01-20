<?php

namespace App\Http\Controllers;
use App\Models\Profile;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;


class ProfileController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrBarangay = array(""=>"Please Select");
    public $arrCountryCode = array(""=>"Please Select");
    public function __construct(){
		$this->_profile = new Profile(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','p_code'=>'','p_first_name'=>'','p_middle_name'=>'','p_family_name'=>'','p_address_house_lot_no'=>'','p_address_street_name'=>'','p_address_subdivision'=>'','brgy_code'=>'','brgy_name'=>'','p_telephone_no'=>'','p_mobile_no'=>'','p_fax_no'=>'','p_tin_no'=>'','p_email_address'=>'','p_job_position'=>'','c_code'=>'','p_gender'=>'','p_date_of_birth'=>'','ba_code'=>'','ba_business_name'=>'','p_place_of_work'=>'','p_registered_date'=>'','p_is_employee'=>'1');  
    }
    public function index(Request $request)
    {
        
        
            $Profileusers = $this->_profile->getProfileusers();
            return view('profileuser.index', compact('Profileusers'));
        
    }
    public function getBploApplicationsDetails($id=''){
        $arrNature= array();
        if(empty($id)){
            $arrNature[0]['ba_business_name']='';
            $arrNature[0]['created_at']='';
            $arrNature[0]['app_type_id']='';
           
            
        }else{
            $arr = $this->_profile->getBploApplications($id);
            // echo "<pre>"; print_r($arr); exit;
            foreach($arr as $key=>$val){
                $arrNature[$key]['ba_business_name']=$val->ba_business_name;
                $arrNature[$key]['created_at']=$val->created_at;
                $arrNature[$key]['app_type_id']=$val->app_type_id;
               
               
            }
        }
        return $arrNature;
    }

    
    
    
    public function store(Request $request){
       
        foreach ($this->_profile->getBarangay() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_name.", ".$val->mun_desc. ", ".$val->prov_desc. ", ".$val->reg_region.  " [".$val->reg_no.$val->prov_no.$val->mun_no.$val->brgy_code."]";
        } 
        foreach ($this->_profile->getCountry() as $val) {
            $this->arrCountryCode[$val->id]=$val->country_name;
        } 
        $data = (object)$this->data;
        $arrBarangay = $this->arrBarangay;
        $arrCountryCode = $this->arrCountryCode;
        //print_r($arrsection); exit;
         $arrNature = $this->getBploApplicationsDetails();
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = Profile::find($request->input('id'));
            $arrNature = $this->getBploApplicationsDetails($request->input('id'));
            
        }

		if($request->isMethod('post')){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['p_complete_name_v1'] = $this->data['p_family_name']." ".$this->data['p_middle_name']." ".$this->data['p_first_name'];
            $this->data['p_complete_name_v2'] = $this->data['p_first_name']." ".$this->data['p_middle_name']." ".$this->data['p_family_name'];
            $this->data['p_registered_date'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
            	$this->data['p_modified_by']=\Auth::user()->creatorId();
            	 $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->_profile->updateData($request->input('id'),$this->data);
                $success_msg = 'Profile User updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Profile ".$this->data['p_first_name']." ".$this->data['p_family_name'];
            }else{
            	$this->data['p_registered_by']=\Auth::user()->creatorId();

            	$this->data['p_is_employee'] = 1;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                 
                $this->_profile->addData($this->data);
                $success_msg = 'Profile User added successfully.';
                $content = "User ".\Auth::user()->name." Added Profile ".$this->data['p_first_name']." ".$this->data['p_family_name'];
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
             if($request->ajax()){
                return response()->json(['status'=>'success','msg'=>$success_msg]);
            }else{
                return redirect()->route('bp.profileuser.index')->with('success', __($success_msg));
            }
    	}
        return view('profileuser.create',compact('data','arrBarangay','arrCountryCode','arrNature'));
	}


     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                /*'p_first_name'=>'required',*/
                /*'p_family_name'=>'required',*/
                /*'p_address_house_lot_no'=>'required',*/
                /*'p_address_subdivision'=>'required',*/
                'brgy_code'=>'required',
                
                // 'p_email_address' =>'required|email|unique:profiles,p_email_address,'.(int)$request->input('id'),
                'p_mobile_no'=>'required|unique:profiles,p_mobile_no,'.$request->input('id'),
               

            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['data'] = $_POST;
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }

    public function destroy($id)
    {
        
            $Subclass = PsicSubclass::find($id);
            if($Subclass->subclass_generated_by == \Auth::user()->creatorId()){
                $Subclass->delete();
                return redirect()->route('psicdivision.index')->with('success', __('Profile successfully deleted.'));
            }
            else{
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        
    }
}
