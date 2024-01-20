<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\BfpFeesMaster;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class BfpFeesMasterController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_Bfpfeesmaster = new BfpFeesMaster(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','fmaster_code'=>'','fmaster_description'=>'','fmaster_shortname'=>'', 'fmaster_subdetails_json'=>'');  
        $this->slugs = 'business-fee-master';
    }
    public function index(Request $request)
    {
       // $this->is_permitted($this->slugs, 'read');
        return view('Bplo.feemaster.index');
    }
     public function getList(Request $request){
        // $this->is_permitted($this->slugs, 'read');
        $data=$this->_Bfpfeesmaster->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
           
            $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-fee-master/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Fees Master">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
        
            $actions .=($row->fmaster_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
          
            $arr[$i]['srno']=$sr_no;
            $fmaster_description = wordwrap($row->fmaster_description, 40, "<br />\n");
            $arr[$i]['fmaster_description']= "<div class='showLess'>".$fmaster_description."</div>"; 
            
            $arr[$i]['fmaster_code']=$row->fmaster_code;
            $arr[$i]['fmaster_shortname']=$row->fmaster_shortname;
            $arr[$i]['fmaster_status']=($row->fmaster_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
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
    
    public function FeesMasterActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('fmaster_status' => $is_activeinactive);
        $this->_Bfpfeesmaster->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo Formula ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function getcheckboxes(Request $request){
        $feeid = $request->input('feeid');
        $data = $this->_Bfpfeesmaster->getEditDetails($feeid);
        //echo "<pre>"; print_r($data); exit;

        if(!empty($data->fmaster_subdetails_json)){
            $checkboxarray = json_decode($data->fmaster_subdetails_json);
            //print_r($checkboxarray); exit;
            $html="";
            foreach ($checkboxarray as $k => $val) {
                $html.='<div class="col-sm-3"><input type="checkbox" name="check'.$val->key.'"><label>'.$val->value.'</label></div>';
            }
            echo $html;
        }
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            //$this->is_permitted($this->slugs, 'update');
        }else{
            //$this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_Bfpfeesmaster->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $checkboxes ="";
            if(!empty($_POST['checkboxesdynamic'])){
            	$checkboxarray =array(); $i=0;
            	foreach ($_POST['checkboxesdynamic'] as $key => $value) {
            		$checkboxarray[$i]['key'] = $key;
            		$checkboxarray[$i]['value'] = $value; $i++;
            	}
            	$checkboxes =json_encode($checkboxarray);
            }
            $this->data['fmaster_subdetails_json'] = $checkboxes; 
            //echo "<pre>"; print_r($this->data); exit;
            if($request->input('id')>0){
                $this->_Bfpfeesmaster->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Bfp Fee Master '".$this->data['fmaster_code']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['fmaster_status'] = 1;
                $request->id = $this->_Bfpfeesmaster->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Bfp Fee Master '".$this->data['fmaster_code']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);

            session()->flash('success', $success_msg);
            return redirect()->back();
            //return redirect()->route('businessfeemaster.index')->with('success', __($success_msg));
        }
        return view('Bplo.feemaster.create',compact('data'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'fmaster_description'=>'required|unique:bfp_fees_masters,fmaster_description,'.(int)$request->input('id'),
				"fmaster_code" => "required",
				"fmaster_shortname" => "required",
            ],[
				"fmaster_description.unique" => "Fee Description has already been taken",
				"fmaster_description.required" => "Fee Description is required",
				"fmaster_code.required" => "Account Code is required",
				"fmaster_shortname.required" => "Short name is required",
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
