<?php

namespace App\Http\Controllers\Engneering;

use App\Models\Engneering\EngBldgOccupancySubType;
use App\Http\Controllers\Controller;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngBldgOccupancySubTypeController extends Controller
{
    
    
     public $data = [];
     public $postdata = [];
     public $arrClassCode = array(""=>"Please Select");

    public function __construct(){
        $this->_engbldgoccupancysubtype = new EngBldgOccupancySubType();
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ebost_id'=>'','ebost_description'=>'');
        $this->slugs = 'engneering/master-data/engbldgoccupancysubtype'; 
        foreach ($this->_engbldgoccupancysubtype->getRptClass() as $val) {
            $this->arrClassCode[$val->id]=$val->ebot_description;
        }  

    }
    
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Engneering.engbldgoccupancysubtype.index');
       
    }
    
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engbldgoccupancysubtype->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->ebost_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$j;
            $arr[$i]['ebot_description']=$row->ebot_description;
            $arr[$i]['ebost_description']=$row->ebost_description;
           
         
            $arr[$i]['ebost_is_active']=($row->ebost_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgoccupancysubtype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Engineering Building Occupancy Sub-Type">
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
        $data=array('ebost_is_active' => $is_activeinactive);
        $this->_engbldgoccupancysubtype->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_bldg_occupancy_sub_types",'action' =>"update",'id'=>$request->input('id')]);
    }
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrClassCode = $this->arrClassCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngBldgOccupancySubType::find($request->input('id'));
            
        }
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engbldgoccupancysubtype->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Occupancy Subtype updated successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' update Occupancy Subtype '".$this->data['ebost_description']."'";
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_occupancy_sub_types",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebost_is_active'] = 1;
               
                $last_insert_id=$this->_engbldgoccupancysubtype->addData($this->data);
                $success_msg = 'Engineering Occupancy Subtype added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Occupancy Subtype '".$this->data['ebost_description']."'";
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_occupancy_sub_types",'action' =>"store",'id'=>$last_insert_id]);
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('Engneering.engbldgoccupancysubtype.index')->with('success', __($success_msg));
        }
        return view('Engneering.engbldgoccupancysubtype.create',compact('data','arrClassCode'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ebost_id'=>'required',
                'ebost_description'=>'required|unique:eng_bldg_occupancy_sub_types,ebost_description,'.(int)$request->input('id')

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
            $EngBldgOccupancySubType = EngBldgOccupancySubType::find($id);
            if($EngBldgOccupancySubType->created_by == \Auth::user()->id){
                $EngBldgOccupancySubType->delete();
            }
    }
}
