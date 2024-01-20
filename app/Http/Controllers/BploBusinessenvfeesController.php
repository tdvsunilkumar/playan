<?php

namespace App\Http\Controllers;
use App\Models\BploBusinessEnvFee;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BploBusinessenvfeesController extends Controller
{
  

    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $arrClassificationCode = array(""=>"Please Select");
    public $arrbbaCode = array(""=>"Please Select");
    public $optionarray = array('0'=>'None','1'=>'Basic By Activity','2'=>'Basic By Category','3'=>'By Area(Sq.M');
    public function __construct(){
		$this->_bplobusinessenvfees = new BploBusinessEnvFee();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','bba_code'=>'','bbef_code'=>'','bbef_fee_option'=>'','is_active'=>'1','bbc_classification_code'=>'','bbef_fee_amount'=>'','bbef_tax_schedule'=>'','bbef_fee_schedule_option'=>'','bbef_fee_amount_not_in_revenue'=>'','bbef_sched'=>'','bbef_category_code'=>'','bbef_category_description'=>'','bbef_area_minimum'=>'','bbef_area_maximum'=>'','bbef_revenue_code'=>'','bbef_remarks'=>'');

        foreach ($this->_bplobusinessenvfees->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_bplobusinessenvfees->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
         foreach ($this->_bplobusinessenvfees->getbussinesscalsification() as $val) {
            $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
        }
        
    }
    public function index(Request $request)
    {
        
        return view('bplobusinessenvfees.index');
        
    }
    public function getList(Request $request){
        $data=$this->_bplobusinessenvfees->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['tax_class_desc']=$row->tax_class_desc;
            $arr[$i]['tax_type_short_name']=$row->tax_type_short_name;
            $bbc_classification_desc = wordwrap($row->bbc_classification_desc, 40, "<br />\n");
            $arr[$i]['bbc_classification_desc']= "<div class='showLess'>".$bbc_classification_desc."</div>"; 
            $arr[$i]['bba_code']=$row->bba_code;
            $arr[$i]['bbef_fee_option']=$this->optionarray[$row->bbef_fee_option];
            $arr[$i]['bbef_fee_amount']=$row->bbef_fee_amount;
            $schedule =""; if($row->bbef_tax_schedule =='1'){ $schedule ='Annual';} else{ $schedule ='Queterly'; }
            $arr[$i]['bbef_tax_schedule']=$schedule;
            $arr[$i]['bbef_revenue_code']=$row->bbef_revenue_code;
            $arr[$i]['bbef_remarks']=$row->bbef_remarks;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/fees-master/environmental-fee/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Business Environmental ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>';
                // <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
                //     </a>
                // </div>
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
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_bplobusinessenvfees->updateActiveInactive($id,$data);
}

    public function store(Request $request){
        $data = (object)$this->data;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessEnvFee::find($request->input('id'));
             foreach ($this->_bplobusinessenvfees->getbussinessbyTaxtype($data->tax_type_id) as $val) {
               $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
            }
            foreach ($this->_bplobusinessenvfees->getbussinessactivitybyid($data->bbc_classification_code) as $val) {
               $this->arrbbaCode[$val->id]=$val->bba_desc;
            }
        }
        $arrClassificationCode = $this->arrClassificationCode;
        $arrbbaCode = $this->arrbbaCode;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bplobusinessenvfees->updateData($request->input('id'),$this->data);
                $success_msg = 'Business Environmental Fee updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bussiness Environment Fee ".$this->data['id']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['bsf_registered_date'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_bplobusinessenvfees->addData($this->data);
                $success_msg = 'Bussiness Environmental Fee added successfully.';
                $content = "User ".\Auth::user()->name." Added Business Environment Fee ".$lastinsertid; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinessenvfees.index')->with('success', __($success_msg));
    	}
        return view('bplobusinessenvfees.create',compact('data','arrTaxClasses','arrTaxTypes','arrClassificationCode','arrbbaCode'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                'bbc_classification_code'=>'required',
                'bba_code'=>'required', 
                'bbef_fee_option'=>'required',
                'bbef_fee_amount'=>'required',
                'bbef_tax_schedule'=>'required',
                'bbef_fee_schedule_option'=>'required',
                'bbef_revenue_code'=>'required',
                'bbef_remarks'=>'required',
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

  public function Delete(Request $request){
        $id = $request->input('id');
            $BploBusinessEnvFee = BploBusinessEnvFee::find($id);
            if($BploBusinessEnvFee->created_by == \Auth::user()->creatorId()){
                $BploBusinessEnvFee->delete();
            }
    }
}
