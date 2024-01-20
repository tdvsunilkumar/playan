<?php

namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Exports\ExportBfpClientList;
use App\Models\Report\BfpClientList;
use App\Models\BfpApplicationForm;
use App\Models\CommonModelmaster;
use App\Models\BploBusinessPsic;
use App\Models\HoAppHealthCert;
use App\Models\HrEmployee;
use App\Models\HoApplicationSanitary;
use App\Models\Barangay;
use App\Models\BfpOccupancyType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Maatwebsite\Excel\Facades\Excel;
use \Mpdf\Mpdf as PDF;
use \NumberFormatter;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\CarbonPeriod;
use App\Models\BploAssessmentCalculationCommon;
use DateTime;
use Session;
use Response;
use App\Interfaces\ComponentSMSNotificationInterface;
use App\Models\SmsTemplate;
use Carbon\Carbon;
use App\Models\BploBusiness;




class BfpClientsListController extends Controller
{
    public $data = [];
    public $postdata = [];
    public $arrprofile = array(""=>"Select Owner");
    public $yeararr = array(""=>"Select Year");
    public $arrBarangay = array(""=>"Please Select");
    public $arrOcupancy = array(""=>"Please Select");   
    public $arrYears = array(""=>"Select Year");
	
    public $employee = array(""=>"Please Select");
    public $isNational=0;
    public $endrlDeptDtls = [];
    public $currentYear;
    private ComponentSMSNotificationInterface $componentSMSNotificationRepository;
    private $carbon;
    public function __construct(ComponentSMSNotificationInterface $componentSMSNotificationRepository,Carbon $carbon){
        $this->componentSMSNotificationRepository = $componentSMSNotificationRepository;
        $this->carbon = $carbon; 
        $this->_BfpClientList = new BfpClientList(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_barangay = new Barangay();
        $this->_hrEmployee = new HrEmployee(); 
        $this->_assessmentCalculationCommon = new BploAssessmentCalculationCommon();
        $this->data = array('id'=>'','busns_id_no'=>'','busn_name'=>'','arrAssesment'=>array(),'totalSurcharges'=>'0','totalInterest'=>'0','end_fee_name'=>'','end_tfoc_id'=>'','enddept_fee'=>'','bbendo_id'=>'','document_details'=>'','document_detailsInspection'=>'','bplo_documents'=>array(),'bend_status'=>'','documetary_req_json'=>'','bend_year'=>'','force_mark_complete'=>'0','app_type_id'=>'','payment_mode'=>'');  
        $this->slugs = 'bfp-clients-list';
        $arrYrs = $this->_BfpClientList->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $this->arrYears[$val->bend_year] =$val->bend_year;
        }

        $this->currentYear=date('Y');
        $this->sanitaryData = array('id'=>'','bend_id'=>'','busn_id'=>'','has_app_year'=>'','has_app_no'=>'','has_type_of_establishment'=>'','has_issuance_date'=>'','has_expired_date'=>'','has_permit_no'=>'','has_status'=>'','has_recommending_approver'=>'','has_recommending_approver_status'=>'','has_recommending_approver_position'=>'','has_approver'=>'','has_approver_position'=>'','has_approver_status'=>'','has_remarks'=>'','has_approved_date'=>'');
        

    }
    public function index(Request $request){
		
        $bbendo_id =  1;
        $title =  'Fire Protection';
        $this->slugs = 'bfp-clients-list';
        $this->is_permitted($this->slugs, 'read');
        $arrYears = $this->arrYears;
        $currentYear = $this->currentYear;
        $barangay=array(""=>"Please select");
		foreach ($this->_BfpClientList->occupancyData() as $val) {
            $this->arrOcupancyData[$val->id]=$val->bot_occupancy_type;
        }
        $arrOcupancy=$this->arrOcupancyData;
        return view('report.bfpclientslist.index',compact('bbendo_id','title','arrYears','currentYear','arrOcupancy','barangay'));
    }
    
    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $bbendo_id=$request->input('bbendo_id');
        $data=$this->_BfpClientList->getList($request);
        $pageTitle = $request->input('pageTitle');
        
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){

            $sr_no=$sr_no+1;
            $actions = '';
           
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['BINBAN']=$row->BINBAN;
            $arr[$i]['created_at']=Carbon::parse($row->created_at)->format('F');
            $arr[$i]['bio_inspection_no']=$row->bio_inspection_no;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['rpo_first_name']=$row->rpo_first_name;
            $arr[$i]['rpo_custom_last_name']=$row->rpo_custom_last_name;
            $arr[$i]['bfpcert_no']=$row->bfpcert_no;
            $arr[$i]['inspection_date']=$row->inspection_date;
            $arr[$i]['app_type']=$row->app_type;
            $arr[$i]['bfpcert_date_issue']=$row->bfpcert_date_issue;
            $arr[$i]['bfpcert_date_expired']=$row->bfpcert_date_expired;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['p_mobile_no']=$row->p_mobile_no;
            $arr[$i]['bot_occupancy_type']=$row->bot_occupancy_type;
            $arr[$i]['fullname']=$row->fullname;
            $arr[$i]['bfpas_total_amount']=number_format($row->bfpas_total_amount, 2, '.', ',');
            $arr[$i]['bfpas_payment_or_no']=$row->bfpas_payment_or_no;
            $arr[$i]['bfpas_date_paid']=$row->bfpas_date_paid;
            $arr[$i]['bfpas_remarks']=$row->bfpas_remarks;
            $arr[$i]['is_printed']=($row->is_printed==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Yes</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">No</span>');"";
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
    
    public function getBarangayAjax(Request $request){
		
        $search = $request->input('search');
        $arrRes = $this->_BfpClientList->getBarangayAjax($search);
        $arr = array();
        foreach ($arrRes['data'] as $key=>$val) {
            $arr['data'][$key]['id']=$val->id;
            $arr['data'][$key]['text']=$val->brgy_name;
        }
        $arr['data_cnt']=$arrRes['data_cnt'];
        echo json_encode($arr);
    }
    public function exportreportsBfpCilentlists (Request $request){
        return Excel::download(new ExportBfpClientList($request->get('keywords')), 'BfpClientlist_sheet'.time().'.xlsx');
    }
}