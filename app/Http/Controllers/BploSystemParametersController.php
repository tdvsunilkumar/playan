<?php

namespace App\Http\Controllers;
use App\Models\BploSystemParameters;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class BploSystemParametersController extends Controller
{
   
     public $data = [];
   
    public function __construct(){
        $this->_bplosystemparameter = new BploSystemParameters();
        
  $this->data = array('id'=>'','bsp_local_code'=>'000','bsp_local_name'=>'Palayan City','bsp_address'=>'City Hall','bsp_telephone_no'=>'','bsp_fax_no'=>'','bsp_governor_mayor'=>'','bsp_administrator_name'=>'','bsp_budget_officer_name'=>'','bsp_budget_officer_position'=>'','bsp_treasurer_name'=>'','bsp_treasurer_position'=>'','bsp_accountant_name'=>'','bsp_accountant_position'=>'','bsp_chief_bplo_name'=>'','bsp_chief_bplo_position'=>'');
        
        
    }
    public function index(Request $request)
    {
        return view('bplosystemparameters.index');
    }
    public function getList(Request $request){
        $data=$this->_bplosystemparameter->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $arr[$i]['bsp_local_code']=$row->bsp_local_code;
            $arr[$i]['bsp_local_name']=$row->bsp_local_name;
            $arr[$i]['bsp_address']=$row->bsp_address;
            $arr[$i]['bsp_telephone_no']=$row->bsp_telephone_no;
            $arr[$i]['bsp_fax_no']=$row->bsp_fax_no;
            $arr[$i]['bsp_governor_mayor']=$row->bsp_governor_mayor;
            $arr[$i]['bsp_administrator_name']=$row->bsp_administrator_name;
                $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplosystemparameters/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Business System Parameters">
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
    
    
    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploSystemParameters::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bplosystemparameter->updateData($request->input('id'),$this->data);
                $success_msg = 'Business System Parameters updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                
                $this->_bplosystemparameter->addData($this->data);
                $success_msg = 'Business System Parameters added successfully.';
            }
            return redirect()->route('bplosystemparameters.index')->with('success', __($success_msg));
        }
        return view('bplosystemparameters.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bsp_local_code'=>'required',
                'bsp_local_name'=>'required',
                'bsp_address'=>'required',
                'bsp_telephone_no'=>'required',
                'bsp_fax_no'=>'required', 
                'bsp_governor_mayor'=>'required',
                'bsp_administrator_name'=>'required', 
                'bsp_budget_officer_name'=>'required',
                'bsp_budget_officer_position'=>'required',
                'bsp_treasurer_name'=>'required',
                'bsp_treasurer_position'=>'required',
                'bsp_accountant_name'=>'required',
                'bsp_accountant_position'=>'required',
                'bsp_chief_bplo_name'=>'required',
                'bsp_chief_bplo_position'=>'required',
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
            $BploSystemParameters = BploSystemParameters::find($id);
            if($BploSystemParameters->created_by == \Auth::user()->creatorId()){
                $BploSystemParameters->delete();
            }
    }
}
