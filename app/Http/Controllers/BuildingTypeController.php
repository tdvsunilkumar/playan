<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RptBuildingType;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class BuildingTypeController extends Controller
{
     public $data = [];
     public $postdata = [];
     public function __construct(){
		$this->_buildingtype = new RptBuildingType(); 
        $this->_commonmodel = new CommonModelmaster();
        

        $this->data = array('id'=>'','bt_building_type_code'=>'','bt_building_type_desc'=>'');  
             
    }
    
    public function index(Request $request)
    {   
        return view('buildingtype.index');    
    }
    
    public function getList(Request $request){

        $data=$this->_buildingtype->getList($request);
        $arr=array();
        $i="0";    
        foreach ($data['data'] as $row){
            $arr[$i]['bt_building_type_code']=$row->bt_building_type_code;
            $arr[$i]['bt_building_type_desc']=$row->bt_building_type_desc;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            
                                 
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/buildingtype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Building Type">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>

             
              ';
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
    
       
    public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_buildingtype->editbuildingtype($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_buildingtype->updateData($request->input('id'),$this->data);
                $success_msg = 'Building Type updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $this->_buildingtype->addData($this->data);
                $success_msg = 'Building Type added successfully.';
            }
            return redirect()->route('buildingtype.index')->with('success', __($success_msg));
    	}
        return view('buildingtype.create',compact('data'));
	}
    // public function formValidation(Request $request){
    //     $validator = \Validator::make(
    //         $request->all(), [
    //             // 'brgy_code'=>'required|unique:barangays,brgy_code,'.(int)$request->input('id'),
    //             // 'brgy_name'=>'required|unique:barangays,brgy_code,'.(int)$request->input('id'),
    //             // 'brgy_office'=>'required'

    //         ]
    //     );
    //     $arr=array('ESTATUS'=>0);
    //     if($validator->fails()){
    //         $messages = $validator->getMessageBag();
    //         $arr['field_name'] = $messages->keys()[0];
    //         $arr['error'] = $messages->all()[0];
    //         $arr['ESTATUS'] = 1;
    //     }
    //     echo json_encode($arr);exit;
    // }

    public function Delete(Request $request){
        $id = $request->input('id');
            $buildingtype = RptBuildingType::find($id);
            if($buildingtype->created_by == \Auth::user()->creatorId()){
                $buildingtype->delete();
            }
    }
   

}
