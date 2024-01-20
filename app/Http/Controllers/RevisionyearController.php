<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RevisionYear;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;

class RevisionyearController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrClassCode = array(""=>"Please Select"); 
     public $arrClassCode1 = array(""=>"Please Select"); 
     private $slugs;
     public function __construct(){
		$this->_revisionyear = new RevisionYear(); 
        $this->_commonmodel = new CommonModelmaster();
        

        $this->data = array('id'=>'','rvy_revision_year'=>'','rvy_revision_code'=>'','rvy_city_assessor_assistant_code'=>'','rvy_city_assessor_code'=>'','display_for_bplo'=>'','display_for_rpt'=>'','is_default_value'=>'','has_tax_basic'=>'0','has_tax_sef'=>'0','has_tax_sh'=>'0');  
    
        foreach ($this->_revisionyear->getHrEmployeeCode() as $val) {
            $this->arrClassCode[$val->id]=$val->firstname.' '.$val->middlename.' '.$val->lastname;
        }  
        $this->slugs = 'real-property/revision-setup';
    }
    public function index(Request $request)
    {
       $this->is_permitted($this->slugs, 'read');
       return view('rptrevisionyear.index');
    }
    public function getList(Request $request){
        $data=$this->_revisionyear->getList($request);
        $arr=array();
        $i="0";
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        
        foreach ($data['data'] as $row){
            $j=$j+1;
            
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>'; 
            
            $arr[$i]['srno']=$j;
            $arr[$i]['rvy_revision_year']=$row->rvy_revision_year;
            $arr[$i]['rvy_revision_code']=$row->rvy_revision_code;
            $arr[$i]['description']=$row->rvy_revision_year.'-'.$row->rvy_revision_code;
            $arr[$i]['rvy_city_assessor_assistant_code']=$row->fullname;
            $arr[$i]['rvy_city_assessor_code']=$row->p1firstname;
             $arr[$i]['display_for_bplo']=($row->display_for_bplo==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['display_for_rpt']=($row->display_for_rpt==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');

            $arr[$i]['basic']=($row->has_tax_basic==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');

            $arr[$i]['sef']=($row->has_tax_sef==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');

            $arr[$i]['sh']=($row->has_tax_sh==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');

             $arr[$i]['stp_print']=($row->is_default_value==1?'<input type="checkbox" id='.$row->id.'  class="form-check-input printupdate" name="stp_print" value="1" checked> ':'<input type="checkbox" class="form-check-input printupdate" id='.$row->id.' name="stp_print" value="0" >');
           
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/revisionyear/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Revision Year">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>'  ;
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

        $this->_revisionyear->updateActiveInactive($id,$data);
        
}

public function defaultUpdateCode(Request $request){
        $id = $request->input('id');

        $is_activeinactive = $request->input('is_print');
        $data=array('is_default_value' => $is_activeinactive);
        $this->_revisionyear->updateData($id,$data);
        $this->_revisionyear->updatePrint($id,$data);

    }
    public function store(Request $request){
        $data = (object)$this->data;
        $arrClassCode = [];
        $arrClassCode1 = [];

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_revisionyear->editRevisionyear($request->input('id'));
            //dd($data);
           
        }
        
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $updatedId = $this->_revisionyear->updateData($request->input('id'),$this->data);
                $this->_revisionyear->updatePrint($updatedId);
                $success_msg = 'Revision Year updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $this->data['is_default_value'] = 0;
                $insertedId = $this->_revisionyear->addData($this->data);
                $this->_revisionyear->updatePrint($insertedId);
                $success_msg = 'Revision Year added successfully.';
            }
            return redirect()->route('revisionyear.index')->with('success', __($success_msg));
    	}
        return view('rptrevisionyear.create',compact('data','arrClassCode','arrClassCode1'));
	}
    



    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'rvy_revision_year'=>'required|unique:rpt_revision_year,rvy_revision_year,'.$request->input('id'),
                'rvy_revision_code'=>'required',
                'rvy_city_assessor_code'=>'required'
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
            $RevisionYear = RevisionYear::find($id);
            if($RevisionYear->created_by == \Auth::user()->creatorId()){
                $RevisionYear->delete();
            }
    }

    public function assesserAjaxRequest(Request $request){
         $data = $this->_revisionyear->assesserAjaxRequest($request);
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }

    
    }

