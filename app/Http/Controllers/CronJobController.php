<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CronJob;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
class CronJobController extends Controller
{
     public $data = [];
     public $scheduleType = [];
     public $postdata = [];
     public $arrMonth=[];
     public $arrDay=[];    
	 public $arrMenugroup = array("" => "Please Select");
     public $scheduleValue = array("" => "Please Select");
     private $slugs;
     public function __construct(){
        $this->_CronJob = new CronJob(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','department'=>'','description'=>'','remarks'=>'','schedule_type'=>'','schedule_value'=>'','url'=>'','day'=>'','hours'=>'');  
        $this->slugs = 'cron-job';
        $this->scheduleType = array(
                                    ''=>'Please Select',
                                    '1'=>'Minute',
                                    '2'=>'Hour',
                                    '3'=>'Day of the month',
                                    '4'=>'Month',
                                    '5'=>'Day of the week'
                                );
        $this->arrMonth = array(
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December',
                                );
        $this->arrDay = array(
                                    1 => 'Sunday',
                                    2 => 'Monday',
                                    3 => 'Tuesday',
                                    4 => 'Wednesday',
                                    5 => 'Thursday',
                                    6 => 'Friday',
                                    7 => 'Saturday',
                                );                                                     
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('CronJob.index');
    }


    public function getList(Request $request){
		$arrMenuGroup =$this->arrMenugroup; 
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_CronJob->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $url = wordwrap($row->url, 40, "<br />\n");
            $description = wordwrap($row->description, 40, "<br />\n");
            $response = wordwrap($row->response, 40, "<br />\n");
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cron-job/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Cron-Job">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->status == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['department']=$row->department;
            $arr[$i]['url']="<div class='showLess'>".$url."</div>";
            $arr[$i]['description']="<div class='showLess'>".$description."</div>";
            $color = (strpos($row->response, "200") !== false)?'color:green':'color:red';
            $arr[$i]['response']="<div class='showLess' style='".$color."'>".$response."</div>";

            $arr[$i]['quickRun']='<span class="btn btn-success quickRun" style="padding: 0.1rem 0.5rem !important;" cid="'.$row->id.'">Quick Run</span>';
            $arr[$i]['lastExecuted']=$row->last_run_datetime;

            $arr[$i]['schedule_type']=$this->scheduleType[$row->schedule_type];
            $arr[$i]['schedule_value']=$row->schedule_value;
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']=$actions;
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
        $data=array('status' => $is_activeinactive);
        $this->_CronJob->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' Cron job ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }
       
    public function store(Request $request){
		$scheduleType =$this->scheduleType;
        $scheduleValue =$this->scheduleValue;
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }

        $data = (object)$this->data;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_CronJob->getEditDetails($request->input('id'));
        }
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_CronJob->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated Cron Job '".$this->data['department']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = 1;
                $request->id = $this->_CronJob->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added Cron Job '".$this->data['department']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('cron-job.index')->with('success', __($success_msg));
        }
        return view('CronJob.create',compact('data','scheduleType','scheduleValue'));
    }
    
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'department' =>'required',
                'description' => 'required',
                'schedule_type'=>'required',
                'url'=>'required',
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
    public function quickRunCron(Request $request){
        $id = $request->input('id');
        $data = $this->_CronJob->getEditDetails($id);
        if(isset($data)){
            $response = Http::get($data->url);
            $data=array();
            $data['response'] = "Status Code: ".$response->status();
            if($response->status()==200){
                $data['last_run_datetime'] = date('Y-m-d H:i:s');
            }
            DB::table('cron_job')->where('id',$id)->update($data);
        }
    }
    public function getScheduleVal(Request $request){
        $schedule_type_id = $request->input('schedule_type_id');
        $h_hours = $request->input('h_hours');
        $h_day = $request->input('h_day');
        $schedule_val = $request->input('schedule_val');

        $data = array();
        switch ($schedule_type_id) {
            case "2":
                for ($i = 1; $i <= 24; $i++) {
                    $data[] = array('key' => $i, 'value' => $i);
                }
                break;
        
            case "3":
                for ($i = 1; $i <= 31; $i++) {
                    $data[] = array('key' => $i, 'value' => $i);
                }
                break;
        
            case "4":
                for ($i = 1; $i <= 12; $i++) {
                    $data[] = array('key' => $i, 'value' => $this->arrMonth[$i]);
                }
                break;
        
            case "5":
                for ($i = 1; $i <= 7; $i++) {
                    $data[] = array('key' => $i, 'value' => $this->arrDay[$i]);
                }
                break;
        
            default:
                for ($i = 1; $i <= 60; $i++) {
                    $data[] = array('key' => $i, 'value' => $i);
                }
                break;
        } 
         $comd = 12;
        if($schedule_type_id==3 || $schedule_type_id==5){
            $comd = 6;
        }
        else if($schedule_type_id==4){
            $comd = 4;
        }
        ?>

        
        <div class="col-md-<?=$comd?>" id="divSchduleValue">
            <div class="form-group" id="schedule_value_parrent">
                <label>Select <?=$this->scheduleType[$schedule_type_id]?></label><span class="text-danger">*</span>
                <div class="form-icon-user">
                    <select id="schedule_value" name="schedule_value" class="form-control" required><?php
                        foreach($data AS $key=>$val){
                            $selected = $schedule_val==$val['key']?'selected':'';
                            ?><option <?=$selected?> value="<?=$val['key']?>"><?=$val['value']?></option><?php
                        } ?>
                    </select>
                </div>
            </div>
        </div><?php
        
        if($schedule_type_id==4){ 
            for ($i = 1; $i <= 31; $i++) {
                $days[] = array('key' => $i, 'value' => $i);
            } ?>
            <div class="col-md-<?=$comd?>" id="divSchduleValue">
                <div class="form-group" id="schedule_value_parrent">
                    <lable>Select Day</lable><span class="text-danger">*</span>
                    <div class="form-icon-user">
                        <select id="day" name="day" class="form-control" required><?php
                            foreach($days AS $key=>$val){
                                $selected = $h_day==$val['key']?'selected':'';
                                ?><option <?=$selected?> value="<?=$val['key']?>"><?=$val['value']?></option><?php
                            } ?>
                        </select>
                    </div>
                </div>
            </div><?php
        }
        if($schedule_type_id==3 || $schedule_type_id==4 || $schedule_type_id==5){ ?>
            <div class="col-md-<?=$comd?>">
                <div class="form-group">
                    <label>Select Time</label><span class="text-danger">*</span>
                    <div class="form-icon-user">
                        <input type="text" name="hours" id="hours" class="form-control timepicker" required value="<?=$h_hours?>">
                    </div>
                </div>
            </div> <?php 
        }
    }
   
}
