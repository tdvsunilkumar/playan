<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\TreasurerTaxCredit;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
class TreasurerTaxCreditCont extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
	 public $getFundCodes = array(""=>"Please Select");
	 public $getChargetypes = array(""=>"Please Select");
	 public $getGeneralledgers = array(""=>"Please Select");
	 public $arrDepaertments = array(""=>"Select Department");
	 public $arrSubsidiaryLeader = array();
     public function __construct(){
        $this->_TreasurerTaxCredit = new TreasurerTaxCredit(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','fund_id'=>'','ctype_id'=>'','tcm_gl_id'=>'','tcm_sl_id'=>'','pcs_id'=>'','rpt_category'=>'','tcm_remarks'=>'');  
        $this->slugs = 'treasurer-tax-credit';
		foreach ($this->_TreasurerTaxCredit->FundCodes() as $val) {
             $this->getFundCodes[$val->id]=$val->code." - ".$val->description;
        }
		
		foreach ($this->_TreasurerTaxCredit->Chargetypes() as $val) {
             $this->getChargetypes[$val->id]=$val->ctype_desc;
        }
		foreach ($this->_TreasurerTaxCredit->Generalledgers() as $val) {
             $this->getGeneralledgers[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        }
		
		foreach ($this->_TreasurerTaxCredit->PaymentCashierSystem() as $val) {
             $this->arrDepaertments[$val->id]=$val->pcs_name;
        }
		//$this->arrDepaertments = array(''=>'Select Department','1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous');
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('TreasurerTaxCredit.index');
    }
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_TreasurerTaxCredit->getList($request);
		$getFundCodes = $this->getFundCodes;
		$getChargetypes = $this->getChargetypes;
		$getGeneralledgers = $this->getGeneralledgers;
		$arrDepaertments = $this->arrDepaertments;
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/treasurer-tax-credit/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Tax Credit [ Account Assignment]">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->tcm_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
			
            $arr[$i]['srno']= $sr_no;
			$arr[$i]['fund_id']= $getFundCodes[$row->fund_id];
			$arr[$i]['tcm_sl_id']=$getGeneralledgers[$row->tcm_sl_id];
			$arr[$i]['pcs_id']= $arrDepaertments[$row->pcs_id];
			$arr[$i]['updated_at']= $row->updated_at;
            $arr[$i]['tcm_status']= ($row->tcm_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']= $actions;
           
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
        $data=array('tcm_status' => $is_activeinactive);
        $this->_TreasurerTaxCredit->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Pregnancy Test ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
		$getFundCodes = $this->getFundCodes;
		$getChargetypes = $this->getChargetypes;
		$getGeneralledgers = $this->getGeneralledgers;
		$arrDepaertments = $this->arrDepaertments;
        $arrCategory = array(''=>'Please Select');
        foreach ($this->_TreasurerTaxCredit->getPropertyKind() as $val) {
             $arrCategory[$val->id]=$val->pk_description;
        }
		$arrSubsidiaryLeader =$this->arrSubsidiaryLeader;
		$current_years=date('Y');
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_TreasurerTaxCredit->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['tfoc_is_applicable'] = $this->data['pcs_id'];
            if($request->input('id')>0){
                $this->_TreasurerTaxCredit->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
            }else{
				$this->data['created_by']=\Auth::user()->creatorId();
				$this->data['created_at'] = date('Y-m-d H:i:s');
				$this->data['tcm_status'] = 1;
				$request->id = $this->_TreasurerTaxCredit->addData($this->data);
				$success_msg = 'Added successfully.';				
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->back();
        }
        return view('TreasurerTaxCredit.create',compact('data','arrDepaertments','getFundCodes','getChargetypes','getGeneralledgers','arrSubsidiaryLeader','arrCategory'));
    }
	
    public function formValidation(Request $request){
        if($request->input('pcs_id') != 2){
            $validator = \Validator::make(
                $request->all(), [
             'tcm_sl_id'=>'required|unique:cto_tax_credit_management,tcm_sl_id,'.$request->input('id'),
             'pcs_id'=>'required'
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
        }else{
            $validator = \Validator::make(
            $request->all(),[
             'pcs_id' => 'required|unique:cto_tax_credit_management,pcs_id,' .$request->input('id'). ',id,rpt_category,' .$request->input('rpt_category'), 
             'tcm_sl_id'=>'required|unique:cto_tax_credit_management,tcm_sl_id,'.$request->input('id'),
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
	
    public function getCitizensname(Request $request){
	  $id = $request->input('id');
	  $data = $this->_TreasurerTaxCredit->getCitizensname($id);
	  return $data;
	}
	
	 public function getAccountDescription(Request $request){
      $getAccountDesc = $this->_TreasurerTaxCredit->getAccountDesc($request->input('agl_code'));
      $htmloption ="";
      foreach ($getAccountDesc as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->description.'</option>';
      }
      echo $htmloption."#".$value->gl_account_id;
    }
	
	
	public function arrdepaertmentscheck(Request $request){
	  $pcs_val = $request->input('pcs_val');
	  $data = DB::table('cto_tax_credit_management')->where('pcs_id','=',$pcs_val)->where('tcm_status','=',1)->count();
	  return $data;
	}
}
