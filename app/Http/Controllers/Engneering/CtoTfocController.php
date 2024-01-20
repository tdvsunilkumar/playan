<?php

namespace App\Http\Controllers\Engneering;
use App\Models\CommonModelmaster;
use App\Models\Engneering\CtoTfoc;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CtoTfocController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $getFundCodes = array();
    public $arrChargestype = array(""=>"Please Select");
    public $arrGeneralReader = array(""=>"Please Select");
    public $arrSubsidiaryLeader = array();
    public $arrOcupancytype = array(""=>"Please Select");
    public $arrSubOcupancytype = array(""=>"Please Select");
    private $slugs;
    public function __construct(){
		$this->_engtfoc = new CtoTfoc();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','fund_id'=>'','ctype_id'=>'','tfoc_old_code'=>'','gl_account_id'=>'','sl_id'=>'','tfoc_short_name'=>'','tfoc_amount'=>'','tfoc_is_applicable'=>'','tfoc_usage_business_permit'=>'','tfoc_divided_fee'=>'','tfoc_iterated_fee'=>'','tfoc_common_fee'=>'','tfoc_interest_fee'=>'','tfoc_surcharge_fee'=>'','tfoc_fire_code_fee'=>'','tfoc_surcharge_interest_fee'=>'','tfoc_usage_engineering'=>'','tfoc_remarks'=>'','tfoc_status'=>'','total_of_sl_id'=>'','tfoc_surcharge_sl_id'=>'','tfoc_interest_sl_id'=>'','tfoc_eachlineof_bussiness'=>'','tfoc_usage_real_property'=>'','is_taxpayer_charges'=>'','tfoc_is_optional_fee'=>'','is_business_tax_essential'=>'','is_business_tax_non_essential'=>'');

         foreach ($this->_engtfoc->getFundCodes() as $val) {
             $this->getFundCodes[$val->id]=$val->code.'-'.$val->description;
         }
         foreach ($this->_engtfoc->getChargesType() as $val) {
             $this->arrChargestype[$val->id]=$val->ctype_desc;
         }
         foreach ($this->_engtfoc->getAccountGeneralLeader() as $val) {
             $this->arrGeneralReader[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
         }
         $this->slugs = 'engneering/engtfoc'; 
         //  foreach ($this->_engtfoc->getSubsidiaryLeader() as $val) {
         //     $this->arrSubsidiaryLeader[$val->id]=$val->code." - ".$val->description;
         // }
    }
    
    public function index(Request $request)
    {
          $arrDepaertments = array('0'=>'All');
         foreach ($this->_engtfoc->GetDepartmrntsArray() as $val) {
             $arrDepaertments[$val->id]=$val->pcs_name;
         }
           $this->is_permitted($this->slugs, 'read');
                return view('Engneering.engtfoc.index',compact('arrDepaertments'));
    }

     public function getTypeofchargesAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engtfoc->getTypeofchargesAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->ctype_desc;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function getChartofaccountAjax(Request $request){
        $search = $request->input('search');
        $arrRes = $this->_engtfoc->getChartofaccountAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }

    public function deleteothertaxes(Request $request){
      $id =$request->input('id');
        $this->_engtfoc->deleteOthertaxesrow($id);
    }

    public function getList(Request $request){
         $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_engtfoc->getList($request);
        //echo "<pre>"; print_r($data); exit;
         //$arrDepaertments = array(''=>'Select Department','1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous','10'=>'Economic and Investment');
          $arrDepaertments = array(''=>'Select Department');
         foreach ($this->_engtfoc->GetDepartmrntsArray() as $val) {
             $arrDepaertments[$val->id]=$val->pcs_name;
         }
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->tfoc_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['fundcode']=$row->fundcode;
            $arr[$i]['chargetype']=$row->chargetype;
            $arr[$i]['chartofaccount']=$row->chartofaccount;
            $essestial = "";
            if($row->tfoc_is_applicable =='1'){
              if($row->is_business_tax_essential ==1){  $essestial ="[Essential]";}
              if($row->is_business_tax_non_essential ==1){ $essestial ="[Non-Essential]";}
            $arr[$i]['accdesc']=$row->accdesc." ".$essestial;  
            }else{
            $arr[$i]['accdesc']=$row->accdesc;
            }
            // $arr[$i]['shortname']=$row->tfoc_short_name;
            $arr[$i]['tfoc_amount']=$row->tfoc_amount;
            $arr[$i]['tfoc_is_applicable']=$arrDepaertments[$row->tfoc_is_applicable];
            if($row->tfoc_usage_business_permit =='1' || $row->tfoc_usage_engineering=='1' || $row->tfoc_usage_real_property =='1'){
               $arr[$i]['tfoc_is_applicable']=$arrDepaertments[$row->tfoc_is_applicable].": Confirm";
            }
            //$arr[$i]['tfoc_usage_business_permit']=$row->tfoc_usage_business_permit;
            $arr[$i]['tfoc_status']=($row->tfoc_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engtfoc/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Tax, Fee & Other Charges">
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
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('tfoc_status' => $is_activeinactive);
        $this->_engtfoc->updateActiveInactive($id,$data);
    }

    public function getAccountDescription(Request $request){
      $getAccountDesc = $this->_engtfoc->getAccountDesc($request->input('agl_code'));
      $htmloption ="";
      foreach ($getAccountDesc as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->description.'</option>';
      }
      echo $htmloption."#".$value->gl_account_id;
    }

    public function getEssestialvalue(Request $request){
      $id = $request->input('chargeid');
      $chargedata = $this->_engtfoc->getChargesbyid($id);
      echo $chargedata->is_essential;
    }
       
    public function store(Request $request){
        $this->is_permitted($this->slugs, 'create');
        $data = (object)$this->data;
        $getFundCodes = $this->getFundCodes;
        $arrChargestype = $this->arrChargestype;
        //$arrGeneralReader = $this->arrGeneralReader;
        $arrGeneralReader = array(); $arrGeneralReadertotal = array();
        $arrSubsidiaryLeader =$this->arrSubsidiaryLeader;
        $othertaxesarr =array();
        $arrGeneralReadertotal = $this->arrGeneralReader;
        //$arrDepaertments = array(''=>'Select Department','1'=>'Business Permit', '2'=>'Real Property', '3'=>'Engineering', '4'=>'Occupancy', '5'=>'Planning and Development', '6'=>'Health & Safety','7'=>'Community Tax','8'=>'Burial Permit','9'=>'Miscellaneous','10'=>'Economic and Investment');
         $arrDepaertments = array(''=>'Select Department');
         foreach ($this->_engtfoc->GetDepartmrntsArray() as $val) {
             $arrDepaertments[$val->id]=$val->pcs_name;
         }
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = CtoTfoc::find($request->input('id'));
            $othertaxesarr = $this->_engtfoc->GetOthercharges($request->input('id'));

            foreach ($this->_engtfoc->getAccountGeneralLeaderedit($data->sl_id) as $val) {
             $arrGeneralReader[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
           }
           //  foreach ($this->_engtfoc->getAccountGeneralLeaderedit($data->total_of_sl_id) as $val) {
           //   $arrGeneralReadertotal[$val->id]="[".$val->code." - ".$val->gldescription."]=>[".$val->prefix." - ".$val->description."]";
           // }
        }
       
		if($request->input('submit')!=""){
            $totalglid ="0"; $surchargeglid="0"; $interestglid="0";
            if(!empty($request->input('total_of_sl_id'))){
               $totalglid = $this->_engtfoc->GetGlid($request->input('total_of_sl_id')); 
               $totalglid = $totalglid->gl_account_id;
            }
             if(!empty($request->input('tfoc_surcharge_sl_id'))){
               $surchargeglid = $this->_engtfoc->GetGlid($request->input('tfoc_surcharge_sl_id')); 
               $surchargeglid = $surchargeglid->gl_account_id;
            }
             if(!empty($request->input('tfoc_interest_sl_id'))){
               $interestglid = $this->_engtfoc->GetGlid($request->input('tfoc_interest_sl_id')); 
               $interestglid = $interestglid->gl_account_id;
            }
            $isapplicable = $request->input('tfoc_is_applicable');
            
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            
            if($isapplicable =='1' || $isapplicable =='2' || $isapplicable =='3' || $isapplicable =='4' || $isapplicable =='5' || $isapplicable =='7'){
              $this->data['is_taxpayer_charges']='1';
            }else if($isapplicable =='6' || $isapplicable =='8'){
              $this->data['is_taxpayer_charges']='0';
            }
            $this->data['tfoc_interest_gl_id']=$interestglid;
            $this->data['total_of_gl_id']=$totalglid;
            $this->data['tfoc_surcharge_gl_id']=$surchargeglid;
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($isapplicable =='1'){
              if($this->data['is_business_tax_essential'] =='2'){
                $this->data['is_business_tax_essential']=0;
              }
            }else{
             $this->data['is_business_tax_essential']=0;  $this->data['is_business_tax_non_essential']=0;
            }
            if($isapplicable =='9'){
                  $this->data['is_taxpayer_charges'] = (!empty($this->data['is_taxpayer_charges'])) ? $this->data['is_taxpayer_charges']:'0';
            }
            //echo "<pre>"; print_r($this->data); exit;
            if($request->input('id')>0){
              if($isapplicable !='9'){
                  unset($this->data['is_taxpayer_charges']);
                }
                $this->_engtfoc->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering  CTO TFOC updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->data['tfoc_interest_gl_id']=$interestglid;
                $this->data['total_of_gl_id']=$totalglid;
                $this->data['tfoc_surcharge_gl_id']=$surchargeglid;
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $lastinsertid = $this->_engtfoc->addData($this->data);
                $success_msg = 'Engineering CTO TFOC added successfully.';
            }
            //echo "<pre>"; print_r($_POST); exit;
            if($request->input('tfoc_is_applicable')=='3'){
              if(!empty($_POST['otaxes_sl_id'])){  
                foreach ($_POST['otaxes_sl_id'] as $key => $value) {
                    $othertaxesarray = array();
                    $othertaxesarray['tfoc_id'] =$lastinsertid;  
                    $giid = $this->_engtfoc->getAccountDesc($value);
                    $othertaxglid = $giid[0]->gl_account_id; 
                    $othertaxesarray['otaxes_gl_id'] = $othertaxglid;
                    $othertaxesarray['otaxes_sl_id'] = $value;
                    $othertaxesarray['tfoc_is_applicable'] = '3';
                    $othertaxesarray['otaxes_percent'] = $_POST['otaxes_percent'][$key];
                     if(!empty($_POST['otid'][$key])){
                        $othertaxesarray['updated_by']=\Auth::user()->id; 
                        $othertaxesarray['updated_at'] = date('Y-m-d H:i:s');
                        $this->_engtfoc->updateDataOthertaxes($_POST['otid'][$key],$othertaxesarray);
                    }else{
                        $othertaxesarray['created_by']=\Auth::user()->id;
                        $othertaxesarray['created_at'] = date('Y-m-d H:i:s');
                        $this->_engtfoc->addDataOthertaxes($othertaxesarray);
                    }
                 }
              }
            }
            return redirect()->route('engtfoc.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engtfoc.create',compact('data','getFundCodes','arrChargestype','arrGeneralReader','arrGeneralReadertotal','arrDepaertments','arrSubsidiaryLeader','othertaxesarr'));
	}
    
    
    public function formValidation(Request $request){
       if($request->input('tfoc_is_applicable') =='8'){
         $validator = \Validator::make(
            $request->all(), [
                'fund_id'=>'required',
                'tfoc_is_applicable'=>'required | unique:cto_tfocs,tfoc_is_applicable,'.(int)$request->input('id'),
                'sl_id'=>'required | unique:cto_tfocs,sl_id,'.(int)$request->input('id'). ',id,gl_account_id,' .$request->input('gl_account_id'),
                
            ]
        );
       }else if($request->input('tfoc_is_applicable') =='1'){
        if($request->input('is_business_tax_essential') =='1'){
          $validator = \Validator::make(
            $request->all(), [
                'fund_id'=>'required',
                'ctype_id'=>'required',
                'sl_id'=>'required | unique:cto_tfocs,sl_id,'.(int)$request->input('id'). ',id,ctype_id,' .$request->input('ctype_id'). ',tfoc_is_applicable,' .$request->input('tfoc_is_applicable').',is_business_tax_essential,' .$request->input('is_business_tax_essential'),
                'gl_account_id'=>'required',
                'tfoc_is_applicable'=>'required',
            ]);
           }
         else if($request->input('is_business_tax_non_essential') =='1'){
          $validator = \Validator::make(
            $request->all(), [
                'fund_id'=>'required',
                'ctype_id'=>'required',
                'sl_id'=>'required | unique:cto_tfocs,sl_id,'.(int)$request->input('id'). ',id,ctype_id,' .$request->input('ctype_id'). ',tfoc_is_applicable,' .$request->input('tfoc_is_applicable').',is_business_tax_non_essential,' .$request->input('is_business_tax_non_essential'),
                'gl_account_id'=>'required',
                'tfoc_is_applicable'=>'required',
            ]);
           } 
           else{
             $validator = \Validator::make(
            $request->all(), [
                'fund_id'=>'required',
                'ctype_id'=>'required',
                'sl_id'=>'required | unique:cto_tfocs,sl_id,'.(int)$request->input('id'). ',id,ctype_id,' .$request->input('ctype_id'). ',tfoc_is_applicable,' .$request->input('tfoc_is_applicable'),
                'gl_account_id'=>'required',
                'tfoc_is_applicable'=>'required',
            ]);
           }
       } 
       else{
          $validator = \Validator::make(
            $request->all(), [
                'fund_id'=>'required',
                'sl_id'=>'required | unique:cto_tfocs,sl_id,'.(int)$request->input('id'). ',id,gl_account_id,' .$request->input('gl_account_id'),
                'tfoc_is_applicable'=>'required',
            ]
        );
       } 
       
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
            $EngBldgOccupancyType = EngBldgOccupancyType::find($id);
            if($EngBldgOccupancyType->created_by == \Auth::user()->id){
                $EngBldgOccupancyType->delete();
            }
    }
}
