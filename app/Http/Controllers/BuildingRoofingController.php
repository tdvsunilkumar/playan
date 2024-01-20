<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RptBuildingRoofing;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class BuildingRoofingController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
		$this->_buildingroofing = new RptBuildingRoofing(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','rbr_building_roof_desc'=>'');  
        $this->slugs = 'real-property/building/roofing';      
    }
    
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('buildingroofing.index');
    }
    
    public function getList(Request $request){

        $data=$this->_buildingroofing->getList($request);
        $arr=array();
        $i="0"; 
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']= $j;
            $arr[$i]['rbr_building_roof_desc']=$row->rbr_building_roof_desc;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/buildingroofing/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Building Roofing">
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
        $data=array('is_active' => $is_activeinactive);
        $this->_buildingroofing->updateActiveInactive($id,$data);
}
       
    public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_buildingroofing->editbuildingroofing($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_buildingroofing->updateData($request->input('id'),$this->data);
                $success_msg = 'Building Roofing updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_buildingroofing->addData($this->data);
                $success_msg = 'Building Roofing added successfully.';
            }
            return redirect()->route('buildingroofing.index')->with('success', __($success_msg));
    	}
        return view('buildingroofing.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'rbr_building_roof_desc'=>'required|unique:rpt_building_roofings,rbr_building_roof_desc,'.(int)$request->input('id'),
                

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
            $buildingroofing = RptBuildingRoofing::find($id);
            if($buildingroofing->created_by == \Auth::user()->creatorId()){
                $buildingroofing->delete();
            }
    }
   

}
