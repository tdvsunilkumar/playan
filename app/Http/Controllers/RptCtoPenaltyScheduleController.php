<?php

namespace App\Http\Controllers;

use App\Models\RptCtoPenaltySchedule;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
class RptCtoPenaltyScheduleController extends Controller
{
    
     public $data = [];
     private $slugs;

    public function __construct(){
        $this->_rptctopenaltyschedule = new RptCtoPenaltySchedule();
        
       $this->data = array('id'=>'','cps_prevailing_law'=>'','cps_from_year'=>'','cps_to_year'=>'','cps_penalty_rate'=>'','cps_penalty_limitation'=>'','cps_maximum_penalty'=>'');
       $this->slugs = 'rptctopenaltyschedule';      
    }

    public function updatePenaltyRateSchedule(Request $request){
        $yearBasedPanltyRates = DB::table('rpt_cto_penalty_tables')->where('cpt_current_year',date("Y"))->get();
        $currentMonth = date("n");
        $success = [];
        $error   = [];
        foreach($yearBasedPanltyRates as $rate){
            $penaltyRateScheduleData = DB::table('rpt_cto_penalty_schedules')->where('cps_from_year',$rate->cpt_effective_year)->where('cps_to_year',$rate->cpt_effective_year)->first();
            
            if($penaltyRateScheduleData != null){
                $limitation = 1;
                $propertyName = 'cpt_month_'.$currentMonth;
                $rate = $rate->$propertyName;
                if($rate < $penaltyRateScheduleData->cps_maximum_penalty){
                    $limitation = 0;
                }
                 $dataToUpdate = [
                     'cps_penalty_rate'       => $rate,
                     'cps_penalty_limitation' => $limitation,
                     'updated_at'             => date("Y-m-d H:i:s")
                 ];
                 try {
                    $this->_rptctopenaltyschedule->updateData($penaltyRateScheduleData->id,$dataToUpdate);
                    $success[] = 'updated';
                 } catch (\Exception $e) {
                    $error[]   = 'not updated';
                 }
                 //dd($dataToUpdate);
            }else{
                $error[]       = 'not updated';
            }
            
        }
        $response = [
            'status' => 'success',
            'msg'    => count($success).' records has been updated and '.count($error).' records have not updated due to error'
        ];
        return response()->json($response);
    }
    



    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('rptctopenaltyschedule.index');
    }
    
    public function getList(Request $request){
        $data=$this->_rptctopenaltyschedule->getList($request);
        $arr=array();
        $i="0";    
        
        foreach ($data['data'] as $row){
        	$arr[$i]['cps_prevailing_law']=$row->cps_prevailing_law;
            $arr[$i]['cps_from_year']=$row->cps_from_year;
            $arr[$i]['cps_to_year']=$row->cps_to_year;
            $arr[$i]['cps_penalty_rate']=$row->cps_penalty_rate;
            $arr[$i]['cps_maximum_penalty']=$row->cps_maximum_penalty;
            
            $arr[$i]['cps_penalty_limitation']=($row->cps_penalty_limitation==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/rptctopenaltyschedule/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Rpt Cto Penalty Schedule ">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                
                ';
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



    public function store(Request $request){
        $data = (object)$this->data;
        $this->is_permitted($this->slugs, 'update');

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptCtoPenaltySchedule::find($request->input('id'));
            
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_rptctopenaltyschedule->updateData($request->input('id'),$this->data);
                $success_msg = 'Rpt Cto Penalty Schedule updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
               
                $this->_rptctopenaltyschedule->addData($this->data);
                $success_msg = 'Rpt Cto Penalty Schedule added successfully.';
            }
            return redirect()->route('rptctopenaltyschedule.index')->with('success', __($success_msg));
        }
        return view('rptctopenaltyschedule.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'cps_from_year'=>'required',
                

               
                
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
            $RptCtoPenaltySchedule = RptCtoPenaltySchedule::find($id);
            if($RptCtoPenaltySchedule->created_by == \Auth::user()->creatorId()){
                $RptCtoPenaltySchedule->delete();
            }
    }
}
