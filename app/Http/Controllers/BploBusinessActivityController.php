<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploBusinessActivity;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploBusinessActivityController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrTaxTypes = array(""=>"Please Select");
    public function __construct(){
		$this->_bploBusinessActivity = new BploBusinessActivity();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','tax_class_id'=>'','tax_type_id'=>'','is_active'=>'1','bba_code'=>'','bba_desc'=>'','bba_per_day'=>'','business_classification_id'=>'');

        foreach ($this->_bploBusinessActivity->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_code.'-'.$val->tax_class_desc;
        } 
        foreach ($this->_bploBusinessActivity->getTaxTyeps() as $val) {
            $this->arrTaxTypes[$val->id]=$val->type_code.'-'.$val->tax_type_description;
        }
        
    }
    
    public function index(Request $request)
    {
        return view('bplobusinessactivity.index'); 
    }
    public function getList(Request $request){
        $data=$this->_bploBusinessActivity->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['code']=$row->taxclass_taxtype_classification_code;
            $arr[$i]['tax_class_desc']=$row->tax_class_code.'-'.$row->tax_class_desc;
            $arr[$i]['tax_type_short_name']=$row->type_code.'-'.$row->tax_type_description;
            $arr[$i]['bba_per_day']=($row->bba_per_day==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['bba_code']=$row->bba_code;
            $newtext = wordwrap($row->bba_code.'-'.$row->bba_desc, 40, "<br />\n");
            $classificationDesc = wordwrap($row->bbc_classification_code.'-'.$row->bbc_classification_desc, 40, "<br />\n");
            
            $arr[$i]['bbc_classification_desc']="<div class='showLess'>".$classificationDesc."</div>";
            $arr[$i]['bba_desc']="<div class='showLess'>".$newtext."</div>";
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplobusinessactivity/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Business Activity Edit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
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
        $this->_bploBusinessActivity->updateActiveInactive($id,$data);
}
    
    
    public function gettaxTypeBytaxClassActivity(Request $request){
        $prev_type_id = $request->input('prev_type_id');
        $arrTaxTypes = $this->_bploBusinessActivity->getTaxTyeps($request->input('tax_class_id'));
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($arrTaxTypes as $key => $value) {
            $selected = ($value->id==$prev_type_id)?'selected':'';
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->type_code.'-'.$value->tax_type_description.'</option>';
        }
        echo $htmloption;
    }

    public function store(Request $request){
        $data = (object)$this->data;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrTaxTypes = $this->arrTaxTypes;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploBusinessActivity::find($request->input('id'));
            
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            
            $classcode =$this->_bploBusinessActivity->getclasscode($this->data['tax_class_id']);
            $typecode =$this->_bploBusinessActivity->gettypecode($this->data['tax_type_id']);
            $classificationcode =$this->_bploBusinessActivity->getClassificationsCode($this->data['business_classification_id']);
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['updated_date'] = date('Y-m-d H:i:s');
             $this->data['taxclass_taxtype_classification_code'] = $classcode->tax_class_code.$typecode->type_code.$classificationcode->bbc_classification_code.$this->data['bba_code'];
            if($request->input('id')>0){
                $this->_bploBusinessActivity->updateData($request->input('id'),$this->data);
                $success_msg = 'Activity updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Activities ".$this->data['taxclass_taxtype_classification_code'];
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->_bploBusinessActivity->addData($this->data);
                $success_msg = 'Activity added successfully.';
                $content = "User ".\Auth::user()->name." Added Activities ".$this->data['taxclass_taxtype_classification_code'];
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplobusinessactivity.index')->with('success', __($success_msg));
    	}
        return view('bplobusinessactivity.create',compact('data','arrTaxClasses','arrTaxTypes'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_id'=>'required',
                'tax_type_id'=>'required', 
                'business_classification_id'=>'required', 
                'bba_code' =>'required',
                'bba_desc'=>'required'
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
        
        $BploBusinessActivity = BploBusinessActivity::find($id);
        if($BploBusinessActivity->created_by == \Auth::user()->creatorId()){
            $BploBusinessActivity->delete();
            return redirect()->route('bplobusinessactivity.index')->with('success', __('Classification successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

    public function getClassificationBytaxClassType(Request $request){
        $tax_type_id = $request->input('tax_type_id');
        $prev_classification_id = $request->input('prev_classification_id');
        $arrClassification = $this->_bploBusinessActivity->getClassifications($request->input('tax_class_id'),$tax_type_id);
        $htmloption ="<option value=''>Please Select</option>";
        foreach ($arrClassification as $key => $value) {
            $selected = ($value->id==$prev_classification_id)?'selected':'';
            $htmloption .='<option value="'.$value->id.'" '.$selected.'>'.$value->bbc_classification_code.'-'.$value->bbc_classification_desc.'</option>';
        }
        echo $htmloption;
    }

}
