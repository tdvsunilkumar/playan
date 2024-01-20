<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\SignApplications;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class SigningSettingsController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $veriable = array(""=>"Please Select");
    public $section = array(""=>"Please Select");
    public $sub_moddule=array('' => "Please Select");
     public function __construct(){
		$this->SignApplications= new SignApplications(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','menu_group_id'=>'','menu_module_id'=>'','menu_sub_id'=>'','section_id'=>'','var_id'=>'','var_name'=>'','remarks'=>'','status' => "1",'pos_x' => "",'pos_y' => "",'esign_pos_x' => "",'esign_pos_y' => "",'pos_x_end' => "",'pos_y_end' => "",'d_page_no' => "1",'esign_resolution' => "",'print_slug'=>'');  
        $this->slugs = 'signing-settings'; 
        foreach ($this->SignApplications->allVeriable() as $val) {
            $this->veriable[$val->id]=$val->var_name;
       }
       foreach ($this->SignApplications->allSections() as $val) {
                $this->section[$val->id]=$val->section_name;
        }
    }
    
    public function index(Request $request)
    {
		//$this->is_permitted($this->slugs, 'read');
        $modules = $this->SignApplications->allModuleMenus();
        $sign_settings=$this->SignApplications->getIpSettingStatus();
        if($sign_settings){
            $sign_settings_status = $sign_settings->value;
        }else{
            $sign_settings_status = 0;
        }
        return view('SignApplications.index',compact('sign_settings_status','modules'));

    }


    public function getList(Request $request){
       // $this->is_permitted($this->slugs, 'read');
        $data=$this->SignApplications->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['menu_desc']=($row->menu_groups_name != null && $row->menu_modules_name != null) ? $row->menu_groups_name ." => ".$row->menu_modules_name : "";
            $arr[$i]['menu_sub_modules_name']=$row->menu_sub_modules_name;
            $arr[$i]['var_name']=$row->var_name;
            $arr[$i]['section_name']=$row->section_name;
		    $remarks = wordwrap($row->remarks, 40, "<br />\n");
            $arr[$i]['remarks']="<div class='showLess'>".$remarks."</div>";
            $arr[$i]['created_by']=$row->fullname;
            $arr[$i]['created_at']= Carbon::parse($row->created_at)->format('d-M-Y h:i A');
            $arr[$i]['updated_at']=Carbon::parse($row->updated_at)->format('d-M-Y h:i A');

            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/signing-settings/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Signing Setting">
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
        $data=array('status' => $is_activeinactive);
        $this->SignApplications->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'Ip Security ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        $width_x = "";
        $height_y = "";
        $modules = $this->SignApplications->allModuleMenus();
        $veriable = $this->veriable;
        $section = $this->section;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->SignApplications->getEditDetails($request->input('id'));
            if($data->menu_sub_id != null){
                foreach ($this->SignApplications->get_sub_module($data->menu_module_id) as $val) {
                    $this->sub_moddule[$val->id]=$val->name;
               }
            }
           
        }
        $sub_moddule=$this->sub_moddule;
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['status'] = ($request->input('status') != null) ? 1 : 0 ;
            $this->data['menu_group_id']=$this->SignApplications->getMenuGroupId($request->input('menu_module_id'));
            $this->data['var_name']=$this->SignApplications->getVariableName($request->input('var_id'));
            $this->data['section_name']=$this->SignApplications->getSectionName($request->input('section_id'));
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->SignApplications->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Signing Settings Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."'Updated Signing Settings '".$this->data['var_name']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['updated_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                $lastinsertid = $this->SignApplications->addData($this->data);
                $success_msg = 'Signing Settings added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."'Added Signing Settings '".$this->data['var_name']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('SigningSettings.index')->with('success', __($success_msg));
    	}
        return view('SignApplications.create',compact('data','modules','veriable','sub_moddule','section','width_x','height_y'));
	}
    
    
    public function formValidation(Request $request)
    {
        $menu_sub_id = $request->input('menu_sub_id');
        $menuModuleId = $request->input('menu_module_id');
        $var_id = $request->input('var_id');
        $recordId = $request->input('id'); // Replace 'record_id' with the actual field name for the primary key.
        // Check if a record with the same values already exists, excluding the current record if $recordId is provided.
        // $query = DB::table('sign_applications')
        // ->where('menu_sub_id', $menu_sub_id)
        // ->where('menu_module_id', $menuModuleId)
        // ->where('var_id', $var_id);

        // if ($recordId !== null) {
        //     $query->where('id', '!=', $recordId);
        // }

        // $existingRecord = $query->first();

        $arr = ['ESTATUS' => 0];

        // if ($existingRecord) {
        //     // A record with the same values already exists
        //     $arr['field_name'] = "menu_module_id";
        //     $arr['error'] = 'Data with Menu Description, Sub-Menu, Variable already exists.';
        //     $arr['ESTATUS'] = 1;
        // } else {
            // Perform the rest of your validation
            $validator = \Validator::make(
                $request->all(), [
                    'menu_module_id' => 'required',
                    'section_id' => 'required',
                    'var_id'=>'required|unique:sign_applications,var_id,'.$request->input('id'),
                    'pos_x' => 'required',
                    'pos_y' => 'required',
                    'd_page_no' => 'required',
                    'pos_x_end' => 'required',
                    'pos_y_end' => 'required',
                    'esign_pos_x' => 'required',
                    'esign_pos_y' => 'required',
                    'esign_resolution' => 'required',
                ]
            );
            
            $menu_module_id = DB::table('menu_sub_modules')->where('menu_module_id',$menuModuleId)->where('is_active',1)->count();
            if ($menu_module_id > 0) {
                $validator = \Validator::make(
                    $request->all(), [
                        'menu_module_id' => 'required',
                        //'menu_sub_id' => 'required',
                        'section_id' => 'required',
                        'var_id'=>'required|unique:sign_applications,var_id,'.$request->input('id'),
                        'pos_x' => 'required',
                        'pos_y' => 'required',
                        'd_page_no' => 'required',
                        'pos_x_end' => 'required',
                        'pos_y_end' => 'required',
                        'esign_pos_x' => 'required',
                        'esign_pos_y' => 'required',
                        
                        ]
                );
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                $arr['field_name'] = $messages->keys()[0];
                $arr['error'] = $messages->all()[0];
                $arr['ESTATUS'] = 1;
            }
        // }

        echo json_encode($arr);
        exit;
    }

    public function get_sub_module(Request $request)
    {
        return $this->SignApplications->get_sub_module($request->menu_module_id);
    }
    
    public function updateSigningSettings(Request $request){
       return $this->SignApplications->updateSigningSettings($request);
    }
   
}
