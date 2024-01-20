<?php

namespace App\Http\Controllers;

use App\Models\HoDisease;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class HoDiseaseController extends Controller
{
    
    public $data = [];
    public function __construct(){
        $this->_hodisease = new HoDisease();
        $this->data = array('id'=>'','disease_name'=>'');
    }
    
    public function index(Request $request)
    {
        
        return view('hodisease.index');
        
    }
    public function getList(Request $request){
        $data=$this->_hodisease->getList($request);
        
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->hd_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            // if($row->suffix){
            //  $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname.', '.$row->suffix;   
            //  }else{
            //    $arr[$i]['ra_appraiser_name']=$row->firstname.' '.$row->middlename.' '.$row->lastname;  
            //  }
              
            $arr[$i]['disease_name']=$row->disease_name;
            $arr[$i]['hd_is_active']=($row->hd_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hodisease/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Disease">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               </div>
                    '.$status.'
                </div>
                ';
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

    
   public function diseaseActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('hd_is_active' => $bt_is_activeinactive);
        $this->_hodisease->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoDisease::find($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hodisease->updateData($request->input('id'),$this->data);
                $success_msg = 'Disease updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['hd_is_active'] = 1;
                $this->_hodisease->addData($this->data);
                $success_msg = 'Disease added successfully.';
            }
            return redirect()->route('hodisease.index')->with('success', __($success_msg));
        }
        return view('hodisease.create',compact('data'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'id,
                disease_name,' .$request->input('disease_name'),
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
            $HoDisease = HoDisease::find($id);
            if($HoDisease->created_by == \Auth::user()->creatorId()){
                $HoDisease->delete();
            }
    }

}
