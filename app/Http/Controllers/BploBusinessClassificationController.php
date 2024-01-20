<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploBusinessClassification;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploBusinessClassificationController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public function __construct(){
		$this->_bploBusinessClassification = new BploBusinessClassification();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','is_active'=>'','bbc_classification_code'=>'','bbc_classification_desc'=>'');

        foreach ($this->_bploBusinessClassification->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_code.'-'.$val->tax_class_desc;
        } 
        foreach ($this->_bploBusinessClassification->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->type_code.'-'.$val->tax_type_description;
        }
        
    }
    public function index(Request $request)
    {
        return view('bplobusinessclassification.index');
        
    }

    
    
    public function getList(Request $request){
        $data=$this->_bploBusinessClassification->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['bbc_classification_code']=$row->tax_class_code.$row->type_code.$row->bbc_classification_code;
            $arr[$i]['tax_class_desc']=$row->tax_class_code.'-'.$row->tax_class_desc;
            $arr[$i]['tax_type_short_name']=$row->type_code.'-'.$row->tax_type_description;
            $newtext = wordwrap($row->bbc_classification_code.'-'.$row->bbc_classification_desc, 40, "<br />\n");
            $arr[$i]['bbc_classification_desc']="<div class='showLess'>".$newtext."</div>";
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinessclassification/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Business Classification Edit">
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
        $this->_bploBusinessClassification->updateActiveInactive($id,$data);
}
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessClassification::find($request->input('id'));
            
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $classcode =$this->_bploBusinessClassification->getclasscode($this->data['tax_class_id']);
            $typecode =$this->_bploBusinessClassification->gettypecode($this->data['tax_type_id']);
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['updated_date'] = date('Y-m-d H:i:s');
            $this->data['taxclass_taxtype_classification_code'] = $classcode->tax_class_code.$typecode->type_code.$this->data['bbc_classification_code'];

            if($request->input('id')>0){
                $this->_bploBusinessClassification->updateData($request->input('id'),$this->data);
                $success_msg = 'Classification updated successfully.';
                 $content = "User ".\Auth::user()->name." Updated Classification ".$this->data['bbc_classification_desc'];
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->_bploBusinessClassification->addData($this->data);
                $success_msg = 'Classification added successfully.';
                 $content = "User ".\Auth::user()->name." Added Classification ".$this->data['bbc_classification_desc'];
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinessclassification.index')->with('success', __($success_msg));
    	}
        return view('bplobusinessclassification.create',compact('data','arrTaxClasses','arrTaxTypes'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bbc_classification_code'=>'required|unique:bplo_business_classifications,bbc_classification_code,'.$request->input('id'),
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                'bbc_classification_desc'=>'required'
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
        
        $BploBusinessClassification = BploBusinessClassification::find($id);
        if($BploBusinessClassification->created_by == \Auth::user()->creatorId()){
            $BploBusinessClassification->delete();
            return redirect()->route('bplobusinessclassification.index')->with('success', __('Classification successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

    public function gettaxTypeBytaxClass(Request $request){
        $prev_type_id = $request->input('prev_type_id');
        $arrTaxTypes = $this->_bploBusinessClassification->getTaxTyeps($request->input('tax_class_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($arrTaxTypes as $key => $value) {
            $selected = ($value->id==$prev_type_id)?'selected':'';
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->type_code.'-'.$value->tax_type_description.'</option>';
        }
        echo $htmloption;
    }

}
