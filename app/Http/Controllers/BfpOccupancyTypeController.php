<?php

namespace App\Http\Controllers;

use App\Models\BfpOccupancyType;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use File;
use App\Models\CommonModelmaster;

class BfpOccupancyTypeController extends Controller
{

     public $data = [];
     private $slugs;
    public function __construct(){
        $this->_bfpoccupancytype = new BfpOccupancyType();
        $this->data = array('id'=>'','bot_occupancy_type'=>'');
        // $this->slugs = 'fire-protection-setup-data';
        $this->slugs = 'administrative/fire-protection/occupancy-type';
          
    }


    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('bfpoccupancytype.index');
    }
    
    
    public function getList(Request $request){
        $data=$this->_bfpoccupancytype->getList($request);
        $arr=array();
        $i="0";    
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['bot_occupancy_type']=$row->bot_occupancy_type;
            
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bfpoccupancytype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Occupancy Type ">
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
        $data=array('is_active' => $is_activeinactive);
        $this->_bfpoccupancytype->updateActiveInactive($id,$data);
}

    public function store(Request $request){
        $data = (object)$this->data;
       if($request->input('id')>0 && $request->input('submit')==""){
            $data = BfpOccupancyType::find($request->input('id'));
            
        }
        
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
             if($image = $request->file('bot_occupancy_pdf')) {
             $destinationPath =  public_path().'/uploads/bfpocuupancy/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
             $filename =  $this->data['bot_occupancy_type'];  
             $filename = str_replace(" ", "", $filename);   
             $occupancypdf = $filename. "." . $image->extension();
             $image->move($destinationPath, $occupancypdf);
             $this->data['bot_occupancy_pdf'] = $occupancypdf;
             // echo $profileImage;
             }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bfpoccupancytype->updateData($request->input('id'),$this->data);
                $success_msg = 'BFP Occupancy Type updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_bfpoccupancytype->addData($this->data);
                $success_msg = 'BFP Occupancy Type added successfully.';
            }
            return redirect()->route('bfpoccupancytype.index')->with('success', __($success_msg));
        }
        return view('bfpoccupancytype.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bot_occupancy_type' => 'required|unique:bfp_occupancy_types,bot_occupancy_type,' .$request->input('id'),
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
            $BfpOccupancyType = BfpOccupancyType::find($id);
            if($BfpOccupancyType->created_by == \Auth::user()->creatorId()){
                $BfpOccupancyType->delete();
            }
    }
}
