<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\DashboardGroupMenu;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DashboardGroupMenuController extends Controller
{
     public $data = [];
     public $postdata = [];
	 public $arrMenugroup = array("" => "Please Select");
     private $slugs;
     public function __construct(){
        $this->_dashboardgroupmenu = new DashboardGroupMenu(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','menu_group_id'=>'','menu_name'=>'','slug'=>'','icon'=>'');  
        $this->slugs = 'dashboard-group-menu';
		
		foreach ($this->_dashboardgroupmenu->getMenugroup() as $val) {
            $this->arrMenugroup[$val->id]=$val->name;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('dashboardgroupmenu.index');
    }


    public function getList(Request $request){
		$arrMenuGroup =$this->arrMenugroup; 
        $this->is_permitted($this->slugs, 'read');
       

        
        $data=$this->_dashboardgroupmenu->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/dashboardgroupmenu/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Group menus">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            if($row->icon != null){
                $icon="<span class='".$row->icon."'></span>";
            }else{
                $icon="";
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['menu_group_id']=$arrMenuGroup[$row->menu_group_id];
			$arr[$i]['menu_name']=$row->menu_name;
            $arr[$i]['slug']=$row->slug;
            $arr[$i]['icon']=$icon;
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
        $this->_dashboardgroupmenu->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Group menus ".$action; 
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
            $data = $this->_dashboardgroupmenu->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_dashboardgroupmenu->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Group menus '".$this->data['menu_name']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $request->id = $this->_dashboardgroupmenu->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Group menus '".$this->data['menu_name']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('dashboardgroupmenu.index')->with('success', __($success_msg));
        }
        return view('dashboardgroupmenu.create',compact('data','arrMenuGroup'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'menu_group_id' =>'required',
                'menu_name'=>'required|unique:dashboard_group_menus,menu_name,'.(int)$request->input('id'),
                'slug'=>'required|unique:dashboard_group_menus,slug,'.(int)$request->input('id'),
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
