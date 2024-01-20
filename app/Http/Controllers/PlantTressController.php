<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\PlantTress;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class PlantTressController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
		$this->_planttress = new PlantTress(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','pt_ptrees_code'=>'','pt_ptrees_description'=>'');  
        $this->slugs = 'real-property/property/plant-trees';     
    }
    
    public function index(Request $request)
    {
		
        $this->is_permitted($this->slugs, 'read');
        return view('planttress.index');
    }
    public function getList(Request $request){

        $data=$this->_planttress->getList($request);
        $arr=array();
        $i="0"; 
        $count = $request->start+1;   
        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$count;
            $arr[$i]['pt_ptrees_code']=$row->pt_ptrees_code;
            $arr[$i]['pt_ptrees_description']=$row->pt_ptrees_description;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
                                 
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/planttress/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Plant|Trees">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                 </div>' ;
              // <div class="action-btn bg-danger ms-2">
              //       <a href="#" class="mx-3 btn btn-sm deleterow ti-trash text-white text-white" id='.$row->id.'>
              //       </a>
              //   </div>
           
           
            $i++;
            $count++;
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
        $this->_planttress->updateActiveInactive($id,$data);
}
       
       
    public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_planttress->editPlantTress($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_planttress->updateData($request->input('id'),$this->data);
                $success_msg = 'Plant Tress updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_planttress->addData($this->data);
                $success_msg = 'Plant Tress added successfully.';
            }
            return redirect()->route('planttress.index')->with('success', __($success_msg));
    	}
        return view('planttress.create',compact('data'));
	}
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'pt_ptrees_code' => 'required|unique:rpt_plant_tress,pt_ptrees_code,' .$request->input('id'). ',id,pt_ptrees_description,' .$request->input('pt_ptrees_description'),

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
            $planttress = PlantTress::find($id);
            if($planttress->created_by == \Auth::user()->creatorId()){
                $planttress->delete();
            }
    }
   

}
