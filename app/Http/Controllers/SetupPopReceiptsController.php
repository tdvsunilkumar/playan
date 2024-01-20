<?php

namespace App\Http\Controllers;

use App\Models\SetupPopReceipts;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class SetupPopReceiptsController extends Controller
{
    public $data = [];
    public function __construct(){
        $this->_setuppopreceipts = new SetupPopReceipts();
        
  $this->data = array('id'=>'','stp_type'=>'','code'=>'','stp_accountable_form'=>'','serial_no_from'=>'','serial_no_to'=>'','stp_qty'=>'','stp_value'=>'','stp_print'=>'1','is_active'=>'1');
        
        
    }
    public function index(Request $request)
    {
        
            return view('setuppopreceipts.index');
        
    }
    public function getList(Request $request){
        $data=$this->_setuppopreceipts->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
             $stp_type =""; if($row->stp_type =='1'){ $stp_type ='Business Permit & License';} elseif($row->stp_type =='2'){ $stp_type ='Real Property (Land Tax)'; }elseif($row->stp_type =='3'){ $stp_type ='Burial Permit'; }elseif($row->stp_type =='4'){ $stp_type ='Community Tax - Indivitual'; }elseif($row->stp_type =='5'){ $stp_type ='Community Tax - Crop'; }elseif($row->stp_type =='6'){ $stp_type ='Miscellaneous'; }
             $arr[$i]['srno']=$j;
             $arr[$i]['stp_type']=$stp_type;
            $arr[$i]['stp_accountable_form']=$row->stp_accountable_form;
            $arr[$i]['serial_no_from']=$row->serial_no_from;
            $arr[$i]['serial_no_to']=$row->serial_no_to;
            $arr[$i]['stp_qty']=$row->stp_qty;
            $arr[$i]['stp_value']=$row->stp_value;
            $arr[$i]['stp_print']=($row->stp_print==1?'<input type="checkbox" id='.$row->id.'  class="form-check-input printupdate" name="stp_print" value="1" checked>':'<input type="checkbox" class="form-check-input printupdate" id='.$row->id.' name="stp_print" value="" >');
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/payment-system/side-menu/setup-receipts/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Check Type Master">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-danger ms-2">
                    <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                    </a>
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
    
    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = SetupPopReceipts::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_setuppopreceipts->updateData($request->input('id'),$this->data);
                $success_msg = 'Setup Pop receipts updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
            
               
                $this->_setuppopreceipts->addData($this->data);
                $success_msg = 'Setup Pop receipts added successfully.';
            }
            return redirect()->route('setuppopreceipts.index')->with('success', __($success_msg));
        }
        return view('setuppopreceipts.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'stp_type'=>'required|unique:setup_pop_receipts,stp_type,'.$request->input('id'),
                // 'stp_type'=>'required',
               
                
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
    public function PrintOptionUpdate(Request $request){
             $id = $request->input('id');
             $is_print = $request->input('is_print');
             $data=array('stp_print' => $is_print);
             $this->_setuppopreceipts->updatePrint($id,$data);
    }
    
    public function Delete(Request $request){
            $id = $request->input('id');
            $SetupPopReceipts = SetupPopReceipts::find($id);
            if($SetupPopReceipts->created_by == \Auth::user()->creatorId()){
                $SetupPopReceipts->delete();
            }
    }
}
