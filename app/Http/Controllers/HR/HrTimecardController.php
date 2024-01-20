<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrTimecard;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HrTimecardController extends Controller
{
    public $data = [];
    public $postdata = [];
    
     public function __construct(){
		$this->_hrtimecard= new HrTimecard(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','hrpt_description'=>'','hrpt_amount_from'=>'','hrpt_amount_to'=>'','hrpt_percentage'=>'');  
        $this->slugs = 'hr-timecard'; 
    }
    
    public function index(Request $request)
    {
    		$fromDate=Date('Y-m-d', strtotime('-15 days'));
            $this->is_permitted($this->slugs, 'read');
            $arrDepaertments = array(""=>"Select Department");  $arrDivisions = array(""=>"Select Division");
            foreach ($this->_hrtimecard->getDepartments() as $val) {
               $arrDepaertments[$val->id]=$val->shortname;
            } 
            foreach ($this->_hrtimecard->getDivisions() as $val) {
               $arrDivisions[$val->id]=$val->name;
            } 
            return view('HR.timecard.index',compact('fromDate','arrDepaertments','arrDivisions'));
    }
    public function employee(Request $request)
    {
    		$fromDate=Date('Y-m-d', strtotime('-15 days'));
            $this->is_permitted('my-timecard', 'read');
            
            return view('HR.timecard.employee',compact('fromDate'));
    }


    public function getList(Request $request, $id = null){
        // dd($id);
        if ($id) {
            $this->is_permitted('my-timecard', 'read');
        } else {
            $this->is_permitted($this->slugs, 'read');

        }
        $data=$this->_hrtimecard->getList($request, $id);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
           // $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $late = $row->hrtc_late / 60;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['userid']=$row->hrtc_employeesidno;
            $arr[$i]['employeename']=$row->fullname;
            $arr[$i]['department']=$row->department;
            $arr[$i]['division']=$row->division;
            $arr[$i]['schedule']=($row->schedule) ? date("h:i a", strtotime($row->schedule->hrds_start_time)) .' to '.date("h:i a", strtotime($row->schedule->hrds_end_time)) : '';
            $arr[$i]['hrtc_late']=number_format($late,2);
            $arr[$i]['hrtc_undertime']=$row->hrtc_undertime;
            $arr[$i]['hrtc_ot']=$row->hrtc_ot;
            $arr[$i]['hrtc_date']=$row->hrtc_date;
            $arr[$i]['holiday']=$row->holiday_name;
            $arr[$i]['hrtc_time_in']= $row->hrtc_time_in ? date("h:i a", strtotime($row->hrtc_time_in)) : '';
            $arr[$i]['hrtc_time_out']= $row->hrtc_time_out ? date("h:i a", strtotime($row->hrtc_time_out)) : '';
            $arr[$i]['action']='
                <!-- <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/hr-philhealth/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Edit Occupation Level">
                        <i class="ti-pencil text-white"></i>
                    </a>
                    </div> -->';
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
        $this->_hrtimecard->updateActiveInactive($id,$data);

        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Salary Grade ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
        
        $data = (object)$this->data;
        $requirements = array();
        $reqids ="";
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = HrPhilHealth::find($request->input('id'));
        }
        //echo "<pre>"; print_r($data); exit;
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->id;
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_hrtimecard->updateData($request->input('id'),$this->data);
                $lastinsertid = $request->input('id');
                $success_msg = 'Phil Health Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Phil Health '".$this->data['hrpt_description']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->id;
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
                $lastinsertid = $this->_hrtimecard->addData($this->data);
                $success_msg = 'Phil Health added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Phil Health '".$this->data['hrpt_description']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('hrphilhealth.index')->with('success', __($success_msg));
    	}
        return view('HR.philhealth.create',compact('data'));
	}
    
    public function timecardRefresh(Request $request){
        // dd($request->all());
        $timecards = $this->_hrtimecard->whereBetween('hrtc_date',[$request->fromdate,$request->todate])
            ->get();
        foreach ($timecards as $timecard) {
            $timecard->getAndUpdateWorkHours();
        }
        return redirect()->back();
    }
    
    public function formValidation(Request $request){
            $validator = \Validator::make(
            $request->all(), [
                'hrpt_description'=>'required|unique:hr_phil_healths,hrpt_description,'.(int)$request->input('id'),
                'hrpt_amount_from'=>'required',
                'hrpt_amount_to'=>'required',
                'hrpt_percentage'=>'required',
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
}
