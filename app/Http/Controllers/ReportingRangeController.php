<?php

namespace App\Http\Controllers;

use App\Models\HoReportingRange;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class ReportingRangeController extends Controller
{
    
    public $data = [];
    public $arrRange = array(""=>"Select Range");

    public function __construct(){
        $this->_reportingrange = new HoReportingRange();
        $this->data = array('id'=>'','rep_range'=>'','rep_remarks'=>'','rep_status'=>'');

        foreach ($this->_reportingrange->getRange() as $val) {
            $this->arrRange[$val->name]=$val->name;
        } 
    }
    
    public function index(Request $request)
    {
        $arrRange = $this->arrRange;
        return view('reporange.index',compact('arrRange'));
        
    }
    
    public function getList(Request $request){
        $data=$this->_reportingrange->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
             $j=$j+1;
            $status =($row->rep_status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';
            $arr[$i]['no']=$j;
            $arr[$i]['rep_range']=$row->rep_range;
            $arr[$i]['rep_remarks']=$row->rep_remarks;
            $arr[$i]['rep_status']=($row->rep_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/reporange/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Reporting Range">
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

    
   public function reporangeActiveInactive(Request $request){
        $id = $request->input('id');
        $bt_is_activeinactive = $request->input('is_activeinactive');
        $data=array('rep_status' => $bt_is_activeinactive);
        $this->_reportingrange->updateActiveInactive($id,$data);
    }  
    
    public function store(Request $request){
        $data = (object)$this->data;
        // $arrHrEmpCode = $this->arrHrEmpCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HoReportingRange::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            $this->data['rep_status'] = 1;
            if($request->input('id')>0){
                $this->_reportingrange->updateData($request->input('id'),$this->data);
                $success_msg = 'Reporting Range updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['rep_status'] = 1;
                $this->_reportingrange->addData($this->data);
                $success_msg = 'Reporting Range added successfully.';
            }
            return redirect()->route('reporange.index')->with('success', __($success_msg));
        }
        return view('reporange.create',compact('data'));
        
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'id,
                rep_range,' .$request->input('rep_range'),
                // 'ra_appraiser_id'=>'required|unique:rpt_appraisers,ra_appraiser_id,'.$request->input('id'),
                // 'ra_appraiser_position'=>'required'
                

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
            $ReportingRange = HoReportingRange::find($id);
            if($ReportingRange->created_by == \Auth::user()->creatorId()){
                $ReportingRange->delete();
            }
    }

}
