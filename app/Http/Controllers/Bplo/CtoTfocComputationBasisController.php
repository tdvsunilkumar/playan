<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\CtoTfocComputationBasis;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CtoTfocComputationBasisController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_CtoTfocComputationBasis = new CtoTfocComputationBasis(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','tfoc_id'=>'','basis_ids'=>'');  
        $this->slugs = 'business-data-tfoc-applicable-computation';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.app_computation.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_CtoTfocComputationBasis->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/CtoTfocComputationBasis/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage TFOC - Applicable Computation">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->tcb_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $basis_name = wordwrap($row->basis_name, 40, "<br />\n");
            
            $arr[$i]['srnumber']=$j;
            $arr[$i]['subsidiary_ledger']=$row->description;
            $arr[$i]['basis_name']="<div class='showLess'>".$basis_name."</div>";

            $arr[$i]['is_active']=($row->tcb_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
           
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
        $data=array('tcb_is_active' => $is_activeinactive);
        $this->_CtoTfocComputationBasis->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Bplo TFOC - Applicable Computation ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;
        $arrDtls=array(""=>"Please Select");
        $arrTFOCBasis = array(""=>"Please Select");
        $arrTFOC=array(""=>"Please Select");
        foreach ($this->_CtoTfocComputationBasis->getTFOCBasis() as $val) {
            $arrTFOCBasis[$val->id]=$val->basis_name;
        }

        foreach ($this->_CtoTfocComputationBasis->getTFOCDtls() as $val) {
            $arrTFOC[$val->id]=$val->description;
        }
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CtoTfocComputationBasis->getEditDetails($request->input('id'));
            $data->basis_ids = explode(",", $data->basis_ids);

        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['basis_ids']=array_filter($this->data['basis_ids']);
            $this->data['basis_ids']=implode(",", $this->data['basis_ids']);
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_CtoTfocComputationBasis->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Bplo TFOC - Applicable Computation '".$this->data['tfoc_id']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['tcb_is_active'] = 1;
                $request->id = $this->_CtoTfocComputationBasis->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Bplo TFOC - Applicable Computation '".$this->data['tfoc_id']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('appComputation.index')->with('success', __($success_msg));
        }
        return view('Bplo.app_computation.create',compact('data','arrDtls','arrTFOC','arrTFOCBasis'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'tfoc_id'=>'required|unique:cto_tfoc_computation_bases,tfoc_id,'.(int)$request->input('id'),
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
