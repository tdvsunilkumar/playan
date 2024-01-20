<?php
namespace App\Http\Controllers;
use App\Models\BploBusinessEnggFee;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploBusinessEnggFeeController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public function __construct(){
        $this->_bploBusinessEnggFee = new BploBusinessEnggFee();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','is_active'=>'','bof_default_amount'=>'');

        foreach ($this->_bploBusinessEnggFee->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_desc;
        } 
        foreach ($this->_bploBusinessEnggFee->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->tax_type_short_name;
        }
        
    }
    public function index(Request $request)
    {
        
        return view('bplobusinessenggfee.index');
        
    }
    public function getList(Request $request){
        $data=$this->_bploBusinessEnggFee->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){    
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['bof_default_amount']=$row->bof_default_amount;
            $arr[$i]['tax_type_short_name']=$row->tax_type_short_name;
            $tax_class_desc = wordwrap($row->tax_class_desc, 40, "<br />\n");
            $arr[$i]['tax_class_desc']="<div class='showLess'>".$tax_class_desc."</div>";

            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/fees-master/business-engineering-fee/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Business Engineering Fee Edit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>' ;
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
        $this->_bploBusinessEnggFee->updateActiveInactive($id,$data);
}
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessEnggFee::find($request->input('id'));
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['updated_date'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $classcode =$this->_bploBusinessEnggFee->getclasscode($this->data['tax_class_id']);
                $typecode =$this->_bploBusinessEnggFee->gettypecode($this->data['tax_class_id']);
                $feecode = $classcode->tax_class_code.$typecode->type_code;
                $this->data['fee_code'] = $feecode;
                $this->_bploBusinessEnggFee->updateData($request->input('id'),$this->data);
                $success_msg = 'Fee updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Bussiness Engneering Fee ".$feecode; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $classcode =$this->_bploBusinessEnggFee->getclasscode($this->data['tax_class_id']);
                $typecode =$this->_bploBusinessEnggFee->gettypecode($this->data['tax_class_id']);
                $feecode = $classcode->tax_class_code.$typecode->type_code;
                $this->data['fee_code'] = $feecode;
                $lastinsertid = $this->_bploBusinessEnggFee->addData($this->data);
                $success_msg = 'Fee added successfully.';
                $content = "User ".\Auth::user()->name." Added Bussiness Engneering Fee Code".$feecode; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinessenggfee.index')->with('success', __($success_msg));
        }
        return view('bplobusinessenggfee.create',compact('data','arrTaxClasses','arrTaxTypes'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                'bof_default_amount'=>'required'
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
       
        $BploBusinessEnggFee = BploBusinessEnggFee::find($id);
        if($BploBusinessEnggFee->created_by == \Auth::user()->creatorId()){
            $BploBusinessEnggFee->delete();
            return redirect()->route('bplobusinessenggfee.index')->with('success', __('Fee successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

    public function gettaxTypeBytaxClass(Request $request){
        $prev_type_id = $request->input('prev_type_id');
        $arrTaxTypes = $this->_bploBusinessEnggFee->getTaxTyeps($request->input('tax_class_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($arrTaxTypes as $key => $value) {
            $selected = ($value->id==$prev_type_id)?'selected':'';
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->tax_type_short_name.'</option>';
        }
        echo $htmloption;
    }

}
