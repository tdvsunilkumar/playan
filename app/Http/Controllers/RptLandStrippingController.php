<?php

namespace App\Http\Controllers;

use App\Models\RptLandStripping;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class RptLandStrippingController extends Controller
{
    
    public $data = [];
    private $slugs;
    public function __construct(){
        $this->_rptlandstripping = new RptLandStripping();
        $this->data = array('id'=>'','rls_code'=>'','rls_description'=>'','rls_percent'=>'0');
        $this->slugs = 'real-property/land-stripping';
    }
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('rptlandstripping.index');
    }
    
    public function getList(Request $request){
        $data=$this->_rptlandstripping->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->rls_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['rls_code']=$row->rls_code;
            $arr[$i]['rls_description']=$row->rls_description;
            $arr[$i]['rls_percent']=$row->rls_percent;
            $arr[$i]['is_active']=($row->rls_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptlandstripping/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Land Stripping">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
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

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('rls_is_active' => $is_activeinactive);
        $this->_rptlandstripping->updateActiveInactive($id,$data);
}
    
    public function store(Request $request){
        $data = (object)$this->data;
       

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptLandStripping::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptlandstripping->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Land Stripping updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['rls_is_active'] = 1;
               
                $this->_rptlandstripping->addData($this->data);
                $success_msg = 'Rpt Land Stripping added successfully.';
            }
            return redirect()->route('rptlandstripping.index')->with('success', __($success_msg));
        }
        return view('rptlandstripping.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'rls_code'=>'required|unique:rpt_land_strippings,rls_code,'.$request->input('id'),
                'rls_description'=>'required',
                'rls_percent'=>'required'

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
            $RptLandStripping = RptLandStripping::find($id);
            if($RptLandStripping->created_by == \Auth::user()->creatorId()){
                $RptLandStripping->delete();
            }
    }
}
