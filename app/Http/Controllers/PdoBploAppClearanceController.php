<?php

namespace App\Http\Controllers;

use App\Models\CommonModelmaster;
use App\Models\PdoBploAppClearance;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PdoBploAppClearanceController extends Controller
{
    
    public $data = [];
    public $postdata = [];
    public $yeararr = array(""=>"Select Year");
    public $arrbfpapplication = array(""=>"Select App");
    public $arrBfpAssess = array(""=>"Select Assessment");
    public $arrBfpInsOrder = array(""=>"Please Order");
    public function __construct(){
		$this->_pdobploappclearance = new PdoBploAppClearance();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','p_code'=>'','brgy_code'=>'','ba_code'=>'','ba_business_account_no'=>'','pbac_app_code'=>'','pbac_app_year'=>'','pbac_app_no'=>'','pbac_transaction_no'=>'','pbac_zoning_clearance_fee'=>'','pbac_is_paid'=>'','pbac_issuance_date'=>'','pbac_remarks'=>'','pbac_officer_position'=>'','pbac_approver_position'=>'');
        foreach ($this->_pdobploappclearance->getbploApplications() as $val) {
            $this->arrbfpapplication[$val->id]=$val->ba_business_account_no;
        } 
    }
    public function index(Request $request)
    {   
        
            return view('pdobploappclearance.index');
        
    }
    public function getList(Request $request){
        $data=$this->_pdobploappclearance->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0";  $z="1";  
        foreach ($data['data'] as $row){
            $arr[$i]['srno']=$z;    
            $arr[$i]['p_complete_name_v1']=$row->p_complete_name_v1;
            $arr[$i]['ba_business_account_no']=$row->ba_business_account_no;
            $arr[$i]['pbac_app_code']=$row->pbac_app_code;
            $arr[$i]['pbac_app_year']=$row->pbac_app_year;
            $arr[$i]['pbac_app_no']=$row->pbac_app_no;
            $arr[$i]['pbac_transaction_no']=$row->pbac_transaction_no;
            $arr[$i]['pbac_zoning_clearance_fee']=$row->pbac_zoning_clearance_fee;
            $arr[$i]['pbac_is_paid']=$row->pbac_is_paid;
            $arr[$i]['pbac_issuance_date']=date("M d, Y",strtotime($row->pbac_issuance_date));
            $arr[$i]['pbac_officer_position']=$row->pbac_officer_position;
            $arr[$i]['pbac_approver_position']=$row->pbac_approver_position;
            $arr[$i]['pbac_remarks']=$row->pbac_remarks;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/pdobploappclearance/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Pdo App Clearance">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
              
            $i++; $z++;
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
        $data = (object)$this->data;
        $bfpapplications =$this->arrbfpapplication;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = PdoBploAppClearance::find($request->input('id'));
            
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['pbac_approved_by']=\Auth::user()->creatorId();
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_pdobploappclearance->updateData($request->input('id'),$this->data);
                $success_msg = 'Pdo Bplo App Clearance updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bplo Requirement ".$this->data['req_description']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $lastinsertid = $this->_pdobploappclearance->addData($this->data);
                $success_msg = 'Pdo Bplo App Clearance added successfully.';
                $content = "User ".\Auth::user()->name." Added Pdo Bplo App Clearance id is ".$lastinsertid; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('pdobploappclearance.index')->with('success', __($success_msg));
        }
        return view('pdobploappclearance.create',compact('data','bfpapplications'));
        
    }

     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'p_code'=>'required',
                'pbac_app_code'=>'required',
                'pbac_app_year'=>'required', 
                'pbac_app_no'=>'required', 
                'pbac_transaction_no'=>'required',
                'pbac_zoning_clearance_fee'=>'required',
                'pbac_is_paid'=>'required',
                'pbac_issuance_date'=>'required'
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
    public function getpdoPbloClearancedetails(Request $request){
    	$id= $request->input('id');
        $data = $this->_pdobploappclearance->getBploApplictaions($id);
        echo json_encode($data);
    }
}
