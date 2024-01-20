<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\GsoBacDesignations;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class GsoBacDesignationsController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $veriable = array(""=>"Please Select");
    public $section = array(""=>"Please Select");
    public $sub_moddule=array('' => "Please Select");
    public $app_name = [
        '1' => 'Abstract Of Canvass',
        '2' => 'Resolution'
    ];
     public function __construct(){
		$this->GsoBacDesignations= new GsoBacDesignations(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','employee_id'=>'','app_id'=>'','position'=>'','remarks'=>'','is_active' => "1");  
        $this->slugs = 'bac-designations'; 
    //     foreach ($this->GsoBacDesignations->allVeriable() as $val) {
    //         $this->veriable[$val->id]=$val->var_name;
    //    }
    //    foreach ($this->GsoBacDesignations->allSections() as $val) {
    //             $this->section[$val->id]=$val->section_name;
    //     }
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
        return view('GsoBacDesignations.index');

    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->GsoBacDesignations->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['fullname']=$row->fullname;
            $arr[$i]['department_name']=$row->department_name;
            $arr[$i]['app_name']=$this->app_name[$row->app_id];
            $arr[$i]['position']=$row->position;
		    // $remarks = wordwrap($row->remarks, 40, "<br />\n");
            // $arr[$i]['remarks']="<div class='showLess'>".$remarks."</div>";
            $arr[$i]['updated_at']=Carbon::parse($row->updated_at)->format('d-M-Y h:i A');

            $arr[$i]['status']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bac-designations/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit BAC Designation">
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
        $data=array('is_active' => $is_activeinactive);
        $this->GsoBacDesignations->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."'BAC Designation ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $department ="";
        $employee = $this->GsoBacDesignations->allHrEmployee();
        $application = $this->app_name;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->GsoBacDesignations->getEditDetails($request->input('id'));
            if($data->employee_id != null){
                $department=$this->GsoBacDesignations->get_emp_dept($data->employee_id);
            }
           
        }
        $sub_moddule=$this->sub_moddule;
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['is_active'] = 1;
            if($request->input('id')>0){
                $this->GsoBacDesignations->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'BAC Designation Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."'Updated BAC Designation '".$this->data['position']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['updated_by']=\Auth::user()->id;
                $this->data['updated_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->GsoBacDesignations->addData($this->data);
                $success_msg = 'BAC Designation added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."'Added BAC Designation '".$this->data['position']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('GsoBacDesignations.index')->with('success', __($success_msg));
    	}
        return view('GsoBacDesignations.create',compact('data','department','employee','application'));
	}
    
    
    public function formValidation(Request $request)
    {
        $arr = ['ESTATUS' => 0];
    
        $validator = \Validator::make($request->all(), [
            'employee_id' => 'required',
            'app_id' => [
                'required',
                Rule::unique('gso_bac_designations')->ignore($request->id)->where(function ($query) use ($request) {
                    return $query->where('employee_id', $request->employee_id);
                }),
            ],
            'position' => 'required',
        ]);
    
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
    
        echo json_encode($arr);
        exit;  
    }

    public function get_emp_dept(Request $request)
    {
        return $this->GsoBacDesignations->get_emp_dept($request->employee_id);
    }
    
    public function updateSigningSettings(Request $request){
       return $this->GsoBacDesignations->updateSigningSettings($request);
    }
   
}
