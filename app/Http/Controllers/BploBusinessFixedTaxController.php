<?php

namespace App\Http\Controllers;
use App\Models\BploBusinessFixedTax;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BploBusinessFixedTaxController extends Controller
{
    
     public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public $arrClassificationCode = array(""=>"Please Select");
    public $arrbbaCode = array(""=>"Please Select");
    public $optionarray = array('0'=>'None','1'=>'Basic By Activity','2'=>'Basic By Category','3'=>'By Area(Sq.M');
    public function __construct(){
        $this->_bplobusinessfixedtax = new BploBusinessFixedTax();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','tax_class_id'=>'','bba_code'=>'','bbc_classification_code'=>'','bft_tax_amount'=>'','bft_item_count'=>'0','bft_additional_tax'=>'0','bft_taxation_procedure'=>'','bft_taxation_schedule'=>'');
        foreach ($this->_bplobusinessfixedtax->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name; 
        } 
        foreach ($this->_bplobusinessfixedtax->getTaxClasses() as $val) {
           $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
       

        }
        // foreach ($this->_bplobusinessfixedtax->getcodebyid() as $val) {
        //    $this->arrbbaCode[$val->id]=$val->bba_desc;
        // }

         foreach ($this->_bplobusinessfixedtax->getbussinesscalsification2() as $val) {
            $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
        }
        
    }
    public function index(Request $request)
    {
        return view('bplobusinessfixedtax.index');
    }
    // public function getactivityCodebyid(Request $request){
    // $getgroups = $this->_bploBusinessPermitfee->getbussinessactivitybyid($request->input('id'));
    //    $htmloption ="<option value=''>Please Select</option>";
    //   foreach ($getgroups as $key => $value) {
    //     $htmloption .='<option value="'.$value->id.'">'.$value->bba_desc.'</option>';
    //   }
    //   echo $htmloption;
    // }



     public function getactivityCodebyid(Request $request){
    $getgroups = $this->_bploBusinessPermitfee->getbussinessactivitybyid($request->input('id'));
       $htmloption ="<option value=''>Please Select</option>";
      foreach ($getgroups as $key => $value) {
        $htmloption .='<option value="'.$value->id.'">'.$value->bba_desc.'</option>';
      }
      echo $htmloption;
    }
    // public function getbussinessbyTaxtype(Request $request){
    //    $getgroups = $this->_bploBusinessPermitfee->getbussinessbyTaxtype($request->input('id'));
    //    $htmloption ="<option value=''>Please Select</option>";
    //   foreach ($getgroups as $key => $value) {
    //     $htmloption .='<option value="'.$value->id.'">'.$value->bbc_classification_desc.'</option>';
    //   }
    //   echo $htmloption;
    // }
    //   public function getbussinessbyTaxtypenew(Request $request){
    //    $getgroups = $this->_bploBusinessPermitfee->getbussinessbyTaxtype($request->input('id'));
    //    $htmloption ="<option value=''>Please Select</option>";
    //   foreach ($getgroups as $key => $value) {
    //     $htmloption .='<option value="'.$value->id.'">'.$value->bbc_classification_code.'</option>';
    //   }
    //   echo $htmloption;
    // }
    public function getList(Request $request){
        $data=$this->_bplobusinessfixedtax->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $arr[$i]['code']=$row->tax_class_code.$row->type_code.$row->bbc_classification_code.$row->bba_code;
            $arr[$i]['tax_class_desc']=$row->tax_class_code.'-'.$row->tax_class_desc;
            $arr[$i]['tax_type_description']=$row->type_code.'-'.$row->tax_type_description;
            $arr[$i]['bbc_classification_desc']=$row->bbc_classification_code.'-'.$row->bbc_classification_desc;
            $arr[$i]['business_activities']=$row->bba_code.'-'.$row->bba_desc;
            $arr[$i]['bft_tax_amount']=$row->bft_tax_amount;
            $arr[$i]['bft_item_count']=$row->bft_item_count;
            $arr[$i]['bft_additional_tax']=$row->bft_additional_tax;
            $schedule =""; if($row->bft_taxation_procedure =='1'){ $schedule ='Tax Rate is indicated in TAX AMOUNT';} elseif($row->bft_taxation_procedure =='2'){ $schedule ='ANNUAL TAX + Excess of count is multiplied by ADDITIONAL TAX'; }elseif($row->bft_taxation_procedure =='3'){ $schedule ='Rate indicated in Tax Amount is multiplied by the number of taxable items in business'; }
            $arr[$i]['bft_taxation_procedure']=$schedule;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinessfixedtax/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Fixed Taxes & Fees Edit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>

             
              ';
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
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
    
    
    public function getTaxDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bplobusinessfixedtax->getTaxDetails($id);
        echo json_encode($data);
    }
    
    public function getbbaDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_bplobusinessfixedtax->getbbaDetails($id);
        echo json_encode($data);
    }
    public function store(Request $request){
        $data = (object)$this->data;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;
        $arrClassificationCode = $this->arrClassificationCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessFixedTax::find($request->input('id'));
            // foreach ($this->_bplobusinessfixedtax->getbussinessbyTaxtype($data->tax_type_id) as $val) {
            //    $this->arrClassificationCode[$val->id]=$val->bbc_classification_desc;
            // }
            foreach ($this->_bplobusinessfixedtax->getbussinessactivitybyid($data->bbc_classification_code) as $val) {
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
                $this->_bplobusinessfixedtax->updateData($request->input('id'),$this->data);
                $success_msg = 'Business Tax Fixed updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bussiness Garbage Fee ".$this->data['id']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['bft_registered_date'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_bplobusinessfixedtax->addData($this->data);
                $success_msg = 'Business Tax Fixed added successfully.';
                $content = "User ".\Auth::user()->name." Added Bussiness Tax Fixed ".$lastinsertid; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);

            return redirect()->route('bplobusinessfixedtax.index')->with('success', __($success_msg));
        }
        return view('bplobusinessfixedtax.create',compact('data','arrTaxClasses','arrTaxTypes','arrClassificationCode','arrbbaCode'));
        
    }
      public function getTasktypesData(Request $request){
        $id= $request->input('id');
        $data = $this->_bploAssessment->getTasktypesData($id);
        echo json_encode($data);
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                
                'bft_tax_amount'=>'required',
                'bft_item_count'=>'required',
                'bft_additional_tax'=>'required',
               
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
            $BploBusinessFixedTax = BploBusinessFixedTax::find($id);
            if($BploBusinessFixedTax->created_by == \Auth::user()->creatorId()){
                $BploBusinessFixedTax->delete();
            }
    }
   
}
