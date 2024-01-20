<?php

namespace App\Http\Controllers\SocialWelfare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWelfare\Policy;

class PolicyController extends Controller
{
    public $data = [];
    private $slugs;

    public function __construct(){

        $this->slugs = 'social-welfare/setup-data/policy';
        $this->_Policy = new Policy(); 
        $this->data = array(
            'id'=>'',
            'wps_key'=>'',
            'wps_value'=>'',
            'wps_note'=>'',
        );  
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        return view('SocialWelfare.Policy.index');
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_Policy->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a data-url="'.url($this->slugs.'/store?id='.$row->id).'" class="mx-3 btn btn-sm  align-items-center" data-size="md" data-ajax-popup="true" data-bs-toggle="tooltip" title="Manage Assistance" data-title="Manage Assistance">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            // if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
            //     $actions .=($row->wswa_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.' data-bs-toggle="tooltip" title="Remove Assistance"></a>' : 
            //         '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white" data-bs-toggle="tooltip" title="Restore Assistance"  name="stp_print" value="1" id='.$row->id.'></a>';  
            // }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['key']=$row->wps_key;
            $arr[$i]['value']=$row->wps_value;
            $arr[$i]['notes'] = '';//wps_remarks
            $arr[$i]['is_active'] = '';//wps_is_active
            // $arr[$i]['is_active']=($row->wswa_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
    
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

    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
        $data->slug = $this->slugs;
        if($request->input('id')>0 && $request->input('submit')==""){            
            $data = $this->_Policy->find($request->input('id'));
        }

        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            if($request->input('id')>0){
                $this->_Policy->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
            }else{
                $this->data['wswa_is_active'] = 1;
                $request->id = $this->_Policy->addData($this->data);
                $success_msg = 'Added successfully.';
            }
            return redirect()->route('sw_policy.index')->with('success', __($success_msg));
        }

        return view('SocialWelfare.Policy.create',compact('data'));
    }

    /* public function formValidation(Request $request){
        $rule = [
            "wps_key" => "required|unique:welfare_policy_settings,wps_key",
            "wps_value" => "required",
        ];
        if ($request->id) {
            $rule = [
                "wps_key" => "required",
                "wps_value" => "required",
            ];
        }
        $validator = \Validator::make(
            $request->all(), $rule
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    } */
	public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
				'wps_key'=>'required|unique:welfare_policy_settings,wps_key,'.(int)$request->input('id'),
				"wps_value" => "required",
            ],[
			  'wps_key.required' => 'The description key field is required.',
			  'wps_key.unique' => 'The description key has already been taken.',
			  'wps_value.required' => 'The description value field is required.',
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
	
    public function ActiveInactive(Request $request)
    {
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('wswa_is_active' => $is_activeinactive);
        $this->_Policy->updateData($id,$data);
    }
}
