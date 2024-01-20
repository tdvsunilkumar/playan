<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\TaxType;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class TaxtypeController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrCategory = array(""=>"Please Select");
    public $arrTaxClasses = array(""=>"Please Select");
    public $arrYesNo = array(""=>"Please Select");


    public function __construct(){
		$this->_taxType = new TaxType(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tax_class_id'=>'','type_code'=>'','tax_class_type_code'=>'','column_no'=>'','tax_type_description'=>'','tax_type_short_name'=>'','tia_account_code'=>'','top'=>'','is_active'=>'1','tax_type_is_annual'=>'','tax_type_with_surcharge'=>'','tax_type_with_intererest'=>'','tax_type_is_fire_code_base'=>'','tax_type_with_engineering_fee'=>'','tax_category_id'=>'','tax_type_initial_amount'=>'');

        foreach ($this->_taxType->getCategory() as $val) {
            $this->arrCategory[$val->id]=$val->tax_category_code.'-'.$val->tax_category_desc;
        } 
        foreach ($this->_taxType->getTaxClasses() as $val) {
            $this->arrTaxClasses[$val->id]=$val->tax_class_code.'-'.$val->tax_class_desc;
        }
        $this->arrYesNo[1]="Yes";
        $this->arrYesNo[0]="No";
    }
    public function index(Request $request)
    {
        
            $data = array();
            return view('taxtype.index', compact('data'));
        
    }



    public function getList(Request $request){
        $data=$this->_taxType->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;     
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j; 
            $arr[$i]['type_code']=$row->tax_class_code."".$row->type_code;
            $arr[$i]['tax_class_desc']=$row->tax_class_code."".$row->tax_class_desc;
            $arr[$i]['tax_type_description']=$row->type_code." - ".$row->tax_type_description;
            $arr[$i]['tax_type_short_name']=$row->tax_type_short_name;
            $arr[$i]['tia_account_code']=$row->tia_account_code;
            $arr[$i]['tax_type_descriptionnew']=$row->tax_type_description;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/taxcategory/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage TaxType">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
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
        $data=array('is_active' => $is_activeinactive);
        $this->_taxType->updateData($id,$data);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrCategory = $this->arrCategory;
        $arrTaxClasses = $this->arrTaxClasses;
        $arrYesNo = $this->arrYesNo;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = TaxType::find($request->input('id'));
            
        }
        
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_taxType->updateData($request->input('id'),$this->data);
                $success_msg = 'Tax Type updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Tax Type ".$this->data['tax_type_short_name']; 
            }else{

                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->_taxType->addData($this->data);
                $success_msg = 'Tax Type added successfully.';
                $content = "User ".\Auth::user()->name." Added Tax Type ".$this->data['tax_type_short_name']; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('taxtype.index')->with('success', __($success_msg));
    	}
        return view('taxtype.create',compact('data','arrCategory','arrTaxClasses','arrYesNo'));
        
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                // 'type_code'=>'required|unique:tax_types,type_code,'.$request->input('id'),
                'tax_class_id'=>'required',
                'tax_category_id'=>'required', 
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
        
        $TaxType = TaxType::find($id);
        if($TaxType->created_by == \Auth::user()->creatorId()){
            $TaxType->delete();
            return redirect()->route('taxtype.index')->with('success', __('Tax Type successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        
    }

}
