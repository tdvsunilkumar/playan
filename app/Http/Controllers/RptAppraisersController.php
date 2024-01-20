<?php

namespace App\Http\Controllers;

use App\Models\RptAppraisers;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class RptAppraisersController extends Controller
{
    
    public $data = [];
    public $arrHrEmpCode = array(""=>"Please Select");
    private $slugs; 
    public function __construct(){
        $this->_rptappraisers = new RptAppraisers();
		$this->data = array('id'=>'','ra_appraiser_id'=>'','ra_appraiser_position'=>'','is_sgd' => '0');
        foreach ($this->_rptappraisers->getHrEmployeeCode() as $val) {
              $this->arrHrEmpCode[$val->id]=$val->fullname;     
        } 
        $this->slugs = 'appraisers';    
    }
	
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('rptappraisers.index');
    }
    public function getEmployeeDetails(Request $request){
        $id= $request->input('id');
        $data = $this->_rptappraisers->getEmployeeDetails($id);
        return $data->description;
    }
    public function getList(Request $request){
        $data=$this->_rptappraisers->getList($request);
		$arrHrEmpCode = $this->arrHrEmpCode;
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->ra_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';

            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=($row->ra_is_active == 1) ? '<div>'.$j.'</div>':'<div style="text-decoration: line-through;color: red; text-decoration-color: red;">'.$j.'</div>';
            $arr[$i]['ra_appraiser_name']=($row->ra_is_active == 1) ? '<div>'.$arrHrEmpCode[$row->ra_appraiser_id].'</div>':'<div style="text-decoration: line-through;color: red; text-decoration-color: red;">'.$arrHrEmpCode[$row->ra_appraiser_id].'</div>';
			$arr[$i]['department']=($row->ra_is_active == 1) ? '<div>'.$row->departments_name." [". $row->division_name ."]".'</div>':'<div style="text-decoration: line-through;color: red; text-decoration-color: red;">'.$row->departments_name." [". $row->division_name ."]".'</div>';
            $arr[$i]['ra_appraiser_position']=($row->ra_is_active == 1) ? '<div>'.$row->ra_appraiser_position.'</div>':'<div style="text-decoration: line-through;color: red; text-decoration-color: red;">'.$row->ra_appraiser_position.'</div>';
			$user_name=User::find($row->created_by)->name;
			$arr[$i]['registered_by']=($row->ra_is_active == 1) ? '<div>'.$user_name.'</div>':'<div style="text-decoration: line-through;color: red; text-decoration-color: red;">'.$user_name.'</div>';
            $arr[$i]['is_active']=($row->ra_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['is_sgd']=($row->is_sgd==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptappraisers/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Appraisers">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
               </div>
                    '.$status.'
                </div>
                ';
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

    
   public function appraisersActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('ra_is_active' => $bt_is_activeinactive);
        $this->_rptappraisers->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        $arrHrEmpCode = [];

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptAppraisers::where('rpt_appraisers.id',$request->input('id'))->leftJoin('hr_employees','hr_employees.id','=','rpt_appraisers.ra_appraiser_id')->select('rpt_appraisers.*','hr_employees.fullname')->first(); 
            //dd($data);
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptappraisers->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Appraisers updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ra_is_active'] = 1;
                $this->_rptappraisers->addData($this->data);
                $success_msg = 'Rpt Appraisers added successfully.';
            }
            return redirect()->route('rptappraisers.index')->with('success', __($success_msg));
        }
        return view('rptappraisers.create',compact('data','arrHrEmpCode'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ra_appraiser_id' => 'required|unique:rpt_appraisers,ra_appraiser_id,' .$request->input('id'). ',id,ra_appraiser_position,' .$request->input('ra_appraiser_position'),
                'ra_appraiser_id'=>'required|unique:rpt_appraisers,ra_appraiser_id,'.$request->input('id'),
                'ra_appraiser_position'=>'required'
              ] , [
					'ra_appraiser_id.required' => 'The appraiser name has already been taken',
					'ra_appraiser_id.unique' => 'The appraiser name has already been taken'
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
            $RptAppraisers = RptAppraisers::find($id);
            if($RptAppraisers->created_by == \Auth::user()->creatorId()){
                $RptAppraisers->delete();
            }
    }

}
