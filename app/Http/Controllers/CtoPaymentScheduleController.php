<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RptCtoPaymentSchedule;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class CtoPaymentScheduleController extends Controller
{
    
     public $data = [];
     public $postdata = [];
     private $slugs;
     public $arrClassCode = array(""=>"Please Select"); 
     public function __construct(){
		$this->_ctopaymentschedule = new RptCtoPaymentSchedule(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->slugs = 'ctopaymentschedule'; 

        $this->data = array('id'=>'','rcpsched_year'=>'','sd_mode'=>'','rcpsched_date_start'=>'',
        'rcpsched_date_end'=>'','rcpsched_penalty_due_date'=>'','rcpsched_discount_due_date'=>'','rcpsched_discount_rate'=>'');  
    
        foreach ($this->_ctopaymentschedule->getSdCode() as $val) {
            $this->arrClassCode[$val->id]=$val->sd_mode.'-'.$val->sd_description.'['.$val->sd_description_short.']';
        }  
    }
    
    public function index(Request $request)
    {
            $this->is_permitted($this->slugs, 'read');
            //dd ($request->session()->all());
            $yearsDataFromTable = DB::table('rpt_cto_payment_schedules')->groupby('rcpsched_year')->orderby('rcpsched_year','DESC')->pluck('rcpsched_year','rcpsched_year')->toArray();
            $newArray = [
                date('Y')   => date('Y'),
                date('Y')+1 => date('Y')+1,
            ];
            $yearsDataFromTable += $newArray;

            $collectionYears = collect($yearsDataFromTable)->sortDesc()->toArray();
            return view('ctopaymentschedule.index',compact('collectionYears','newArray'));
           
    }

    public function addSchedularDataForNewYear(Request $request){
        $succ = [];
        $err = [];
        $dup = [];
        $currentYear = date("Y");
        $getLastYear = DB::table('rpt_cto_payment_schedules')->where('is_active',1)->latest()->first();
        if($getLastYear != null){
            $dataOfLastYear = DB::table('rpt_cto_payment_schedules')->where('is_active',1)->where('rcpsched_year',$getLastYear->rcpsched_year)->get();
            foreach ($dataOfLastYear as $yearData) {
                $newDate = Carbon::parse($yearData->rcpsched_date_start)->year(now()->format('Y'))->format('Y-m-d');
                $newYearData = [
                    'rcpsched_year' => $currentYear,
                    'sd_mode'       => $yearData->sd_mode,
                    'rcpsched_date_start' => Carbon::parse($yearData->rcpsched_date_start)->year(now()->format('Y'))->format('Y-m-d'),
                    'rcpsched_date_end' => Carbon::parse($yearData->rcpsched_date_end)->year(now()->format('Y'))->format('Y-m-d'),
                    'rcpsched_penalty_due_date' => Carbon::parse($yearData->rcpsched_penalty_due_date)->year(now()->format('Y'))->format('Y-m-d'),
                    'rcpsched_discount_due_date' => Carbon::parse($yearData->rcpsched_discount_due_date)->year(now()->format('Y'))->format('Y-m-d'),
                    'rcpsched_discount_rate' => $yearData->rcpsched_discount_rate,
                    'is_active' => 1,
                    'created_by' => $yearData->created_by,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $checkRecordAlreadyExist = DB::table('rpt_cto_payment_schedules')
                                              ->where('is_active',1)
                                              ->where('rcpsched_year',$currentYear)
                                              ->where('sd_mode',$yearData->sd_mode)
                                              ->first();                            
                if($checkRecordAlreadyExist == null){
                    /* Add New Data */
                    try {
                        $this->_ctopaymentschedule->addData($newYearData);
                        $succ[] = 'added';
                    } catch (\Exception $e) {
                        $err[] = 'not added';
                    }
                    /* Add New Data */

                    /* Update Old Data */
                    $olderDataToUpdate = [
                        'is_active' => 0
                    ];
                     $this->_ctopaymentschedule->updateData($yearData->id,$olderDataToUpdate);
                    /* Update Old Data */
                }else{
                    $dup[] = 'dup';
                }
            }
            $response = [
                'status' => 'error',
                'msg'    => count($succ).' new records addedd successfully, '.count($err).' records had an error and '.count($dup).' records are already exists for current year'
            ];
        }else{
            $response = [
                'status' => 'error',
                'msg'    => 'Table has not any active data'
            ];
        }
        return response()->json($response);
    }
    
    public function getList(Request $request){
        $data=$this->_ctopaymentschedule->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['rcpsched_year']=$row->rcpsched_year;
            $arr[$i]['sd_mode']=$row->sd_mode.'-'.$row->sd_description.'['.$row->sd_description_short.']';
            $arr[$i]['rcpsched_date_start']=date("m/d/Y",strtotime($row->rcpsched_date_start));
            $arr[$i]['rcpsched_date_end']=date("m/d/Y",strtotime($row->rcpsched_date_end));
            $arr[$i]['rcpsched_penalty_due_date']=date("m/d/Y",strtotime($row->rcpsched_penalty_due_date));
            $arr[$i]['rcpsched_discount_due_date']=($row->rcpsched_discount_due_date != '')?date("d/m/Y",strtotime($row->rcpsched_discount_due_date)):'';
            $arr[$i]['rcpsched_discount_rate']=($row->rcpsched_discount_rate != '')?$row->rcpsched_discount_rate:'0.00';
            $arr[$i]['is_active']=($row->is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/ctopaymentschedule/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Payment Schedule">
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
        $this->_ctopaymentschedule->updateActiveInactive($id,$data);
}  

    public function store(Request $request){
        $data = (object)$this->data;
        $arrClassCode = $this->arrClassCode;

        if($request->input('id')>0 && $request->input('submit')==""){
            $data = RptCtoPaymentSchedule::find($request->input('id'));
        }
       
       
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_ctopaymentschedule->updateData($request->input('id'),$this->data);
                $success_msg = 'Payment Schedule updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_ctopaymentschedule->addData($this->data);
                $success_msg = 'Payment Schedule added successfully.';
            }
            return redirect()->route('ctopaymentschedule.index')->with('success', __($success_msg));
    	}
        return view('ctopaymentschedule.create',compact('data','arrClassCode'));
	}
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'sd_mode'=>'required|unique:rpt_cto_payment_schedules,sd_mode,' .$request->input('id'). ',id,rcpsched_year,' .$request->input('rcpsched_year'),
                // 'rcpsched_year'=>'required|between:'.date('Y').','.date('Y', strtotime('+1 year')),
                'rcpsched_year'=>'required',
                'rcpsched_date_start'=>'required',
                'rcpsched_date_end' => 'required',
                'rcpsched_penalty_due_date' => 'required',
                'rcpsched_discount_rate' => 'numeric|between:0,99.99'
            ],
            [
                'sd_mode.required'=>'Required Field',
                'sd_mode.unique' => 'Already Exists',
                'rcpsched_year.required'=>'Required Field',
                'rcpsched_date_start.required'=>'Required Field',
                'rcpsched_date_end.required' => 'Required Field',
                'rcpsched_penalty_due_date.required' => 'Required Field',
                'rcpsched_discount_rate.numeric' => 'Invalid discount rate',
                'rcpsched_discount_rate.between' => 'Discount rate should be between 0,99.99'
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
            $ctopaymentschedule = RptCtoPaymentSchedule::find($id);
            if($ctopaymentschedule->created_by == \Auth::user()->creatorId()){
                $ctopaymentschedule->delete();
            }
    }
    }

