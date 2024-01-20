<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngBldgScope;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;
class EngBldgScopeController extends Controller
{
     public $data = [];
     public $postdata = [];
     public function __construct(){
		$this->_engbldgscope = new EngBldgScope(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->slugs = 'engneering/master-data/applicationscope';   
        $this->data = array('id'=>'','ebs_description'=>'','ebs_is_building'=>'','ebs_is_sanitary'=>'','ebs_is_mechanical'=>'','ebs_is_electrical'=>'','ebs_is_electronics'=>'','ebs_is_excavation_and_ground'=>'','ebs_is_civil_structural_permit'=>'','ebs_is_architectural_permit'=>'','ebs_is_fencing'=>'','ebs_is_sign'=>'','ebs_is_demolition'=>'');  
             
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            return view('Engneering.engbldgscope.index');
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engbldgscope->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;    
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->ebs_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['eba_description']=$row->ebs_description;
            $arr[$i]['is_active']=($row->ebs_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/engbldgscope/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Building Scope">
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
        $data=array('ebs_is_active' => $is_activeinactive);
        $this->_engbldgscope->updateActiveInactive($id,$data);
        Session::put('remort_serv_session_det', ['table' => "eng_bldg_scopes",'action' =>"update",'id'=>$request->input('id')]);
}
       
    public function store(Request $request){
        
        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_engbldgscope->editbuildingroofing($request->input('id'));
           
        }
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engbldgscope->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Building Scope updated successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_scopes",'action' =>"update",'id'=>$request->input('id')]);
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ebs_is_active'] = 1;
                $this->data['ebs_is_building'] = ($this->data['ebs_is_building']=="")?'0':'1';
				$this->data['ebs_is_sanitary'] = ($this->data['ebs_is_sanitary']=="")?'0':'1';
				$this->data['ebs_is_mechanical'] = ($this->data['ebs_is_mechanical']=="")?'0':'1';
				$this->data['ebs_is_electrical'] = ($this->data['ebs_is_electrical']=="")?'0':'1';
				$this->data['ebs_is_electronics'] = ($this->data['ebs_is_electronics']=="")?'0':'1';
				$this->data['ebs_is_excavation_and_ground'] = ($this->data['ebs_is_excavation_and_ground']=="")?'0':'1';
				$this->data['ebs_is_civil_structural_permit'] = ($this->data['ebs_is_civil_structural_permit']=="")?'0':'1';
				$this->data['ebs_is_architectural_permit'] = ($this->data['ebs_is_architectural_permit']=="")?'0':'1';
				$this->data['ebs_is_fencing'] = ($this->data['ebs_is_fencing']=="")?'0':'1';
				$this->data['ebs_is_sign'] = ($this->data['ebs_is_sign']=="")?'0':'1';
				$this->data['ebs_is_demolition'] = ($this->data['ebs_is_demolition']=="")?'0':'1';
                $last_insert_id=$this->_engbldgscope->addData($this->data);
                $success_msg = 'Engineering Building Scope added successfully.';
                Session::put('remort_serv_session_det', ['table' => "eng_bldg_scopes",'action' =>"store",'id'=>$last_insert_id]);
            }
            return redirect()->route('Engneering.engbldgscope.index')->with('success', __($success_msg));
    	}
        return view('Engneering.engbldgscope.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ebs_description'=>'required|unique:eng_bldg_scopes,ebs_description,'.(int)$request->input('id'),
                

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
