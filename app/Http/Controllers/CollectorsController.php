<?php

namespace App\Http\Controllers;

use App\Models\Collectors;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class CollectorsController extends Controller
{
    
     public $data = [];
  


    public function __construct(){
        $this->_collectors = new Collectors();
        
  $this->data = array('id'=>'','col_code'=>'','col_initial'=>'','col_initial2'=>'','col_name'=>'','col_desc'=>'','col_type'=>'');
            
    }
    public function index(Request $request)
    {
        return view('collectors.index');
    }
    
    public function getList(Request $request){
        $data=$this->_collectors->getList($request);
        $arr=array();
        $i="0";    
       
        foreach ($data['data'] as $row){
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$i+1;
            $arr[$i]['col_code']=$row->col_code;
            $arr[$i]['col_initial']=$row->col_initial;
            $arr[$i]['col_initial2']=$row->col_initial2;
            $arr[$i]['col_name']=$row->col_name;
            $arr[$i]['col_desc']=$row->col_desc;
             $type =""; if($row->col_type =='1'){ $type ='1-Cedula';} elseif($row->col_type =='2'){ $type ='2-Cedula';}elseif($row->col_type =='3'){ $type ='3-Cedula';}elseif($row->col_type =='4'){ $type ='4-Cedula';}
            $arr[$i]['col_type']=$type;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/payment-system/side-menu/collectors-file/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title=" Manage Collectors">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>' ;
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
        $this->_collectors->updateActiveInactive($id,$data);
}

    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = Collectors::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_collectors->updateData($request->input('id'),$this->data);
                $success_msg = 'Collectors updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_collectors->addData($this->data);
                $success_msg = 'Collectors added successfully.';
            }
            return redirect()->route('collectors.index')->with('success', __($success_msg));
        }
        return view('collectors.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'col_code'=>'required',
                'col_initial'=>'required',
                'col_name'=>'required',
                'col_desc'=>'required',

               
                
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
            $Collectors = Collectors::find($id);
            if($Collectors->created_by == \Auth::user()->creatorId()){
                $Collectors->delete();
            }
    }
}
