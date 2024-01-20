<?php

namespace App\Http\Controllers;
use App\Models\CommonModelmaster;
use App\Models\BploAssessPaymentSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class BploAssessPaymentScheduleController extends Controller
{
    public $data = [];
    public $postdata = [];
    public function __construct(){
		$this->_paymentSchedule = new BploAssessPaymentSchedule();
        $this->_commonmodel = new CommonModelmaster();  
        $this->data = array('id'=>'','psched_year'=>'','psched_mode_no'=>'','psched_description'=>'','psched_short_desc'=>'','psched_date_start'=>'','psched_date_end'=>'','psched_penalty_due_date'=>'','psched_discount_due_date'=>'');

    }
    public function index(Request $request)
    {
        return view('bplopaymentsschedule.index');
        
    }
    public function getList(Request $request){
        $data=$this->_paymentSchedule->getList($request);
    	$arr=array();
		$i="0";    
		foreach ($data['data'] as $row){	
            $arr[$i]['id']=$row->id;
            $arr[$i]['psched_year']=$row->psched_year;
            $arr[$i]['psched_mode_no']=$row->psched_mode_no;
            $arr[$i]['psched_description']=$row->psched_description;
            $arr[$i]['psched_short_desc']=$row->psched_short_desc;
            $arr[$i]['psched_date_start']=$row->psched_date_start;
            $arr[$i]['psched_date_end']=$row->psched_date_end;
            $arr[$i]['psched_penalty_due_date']=$row->psched_penalty_due_date;
            $arr[$i]['psched_discount_due_date']=$row->psched_discount_due_date;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/bplopaymentsschedule/store?id='.$row->id).'" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="Edit"  data-title="Payments Schedule Edit">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
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
    	$yeararr= array();  $description = array(); $shortdesc =array();
        $year ='2020';
        for($i=0;$i<=10;$i++){
            $yeararr[$year] =$year; 
            $year = $year +1;
        }
        $data = (object)$this->data;
        $paymentmodes = $this->_paymentSchedule->getmodes();
        foreach ($paymentmodes as $keym => $valm) {
        	$description[$valm->psched_description] =$valm->psched_description;
        	$shortdesc[$valm->psched_short_desc] =$valm->psched_short_desc;
        }
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = BploAssessPaymentSchedule::find($request->input('id'));
        }
		if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['created_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $arrPrevData = BploAssessPaymentSchedule::find($request->input('id'));
                $this->_paymentSchedule->updateData($request->input('id'),$this->data);
                $success_msg = 'Payments Schedule updated successfully.';
                $content = "User ".\Auth::user()->name." Updated Payments Schedule ".$this->data['id']; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $lastinsertid = $this->_paymentSchedule->addData($this->data);
                $success_msg = 'Payments Schedule added successfully.';
                $content = "User ".\Auth::user()->name." Added Payments Schedule ".$lastinsertid; 
            }
             $systemlogdata = array(); 
             $systemlogdata['created_by'] = \Auth::user()->creatorId();
             $systemlogdata['log_content'] = $content;
             $systemlogdata['created_at'] = date('Y-m-d H:i:s');
             $systemlogdata['updated_at'] = date('Y-m-d H:i:s');
             $this->_commonmodel->addSystemActivityLog($systemlogdata);
            return redirect()->route('bplopaymentsschedule.index')->with('success', __($success_msg));
    	 }
        
        return view('bplopaymentsschedule.create',compact('data','yeararr','description','shortdesc'));
        
	}

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'psched_year'=>'required',
                'psched_mode_no'=>'required',
                'psched_description'=>'required', 
                'psched_short_desc'=>'required', 
                'psched_date_start'=>'required',
                'psched_date_end'=>'required',
                'psched_penalty_due_date'=>'required',
                'psched_discount_due_date'=>'required'
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
