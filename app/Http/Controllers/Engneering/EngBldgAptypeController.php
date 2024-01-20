<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngBldgAptype;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngBldgAptypeController extends Controller
{
     public $data = [];
     public $postdata = [];
     public function __construct(){
		$this->_engbldgaptype = new EngBldgAptype(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','eba_description'=>'');  
        $this->slugs = 'engneering/master-data/applicationtype';     
    }
    
    public function index(Request $request)
    {
           $this->is_permitted($this->slugs, 'read');
            return view('Engneering.engbldgaptype.index');
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engbldgaptype->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->eba_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['eba_description']=$row->eba_description;
            $arr[$i]['is_active']=($row->eba_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgaptype/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Building Application Type">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                    </div>' 
              ;
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
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('eba_is_active' => $is_activeinactive);
        $this->_engbldgaptype->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_bldg_aptypes",'action' =>"update",'id'=>$request->input('id')]);
    }
       
    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_engbldgaptype->editbuildingroofing($request->input('id'));
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engbldgaptype->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Building Application Type updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_aptypes",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['eba_is_active'] = 1;
                $last_insert_id=$this->_engbldgaptype->addData($this->data);
                $success_msg = 'Engineering Building Application Type added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_aptypes",'action' =>"store",'id'=>$last_insert_id]);
            }

            return redirect()->route('Engneering.engbldgaptype.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engbldgaptype.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'eba_description'=>'required|unique:eng_bldg_aptypes,eba_description,'.(int)$request->input('id'),
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
            $EngBldgAptype = EngBldgAptype::find($id);
            if($EngBldgAptype->created_by == \Auth::user()->id){
                $EngBldgAptype->delete();
            }
    }
}
