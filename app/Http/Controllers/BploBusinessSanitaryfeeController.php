<?php

namespace App\Http\Controllers;
use App\Models\BploBusinessSanitaryfee;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BploBusinessSanitaryfeeController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $arrClassificationCode = array(""=>"Please Select");
    public $arrbbaCode = array(""=>"Please Select");
    public $optionarray = array('0'=>'None','1'=>'Basic By Activity','2'=>'Basic By Category','3'=>'By Area(Sq.M');
    public function __construct(){
		$this->_bploBusinessSanitaryfee = new BploBusinessSanitaryfee();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','bba_code'=>'','bsf_code'=>'','bsf_fee_option'=>'','is_active'=>'1','bbc_classification_code'=>'','bsf_fee_amount'=>'','bsf_tax_schedule'=>'','bsf_fee_schedule_option'=>'','bsf_sched'=>'','bsf_category_code'=>'','bsf_category_description'=>'','bsf_area_minimum'=>'','bsf_area_maximum'=>'','bsf_revenue_code'=>'','bsf_remarks'=>'');

        foreach ($this->_bploBusinessSanitaryfee->getTaxClasses() as $val){
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_bploBusinessSanitaryfee->getTaxTyeps() as $val){
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
         foreach ($this->_bploBusinessSanitaryfee->getbussinesscalsification() as $val){
            $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
        }
        
    }
    public function index(Request $request)
    {
        return view('bplobusinesssanitaryfee.index');
    }
    public function getList(Request $request){
        $data=$this->_bploBusinessSanitaryfee->getList($request);
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
            $arr[$i]['bsf_fee_option']=$this->optionarray[$row->bsf_fee_option];
            $schedule =""; if($row->bsf_tax_schedule =='1'){ $schedule ='Annual';} else{ $schedule ='Queterly'; }
            $arr[$i]['bsf_fee_amount']=$row->bsf_fee_amount;
            $arr[$i]['bsf_revenue_code']= wordwrap($row->bsf_revenue_code, 40, "<br />\n");
            $arr[$i]['bsf_remarks']=$row->bsf_remarks;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/fees-master/business-sanitary-fee/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Business Sanitary Fee ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  
                ;
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
        $this->_bploBusinessSanitaryfee->updateActiveInactive($id,$data);
}

    public function store(Request $request){
        $data = (object)$this->data;  
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessSanitaryfee::find($request->input('id'));
             foreach ($this->_bploBusinessSanitaryfee->getbussinessbyTaxtype($data->tax_type_id) as $val) {
               $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
            }
             foreach ($this->_bploBusinessSanitaryfee->getbussinessactivitybyid($data->bbc_classification_code) as $val) {
               $this->arrbbaCode[$val->id]=$val->bba_desc;
            }
        }
        $arrClassificationCode = $this->arrClassificationCode;
        $arrbbaCode = $this->arrbbaCode;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            if(isset($_POST['category_option']) && $_POST['bsf_fee_option'] =='2'){ 
                $this->data['bsf_fee_amount'] = $_POST['fee_amount'][$_POST['category_option']];
                $this->data['bsf_category_code'] = $_POST['cagetory_code'][$_POST['category_option']];
                $this->data['bsf_category_description'] = $_POST['category_Description'][$_POST['category_option']];
                $this->data['bsf_fee_schedule_option'] =""; 
                $this->data['bsf_tax_schedule'] =""; 
            }

             if(isset($_POST['area_option']) && $_POST['bsf_fee_option'] =='3'){
                $this->data['bsf_fee_amount'] = $_POST['fee_amount'][$_POST['area_option']];
                $this->data['bsf_area_minimum'] = $_POST['area_minimum'][$_POST['area_option']];
                $this->data['bsf_area_maximum'] = $_POST['area_maximum'][$_POST['area_option']];
                $this->data['bsf_fee_schedule_option'] =""; 
                $this->data['bsf_tax_schedule'] =""; 
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bploBusinessSanitaryfee->updateData($request->input('id'),$this->data);
                $success_msg = 'Business Sanitary Fee updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bussiness Sanitary Fee ".$this->data['id']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['bsf_registered_date'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid =$this->_bploBusinessSanitaryfee->addData($this->data);
                $success_msg = 'Business Sanitary Fee added successfully.';
                $content = "User ".\Auth::user()->name." Added Bussiness Sanitary Fee ".$lastinsertid; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinesssanitaryfee.index')->with('success', __($success_msg));
    	}
        return view('bplobusinesssanitaryfee.create',compact('data','arrTaxClasses','arrTaxTypes','arrClassificationCode','arrbbaCode'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                'bbc_classification_code'=>'required',
                'bba_code'=>'required',
                'bsf_revenue_code'=>'required',
                'bsf_remarks'=>'required',
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

    public function getSanitaryCategoryDropdown(Request $request){
        $classificationid= $request->input('classificationid');
        $bba_id= $request->input('activityid'); 
        $precatcode= $request->input('precode'); 

        $categoryarray = $this->_bploBusinessSanitaryfee->getSanitaryCategory($classificationid,$bba_id); $htmptable =""; $i=0;
        foreach ($categoryarray as $key => $val) { $checked="";
            if(trim($precatcode) == $val->code){ $checked ='checked';}
            $htmptable.="<tr><td style='padding-left: 10px'><input id='none' class='form-check-input' ".$checked." name='category_option' type='radio' value=".$i."></td>";
             $htmptable.="<td><input class='form-control' id='bpt_cagetory_code' name='cagetory_code[]' type='text' value=".$val->code."></td>";
              $htmptable.="<td><input class='form-control' id='category_Description' name='category_Description[]' type='text' value='".$val->category_Description."'></td>";
               $htmptable.="<td><input class='form-control' id='fee_amount' name='fee_amount[]' type='text' value=".$val->fee_amount."></td>";
                $htmptable.="<td><input class='form-control' id='ashed' name='ashed[]' type='text' value='A'></td></tr>";
                $i++;
        }
        echo $htmptable;
    }
    public function getSanitaryAreaDropdown(Request $request){
        $classificationid= $request->input('classificationid');
        $bba_id= $request->input('activityid'); 
        $precatcode= $request->input('feeamount'); 
        $categoryarray = $this->_bploBusinessSanitaryfee->getSanitaryArea($classificationid,$bba_id); $htmptable =""; $i=0;
        if(count($categoryarray) > 0){
        foreach ($categoryarray as $key => $val) { $checked="";
            if(trim($precatcode) == $val->fee_amount){ $checked ='checked';}
            $htmptable.="<tr><td style='padding-left: 10px'><input id='none' class='form-check-input' ".$checked." name='area_option' type='radio' value=".$i."></td>";
             $htmptable.="<td><input class='form-control' id='bpt_area_minimum' name='area_minimum[]' type='text' value=".$val->area_minimum."></td>";
              $htmptable.="<td><input class='form-control' id='bpt_area_maximum' name='area_maximum[]' type='text' value='".$val->area_maximum."'></td>";
               $htmptable.="<td><input class='form-control' id='fee_amount' name='fee_amount[]' type='text' value=".$val->fee_amount."></td>";
                $htmptable.="<td><input class='form-control' id='ashed' name='ashed[]' type='text' value='A'></td></tr>";
                $i++;
           } 
        } else{ //$htmptable.="<tr><td style='padding-left: 10px'>No Data Found</td></tr>"; 
        }
       
        echo $htmptable;
    }
}
