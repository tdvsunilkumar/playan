<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\SpecialAccessforApps;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class SpecialAccessforAppsController extends Controller
{
     public $data = [];
     public $postdata = [];
	 public $arrMenugroup = array("" => "Please Select");
	  public $sub_moddule=array("" => "Please Select");
     private $slugs;
     public function __construct(){
        $this->_specialaccessforapps = new SpecialAccessforApps(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','group_id'=>'','module_id'=>'','sub_module_id'=>'','application'=>'','remarks'=>'');  
        $this->slugs = 'dashboard-group-menu';
		
		foreach ($this->_specialaccessforapps->getMenugroup() as $val) {
            $this->arrMenugroup[$val->id]=$val->groups_name.' => '.$val->modules_name;
        }
    }
    public function index(Request $request)
    {
		$arrMenugroup = $this->arrMenugroup;
        $this->is_permitted($this->slugs, 'read');
        return view('SpecialAccess.index',compact('arrMenugroup'));
    }


    public function getList(Request $request){
		$arrMenuGroup =$this->arrMenugroup; 
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_specialaccessforapps->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/special-access-for-apps/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Application: Special Access">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
          
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['group_id']=$arrMenuGroup[$row->group_id];
			$arr[$i]['sub_module_id']=$row->name;
            $arr[$i]['application']=$row->application;
            $arr[$i]['remarks']=$row->remarks;
			$arr[$i]['created_at']=$row->created_at;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
        $this->_specialaccessforapps->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Groups | Module ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
		$arrMenuGroup =$this->arrMenugroup;
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
		if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_specialaccessforapps->getEditDetails($request->input('id'));
            if($data->sub_module_id != null){
                foreach ($this->_specialaccessforapps->get_sub_module($data->module_id) as $val) {
                    $this->sub_moddule[$val->id]=$val->name;
               }
            }
        }
		
        $submodulelist=$this->sub_moddule;
		
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
				//print_r($this->data);die;
				
                $this->_specialaccessforapps->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Groups | Module '"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['sub_module_id']=($request->input('sub_module_id') =='' )?'0':$request->input('sub_module_id');
                $this->data['is_active'] = 1;
                $request->id = $this->_specialaccessforapps->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Groups | Module '"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('special-access-for-apps.index')->with('success', __($success_msg));
        }
        return view('SpecialAccess.create',compact('data','arrMenuGroup','submodulelist'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'group_id' =>'required',
                'module_id'=>'required',
                'application'=>'required',
				//'sub_module_id'=>'required',
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
	
	public function getsubmodule(Request $request)
    {
        return $this->_specialaccessforapps->get_sub_module($request->menu_module_id);
    }
	
	public function get_module_id(Request $request)
    {
        return $this->_specialaccessforapps->get_module_id($request->module_id);
    }
   
}
