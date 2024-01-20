<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngBldgOccupancyType;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngBldgOccupancyTypeController extends Controller
{
     public $data = [];
     public $postdata = [];
     public function __construct(){
		$this->_engbldgoccupancytype = new EngBldgOccupancyType(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','ebot_description'=>'','ebot_is_building'=>'','ebot_is_sanitary'=>'','ebot_is_mechanical'=>'','ebot_is_electrical'=>'','ebot_is_electronics'=>'','ebot_is_excavation_and_ground'=>'','ebot_is_civil_structural_permit'=>'','ebot_is_architectural_permit'=>'','ebot_is_fencing'=>''); 
         $this->slugs = 'engneering/master-data/engbldgoccupancytype';  
    }
    
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('Engneering.engbldgoccupancytype.index');
    }


    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engbldgoccupancytype->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;     
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->ebot_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['eba_description']=$row->ebot_description;
            $arr[$i]['is_active']=($row->ebot_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgoccupancytype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Engineering Building Occupancy Type">
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
        $data=array('ebot_is_active' => $is_activeinactive);
        $this->_engbldgoccupancytype->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_bldg_occupancy_types",'action' =>"update",'id'=>$request->input('id')]);
}
       
    public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_engbldgoccupancytype->editbuildingroofing($request->input('id'));
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engbldgoccupancytype->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Building Occupancy Type updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_occupancy_types",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebot_is_active'] = 1;
				$this->data['ebot_is_building'] = ($this->data['ebot_is_building']=="")?'0':'1';
				$this->data['ebot_is_sanitary'] = ($this->data['ebot_is_sanitary']=="")?'0':'1';
				$this->data['ebot_is_mechanical'] = ($this->data['ebot_is_mechanical']=="")?'0':'1';
				$this->data['ebot_is_electrical'] = ($this->data['ebot_is_electrical']=="")?'0':'1';
				$this->data['ebot_is_electronics'] = ($this->data['ebot_is_electronics']=="")?'0':'1';
				$this->data['ebot_is_excavation_and_ground'] = ($this->data['ebot_is_excavation_and_ground']=="")?'0':'1';
				$this->data['ebot_is_civil_structural_permit'] = ($this->data['ebot_is_civil_structural_permit']=="")?'0':'1';
				$this->data['ebot_is_architectural_permit'] = ($this->data['ebot_is_architectural_permit']=="")?'0':'1';
				$this->data['ebot_is_fencing'] = ($this->data['ebot_is_fencing']=="")?'0':'1';
                $last_insert_id=$this->_engbldgoccupancytype->addData($this->data);
                $success_msg = 'Engineering Building Occupancy Type added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_occupancy_types",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('Engneering.engbldgoccupancytype.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engbldgoccupancytype.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ebot_description'=>'required|unique:eng_bldg_occupancy_types,ebot_description,'.(int)$request->input('id'),
                

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
            $EngBldgOccupancyType = EngBldgOccupancyType::find($id);
            if($EngBldgOccupancyType->created_by == \Auth::user()->id){
                $EngBldgOccupancyType->delete();
            }
    }
   

}
