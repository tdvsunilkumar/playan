<?php

namespace App\Http\Controllers;
use App\Models\BploBusinessTax;
use App\Models\CommonModelmaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploBussinessTaxController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $arrClassificationCode = array(""=>"Please Select");
    public $arrbbaCode = array(""=>"Please Select");
    public $optionarray = array('1'=>'Tax Amount','4'=>'Graduated % of Gross','3'=>'Tax By Percentage OF Gross','2'=>'% of Gross or Minimum Amount,');
    public function __construct(){
		$this->_bploBusinessTax = new BploBusinessTax();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','bbc_classification_code'=>'','bbt_grsar_minimum'=>'','bbt_grsar_maximum'=>'','bbt_tax_amount'=>'','bbt_initial_tax_amount'=>'','bbt_taxation_percent'=>'','bbt_taxation_procedure'=>'','bbt_taxation_schedule'=>'');

        foreach ($this->_bploBusinessTax->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_bploBusinessTax->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->type_code."-".$val->tax_type_short_name;
        }
         foreach ($this->_bploBusinessTax->getbussinesscalsification() as $val) {
            $this->arrClassificationCode[$val->id]=$val->bbc_classification_code;
        }
        
    }

    
    public function index(Request $request)
    {
        return view('bplobusinesstax.index');
    }
    public function getList(Request $request){
        $data=$this->_bploBusinessTax->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $arr[$i]['id']=$row->id;
            $arr[$i]['tax_class_desc']=$row->tax_class_desc;
            $arr[$i]['tax_type_short_name']=$row->tax_type_short_name;
            $arr[$i]['bbc_classification_code']=$row->bbc_classification_code;
            $arr[$i]['grossminimum']=$row->bbt_grsar_minimum;
            $arr[$i]['grossmaximum']=$row->bbt_grsar_maximum;
            $arr[$i]['taxamount']=$row->bbt_tax_amount;
            $arr[$i]['taxpercent']=$row->bbt_taxation_percent;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinesstax/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Basic And Excess Graduated Tax ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div> 
                ';
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
    
    public function getOptionDetails($arrJson=''){
        $garduatesoptionArr= array();
            $arr = json_decode($arrJson,true);
            foreach($arr as $key=>$val){
                $garduatesoptionArr[$key]['minimumgross']=$val['minimumgross'];
                $garduatesoptionArr[$key]['maximumgross']=$val['maximumgross'];
                $garduatesoptionArr[$key]['taxamount']=$val['taxamount'];
                $garduatesoptionArr[$key]['initialtaxamount']=$val['initialtaxamount']; 
                $garduatesoptionArr[$key]['taxationpercent']=$val['taxationpercent'];
                $garduatesoptionArr[$key]['taxationprocedure']=$val['taxationprocedure'];
                $garduatesoptionArr[$key]['taxschedule']=$val['taxschedule'];
            
        }
        return $garduatesoptionArr;
    }
    public function store(Request $request){
        $data = (object)$this->data; $garduatesoptionArr = array();
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;
        //print_r($arrTaxTypes); exit;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessTax::find($request->input('id'));
             foreach ($this->_bploBusinessTax->getbussinessactivitybyid($data->bbc_classification_code) as $val) {
               $this->arrbbaCode[$val->id]=$val->bba_desc;
            }
            $garduatesoptionArr = $this->getOptionDetails($data->alloptionjson);
            
        }
        $arrClassificationCode = $this->arrClassificationCode;
        $arrbbaCode = $this->arrbbaCode;  
        $optionarray =$this->optionarray;
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
             if(!empty($request->input('minimumgross')[0])){
                $this->data['bbt_grsar_minimum']=$request->input('minimumgross')[0];
                $this->data['bbt_grsar_maximum']=$request->input('maximumgross')[0];
                $this->data['bbt_tax_amount']=$request->input('taxamount')[0];
                $this->data['bbt_initial_tax_amount']=$request->input('initialtaxamount')[0];
                $this->data['bbt_taxation_percent']=$request->input('taxationpercent')[0];
                $this->data['bbt_taxation_procedure']=$request->input('taxationprocedure')[0];
                $this->data['bbt_taxation_schedule']=$request->input('taxschedule')[0];
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bploBusinessTax->updateData($request->input('id'),$this->data);
                $success_msg = 'Graduated Tax updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Graduated tax ".$this->data['id']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid =$this->_bploBusinessTax->addData($this->data);
                $success_msg = 'Graduated Tax added successfully.';
                $content = "User ".\Auth::user()->name." Added Graduated tax ".$lastinsertid; 
                $request->id =$lastinsertid; 
            }
            if($request->id>0){
                $this->addBussinesstaxOption($request);
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinesstax.index')->with('success', __($success_msg));
        }
        return view('bplobusinesstax.create',compact('data','arrTaxClasses','arrTaxTypes','arrClassificationCode','optionarray','garduatesoptionArr'));
        
    }
    public function addBussinesstaxOption($request){
        $minimumgross = $request->input('minimumgross');
        $arr = array();
        $i=0;
        foreach ($minimumgross as $key => $value) {
            if(!empty($request->input('minimumgross')[$key])){
                $arr[$i]['minimumgross']=$request->input('minimumgross')[$key];
                $arr[$i]['maximumgross']=$request->input('maximumgross')[$key];
                $arr[$i]['taxamount']=$request->input('taxamount')[$key];
                $arr[$i]['initialtaxamount']=$request->input('initialtaxamount')[$key];
                $arr[$i]['taxationpercent']=$request->input('taxationpercent')[$key];
                $arr[$i]['taxationprocedure']=$request->input('taxationprocedure')[$key];
                $arr[$i]['taxschedule']=$request->input('taxschedule')[$key];
                $i++;
            }
        }
        if(count($arr)>0){
            $json = json_encode($arr);
            $arrData=array("alloptionjson"=>$json);
            $this->_bploBusinessTax->updateData($request->id,$arrData);
        }
    }
     public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_type_id'=>'required', 
                'bbc_classification_code'=>'required',
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
            $BploBusinesstax = BploBusinessTax::find($id);
            if($BploBusinesstax->created_by == \Auth::user()->creatorId()){
                $BploBusinesstax->delete();
            }
    }

}
