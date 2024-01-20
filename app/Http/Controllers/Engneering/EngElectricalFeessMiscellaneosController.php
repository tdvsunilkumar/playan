<?php

namespace App\Http\Controllers\Engneering;
use App\Http\Controllers\Controller;
use App\Models\Engneering\EngElectricalFeessMiscellaneos;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EngElectricalFeessMiscellaneosController extends Controller
{
    public $data = [];
     public $postdata = [];
     public function __construct(){
		$this->_engelectricalfeemisc = new EngElectricalFeessMiscellaneos(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','eefm_description'=>'','eefpa_electic_meter_amount'=>'','eefpa_wiring_permit_amount'=>'');  
        $this->slugs = 'eng_electricalmisc';     
    }
    
    public function index(Request $request)
    {
           $this->is_permitted($this->slugs, 'read');
            return view('Engneering.electricalfeesmisc.index');
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_engelectricalfeemisc->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['eefm_description']=$row->eefm_description;
            $arr[$i]['eefm_amount']=$row->eefpa_electic_meter_amount;
            $arr[$i]['eefm_writing']=$row->eefpa_wiring_permit_amount;
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
                             
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/eng_electricalmisc/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Building Application Type">
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
        $data=array('is_active' => $is_activeinactive);
        $this->_engelectricalfeemisc->updateActiveInactive($id,$data);
    }
       
    public function store(Request $request){
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_engelectricalfeemisc->editbuildingroofing($request->input('id'));
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_engelectricalfeemisc->updateData($request->input('id'),$this->data);
                $success_msg = 'Engineering Electrical Fees Miscellaneos updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->_engelectricalfeemisc->addData($this->data);
                $success_msg = 'Engineering Electrical Fees Miscellaneos added successfully.';
            }

            return redirect()->route('Engneering.engelectricalmisc.index')->with('success', __($success_msg));
    	}
        return view('Engneering.electricalfeesmisc.create',compact('data'));
	}
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'eefm_description'=>'required|unique:eng_electrical_feess_miscellaneos,eefm_description,'.(int)$request->input('id'),
                'eefpa_wiring_permit_amount'=>'required',
                'eefpa_electic_meter_amount'=>'required',
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
