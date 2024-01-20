<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngExcavationGroundType;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngExcavationGroundTypeController extends Controller
{
    public $data = [];
     public $postdata = [];
     public function __construct(){
		$this->engexcavationtype = new EngExcavationGroundType(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','eegt_description'=>'');  
        $this->slugs = 'engneering/master-data/excavationgroundtype';      
    }
    
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
                return view('Engneering.excavationgroungtype.index');
    }
    

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->engexcavationtype->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;       
        foreach ($data['data'] as $row){
             $sr_no=$sr_no+1;
            $status =($row->eegt_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['eegt_description']=$row->eegt_description;
            $arr[$i]['is_active']=($row->eegt_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/excavationgroundtype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Excavation And Ground Type">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                    </div>' 
              ;
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
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
        $data=array('eegt_is_active' => $is_activeinactive);
        $this->engexcavationtype->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_excavation_ground_types",'action' =>"update",'id'=>$request->input('id')]);
     }
       
     public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = EngExcavationGroundType::find($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->engexcavationtype->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Excavation Ground And Type updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_excavation_ground_types",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['eegt_is_active'] = 1;
               
                $last_insert_id=$this->engexcavationtype->addData($this->data); 
                $success_msg = 'Engineering Excavation Ground And Type added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_excavation_ground_types",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('Engneering.excavationgroundtype.index')->with('success', __($success_msg));
    	}
        return view('Engneering.excavationgroungtype.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'eegt_description'=>'required|unique:eng_excavation_ground_types,eegt_description,'.(int)$request->input('id'),
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
            $EngBldgScope = EngBldgScope::find($id);
            if($EngBldgScope->created_by == \Auth::user()->id){
                $EngBldgScope->delete();
            }
    }
}
