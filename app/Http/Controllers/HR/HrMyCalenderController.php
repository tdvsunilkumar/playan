<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use App\Models\HR\HrWorkSchedule;
use App\Models\CommonModelmaster;
use App\Models\HrEmployee;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;
use DB;

class HrMyCalenderController extends Controller
{
    public $data = [];
    public $postdata = [];
   
    public function __construct(){
       $this->_hrWorkSchedule= new HrWorkSchedule(); 
       $this->_hrEmployee= new HrEmployee(); 
       $this->_commonmodel = new CommonModelmaster();
       $this->data = array('id'=>'','hrsg_salary_grade'=>'','hrsg_step_1'=>'','hrsg_step_2'=>'','hrsg_step_3'=>'','hrsg_step_4'=>'','hrsg_step_5'=>'','hrsg_step_6'=>'','hrsg_step_7'=>'','hrsg_step_8'=>'');  
       $this->slugs = 'hr-my-calender'; 
   }
   
   public function index(Request $request)
   {
        $this->is_permitted($this->slugs, 'read');
        $events =  DB::table('hr_holidays')->select('id', 'hrh_description as title', 'hrh_date as start', 'hrh_date as end')->get();
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        // $sched_eve=$this->_hrWorkSchedule->getWorkScheduleByEmpId($hr_emp->id);
        // $mergedArray = array_merge($events->toArray(), $sched_eve);
        return view('HR.myCalender.index');
   }
   public function getEvents(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $start = Carbon::parse($request['start']);
        $end = Carbon::parse($request['end']);
        
        $events =  DB::table('hr_holidays')
                    ->whereBetween('hrh_date',[$start,$end])
                    ->select('id', 'hrh_description as title', 'hrh_date as start', 'hrh_date as end', DB::raw("'#A94064' as backgroundColor"))
                    ->get();
                    
        $hr_emp= $this->_hrEmployee->empIdByUserId(Auth::user()->id);
        $sched_eve=$this->_hrWorkSchedule->getWorkScheduleByEmpId($hr_emp->id, ['start'=>$start,'end'=>$end]);
        $timecard=$this->_hrWorkSchedule->getTimecardByEmpId($hr_emp->id, ['start'=>$start,'end'=>$end]);
        $leaves=$this->_hrWorkSchedule->getLeavesByEmpId($hr_emp->id, ['start'=>$start,'end'=>$end]);
        // Merge $events and $sched_eve arrays
        $mergedArray = array_merge($events->toArray(), $sched_eve,$timecard,$leaves);
        return response()->json($mergedArray);
    }
   public function updateEvent(Request $request)
    {
        $event = Event::find($request->input('id'));
        $event->title = $request->input('title');
        $event->start_date = $request->input('start');
        $event->end_date = $request->input('end');
        $event->save();

        return response()->json(['status' => 'success']);
    }
}
