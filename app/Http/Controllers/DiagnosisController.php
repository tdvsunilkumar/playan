<?php

namespace App\Http\Controllers;

use App\Models\HoDiagnosis;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class DiagnosisController extends Controller
{
    
    public $data = [];
    public function __construct(){
        $this->_hodiagnosis = new HoDiagnosis();
        $this->data = array('id'=>'','diag_name'=>'','diag_report_cat'=>'','icd10_details'=>'','diag_remarks'=>'');
		$this->slugs = 'healthy-and-safety/setup-data/diagnosis';
    }
    
    public function index(Request $request)
    {
		$this->is_permitted($this->slugs, 'read');
		return view('diagnosis.index');
        
    }
    public function getList(Request $request){
		$this->is_permitted($this->slugs, 'read');
        $data=$this->_hodiagnosis->getList($request);
        
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->hd_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            $arr[$i]['diag_name']=$row->diag_name;
            $arr[$i]['icd10_details']=$row->icd10_details;
            $arr[$i]['diag_report_cat']=($row->diag_report_cat==1?'Disease':'Other Disease');
            $arr[$i]['diag_remarks']=$row->diag_remarks;
            $arr[$i]['hd_is_active']=($row->hd_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/healthy-and-safety/setup-data/diagnosis/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Diagnosis">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               </div>
                    '.$status.'
                </div>
                ';
                //  <div class="action-btn bg-danger ms-2">
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

    
   public function diagnosisActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('hd_is_active' => $bt_is_activeinactive);
        $this->_hodiagnosis->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
		$this->is_permitted($this->slugs, 'update');
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoDiagnosis::find($request->input('id'));
           
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hodiagnosis->updateData($request->input('id'),$this->data);
                $success_msg = 'Diagnosis updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hd_is_active'] = 1;
                $this->_hodiagnosis->addData($this->data);
                $success_msg = 'Diagnosis added successfully.';
            }
            return redirect()->route('diagnosis.index')->with('success', __($success_msg));
        }
        return view('diagnosis.create',compact('data'));
        
    }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'id,
                diag_name,' .$request->input('diag_name'),
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
            $HoDiagnosis = HoDiagnosis::find($id);
            if($HoDiagnosis->created_by == \Auth::user()->creatorId()){
                $HoDiagnosis->delete();
            }
    }

}
