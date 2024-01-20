<?php

namespace App\Http\Controllers\Engneering;

use App\Models\Engneering\EngWaterSupplyType;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngWaterSupplyTypeController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
    

    public function __construct(){
        $this->_engwatersupplytype = new EngWaterSupplyType();
        $this->data = array('id'=>'','ewst_description'=>'');
         $this->slugs = 'engneering/master-data/engwatersupplytype';  
    }
    
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
            return view('Engneering.engwatersupplytype.index');
    }
    
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engwatersupplytype->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->ewst_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            
            
            $arr[$i]['description']=$row->ewst_description;
           
         
            $arr[$i]['is_active']=($row->ewst_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engwatersupplytype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Engineering Water Supply Type ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                     </div>'  
                ;
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
    

    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ewst_is_active' => $is_activeinactive);
        $this->_engwatersupplytype->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_water_supply_types",'action' =>"update",'id'=>$request->input('id')]);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngWaterSupplyType::find($request->input('id'));
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engwatersupplytype->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Water Supply Type updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_water_supply_types",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ewst_is_active'] = 1;
               
                $last_insert_id=$this->_engwatersupplytype->addData($this->data);
                $success_msg = 'Engineering Water Supply Type added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_water_supply_types",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('Engneering.engwatersupplytype.index')->with('success', __($success_msg));
        }
        return view('Engneering.engwatersupplytype.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                
                 'ewst_description'=>'required|unique:eng_water_supply_types,ewst_description,'.(int)$request->input('id'),
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
            $EngWaterSupplyType = EngWaterSupplyType::find($id);
            if($EngWaterSupplyType->created_by == \Auth::user()->id){
                $EngWaterSupplyType->delete();
            }
    }
}
