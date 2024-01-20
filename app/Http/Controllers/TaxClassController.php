<?php
namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\CustomField;
use App\Models\TaxClass;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class TaxClassController extends Controller
{
    public $data = [];
    public $postdata = [];
    public function __construct(){
		$this->_taxClass = new TaxClass();
        $this->_commonmodel = new CommonModelmaster(); 
        $this->data = array('id'=>'','tax_class_code'=>'','tax_class_desc'=>'','tax_class_complete_description'=>'','created_by'=>'');  
    }
    public function index(Request $request)
    {
            //$TaxClass = TaxClass::where('created_by', '=', \Auth::user()->creatorId())->get();
            $TaxClass = $this->_taxClass->getTaxclass();
            return view('taxclass.index', compact('TaxClass'));
        
    }
    public function getList(Request $request){
        $data=$this->_taxClass->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;   
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>'; 
            $arr[$i]['srno']=$j;  
            $arr[$i]['tax_class_code']=$row->tax_class_code;
            $arr[$i]['tax_class_desc']=$row->tax_class_desc;
            $arr[$i]['completedesc']=$row->tax_class_code." - ".$row->tax_class_desc;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/taxclass/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Class">
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
        $this->_taxClass->updateActiveInactive($id,$data);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = TaxClass::find($request->input('id'));
        }
        
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['created_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_taxClass->updateData($request->input('id'),$this->data);
                $success_msg = 'Tax class updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Tax Class ".$this->data['tax_class_desc']; 
            }else{
                $this->data['is_active'] = 1;
                $this->_taxClass->addData($this->data);
                $success_msg = 'Tax class added successfully.';
                $content = "User ".\Auth::user()->name." Added Tax Class ".$this->data['tax_class_desc']; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('taxclass.index')->with('success', __($success_msg));
    	}
        return view('taxclass.create',compact('data'));
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tax_class_code'=>'required|unique:tax_classes,tax_class_code,'.$request->input('id'),
                'tax_class_desc'=>'required'
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
        
        $TaxClass = TaxClass::find($id);
        if($TaxClass->created_by == \Auth::user()->creatorId()){
            $TaxClass->delete();
            return redirect()->route('taxclass.index')->with('success', __('Tax class successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
       
    }

}
