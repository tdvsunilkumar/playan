<?php

namespace App\Http\Controllers;
use App\Models\BploBusinessPermitfee;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BploBusinessPermitfeeController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $arrClassificationCode = array(""=>"Please Select");
    public $arrbbaCode = array(""=>"Please Select");
    public $optionarray = array('0'=>'None','1'=>'Basic Fee','2'=>'By Category','3'=>'By Area','4'=>'By Tax Paid');
    public function __construct(){
		$this->_bploBusinessPermitfee = new BploBusinessPermitfee();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','bpt_recno'=>'','tax_class_id'=>'','tax_type_id'=>'','bba_code'=>'','bpf_code'=>'','bpt_fee_option'=>'0','bpt_amount_w_machine'=>'','is_active'=>'1','bbc_classification_code'=>'','bpt_amount_wo_machine'=>'','bpt_permit_fee_amount'=>'','bpt_tax_schedule'=>'','bpt_item_count'=>'','bpt_sched'=>'','bpt_additional_fee'=>'','bpt_fee_schedule_option'=>'0','bpt_cagetory_code'=>'','bpt_cagetory_desc'=>'','bpt_area_minimum'=>'','bpt_area_maximum'=>'','bpt_capital_asset_minimum'=>'','bpt_capital_asset_maximum'=>'','bpt_workers_no_minimum'=>'','bpt_workers_no_maximum'=>'','bpt_revenue_code'=>'','bpt_remarks'=>'');

        foreach ($this->_bploBusinessPermitfee->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_bploBusinessPermitfee->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
        
        
    }
    public function index(Request $request)
    {
        return view('bplobusinesspermitfee.index');
    }
    public function getactivityCodebyid(Request $request){
    $getgroups = $this->_bploBusinessPermitfee->getbussinessactivitybyid($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->bba_desc.'</option>';
      }
      echo $htmloption;
    }
    public function getbussinessbyTaxtype(Request $request){
       $getgroups = $this->_bploBusinessPermitfee->getbussinessbyTaxtype($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->bbc_classification_desc.'</option>';
      }
      echo $htmloption;
    }
      public function getbussinessbyTaxtypenew(Request $request){
       $getgroups = $this->_bploBusinessPermitfee->getbussinessbyTaxtype($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->bbc_classification_code.'</option>';
      }
      echo $htmloption;
    }

    public function getList(Request $request){
        $data=$this->_bploBusinessPermitfee->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['bpt_recno']=$row->id;
            $tax_class_desc = wordwrap($row->tax_class_desc, 40, "<br />\n");
            $arr[$i]['tax_class_desc']="<div class='showLess'>".$tax_class_desc."</div>";
            $arr[$i]['tax_type_short_name']=$row->tax_type_short_name;
            $bbc_classification_desc = wordwrap($row->bbc_classification_desc, 40, "<br />\n");
            $arr[$i]['bbc_classification_desc']="<div class='showLess'>".$bbc_classification_desc."</div>";
            $arr[$i]['bpt_permit_fee_amount']=$row->bpt_permit_fee_amount;
            $arr[$i]['bpt_fee_option']=$this->optionarray[$row->bpt_fee_option];
            $schedule =""; if($row->bpt_tax_schedule =='1'){ $schedule ='Annual';} else{ $schedule ='Queterly'; }
            $arr[$i]['bpt_tax_schedule']=$schedule;
            $arr[$i]['bpt_revenue_code']=$row->bpt_revenue_code;
            $arr[$i]['bpt_remarks']=$row->bpt_remarks;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/fees-master/business-permit-fee/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Bussiness Permit Fee ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  
                ;
                //  <div class="action-btn bg-danger ms-2">
                //     <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinesspermitfee/destroy?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Delete"  data-title=""Bussiness Permit Fee Delete">
                //        <i class="ti-trash text-white text-white"></i>
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
        $this->_bploBusinessPermitfee->updateActiveInactive($id,$data);
}
    
    public function store(Request $request){
        $data = (object)$this->data;  $categoryarray = array();
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;
        
        //echo "<pre>"; print_r($_POST); exit;
       
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessPermitfee::find($request->input('id'));
             foreach ($this->_bploBusinessPermitfee->getbussinessbyTaxtype($data->tax_type_id) as $val) {
               $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
            }
             foreach ($this->_bploBusinessPermitfee->getbussinessactivitybyid($data->bbc_classification_code) as $val) {
               $this->arrbbaCode[$val->id]=$val->bba_desc;
            }
            
        }
        $arrClassificationCode = $this->arrClassificationCode;
        $arrbbaCode = $this->arrbbaCode;
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
           // echo "<pre>"; print_r($_POST); exit;
            if(isset($_POST['category_option']) && $_POST['bpt_fee_option'] =='2'){
                $this->data['bpt_permit_fee_amount'] = $_POST['fee_amount'][$_POST['category_option']];
                $this->data['bpt_cagetory_code'] = $_POST['bpt_cagetory_code'][$_POST['category_option']];
                $this->data['bpt_cagetory_desc'] = $_POST['category_Description'][$_POST['category_option']];
                $this->data['bpt_item_count'] =""; $this->data['bpt_additional_fee'] ="";  $this->data['bpt_fee_schedule_option'] =""; 
                $this->data['bpt_tax_schedule'] =""; 
            }

             if(isset($_POST['area_option']) && $_POST['bpt_fee_option'] =='3'){
                $this->data['bpt_permit_fee_amount'] = $_POST['fee_amount'][$_POST['area_option']];
                $this->data['bpt_area_minimum'] = $_POST['area_minimum'][$_POST['area_option']];
                $this->data['bpt_area_maximum'] = $_POST['area_maximum'][$_POST['area_option']];
                $this->data['bpt_item_count'] =""; $this->data['bpt_additional_fee'] ="";  $this->data['bpt_fee_schedule_option'] =""; 
                $this->data['bpt_tax_schedule'] =""; 
            }
            
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bploBusinessPermitfee->updateData($request->input('id'),$this->data);
                $success_msg = 'Business Permit Fee updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bussiness Permit Fee ".$this->data['id'];
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_bploBusinessPermitfee->addData($this->data);
                $success_msg = 'Business Permit Fee added successfully.';
                $content = "User ".\Auth::user()->name." Added Bussiness Permit Fee ".$lastinsertid;
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinesspermitfee.index')->with('success', __($success_msg));
    	}
        return view('bplobusinesspermitfee.create',compact('data','arrTaxClasses','arrTaxTypes','arrClassificationCode','arrbbaCode'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                'bbc_classification_code'=>'required',
                'bba_code'=>'required', 
                'bpt_fee_option'=>'required',
                'bpt_revenue_code'=>'required',
                'bpt_revenue_code'=>'required',
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

    public function destroy($id)
    {
        
        $BploBusinessPermitfee = BploBusinessPermitfee::find($id);
        if($BploBusinessPermitfee->created_by == \Auth::user()->creatorId()){
            $BploBusinessPermitfee->delete();
            return redirect()->route('bplobusinesspermitfee.index')->with('success', __('Permit Fee successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

    public function getActivitybbaCode(Request $request){
        $id= $request->input('id');
        $data = $this->_bploBusinessPermitfee->getActivitybbaCode($id);
        echo json_encode($data);
    }

    public function getCategoryDropdown(Request $request){
        $classificationid= $request->input('classificationid');
        $bba_id= $request->input('activityid'); 
        $precatcode= $request->input('precode'); 
        
        $categoryarray = $this->_bploBusinessPermitfee->getPermitCategory($classificationid,$bba_id); $htmptable =""; $i=0;
        foreach ($categoryarray as $key => $val) { $checked="";
            if(trim($precatcode) == $val->code){ $checked ='checked';}
            $htmptable.="<tr><td style='padding-left: 10px'><input id='none' class='form-check-input' ".$checked." name='category_option' type='radio' value=".$i."></td>";
             $htmptable.="<td><input class='form-control' id='bpt_cagetory_code' name='bpt_cagetory_code[]' type='text' value=".$val->code."></td>";
              $htmptable.="<td><input class='form-control' id='category_Description' name='category_Description[]' type='text' value='".$val->category_Description."'></td>";
               $htmptable.="<td><input class='form-control' id='fee_amount' name='fee_amount[]' type='text' value=".$val->fee_amount."></td>";
                $htmptable.="<td><input class='form-control' id='ashed' name='ashed[]' type='text' value='A'></td></tr>";
                $i++;
        }
        echo $htmptable;
    }
    public function getAreaDropdown(Request $request){
        $classificationid= $request->input('classificationid');
        $bba_id= $request->input('activityid'); 
        $precatcode= $request->input('feeamount'); 
        $categoryarray = $this->_bploBusinessPermitfee->getPermitArea($classificationid,$bba_id); $htmptable =""; $i=0;
        foreach ($categoryarray as $key => $val) { $checked="";
            if(trim($precatcode) == $val->fee_amount){ $checked ='checked';}
            $htmptable.="<tr><td style='padding-left: 10px'><input id='none' class='form-check-input' ".$checked." name='area_option' type='radio' value=".$i."></td>";
             $htmptable.="<td><input class='form-control' id='bpt_area_minimum' name='area_minimum[]' type='text' value=".$val->area_minimum."></td>";
              $htmptable.="<td><input class='form-control' id='bpt_area_maximum' name='area_maximum[]' type='text' value='".$val->area_maximum."'></td>";
               $htmptable.="<td><input class='form-control' id='fee_amount' name='fee_amount[]' type='text' value=".$val->fee_amount."></td>";
                $htmptable.="<td><input class='form-control' id='ashed' name='ashed[]' type='text' value='A'></td></tr>";
                $i++;
        }
        echo $htmptable;
    }
}
