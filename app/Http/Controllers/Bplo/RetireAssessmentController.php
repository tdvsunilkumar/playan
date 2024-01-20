<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\RetireAssessment;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;

class RetireAssessmentController extends Controller
{
    public $data = [];
    private $slugs;
    public function __construct(){
        $this->_RetireAssessment = new RetireAssessment(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busn_id'=>'','retire_date_start'=>'','retire_date_closed'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','busn_created_date'=>'','retire_is_final_assessment'=>'0');
        $this->slugs = 'treasury-business-retirement-assessment';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $arrYears=array(""=>"Select Year");
        $arrYrs = $this->_RetireAssessment->getYearDetails();
        foreach($arrYrs AS $key=>$val){
            $arrYears[$val->retire_year] =$val->retire_year;
        }
        return view('Bplo.RetireAssessment.index',compact('arrYears'));
    }
    public function getList(Request $request){
        $data=$this->_RetireAssessment->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        $year=$request->input('year');
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $arr[$i]['ownar_name']=$row->full_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['retire_app_type']=($row->retire_application_type==2)?'Entire Business':'Per Line of Business';
            $arr[$i]['retire_date_start']=date("M d, Y",strtotime($row->retire_date_start));
            $arr[$i]['retire_date_closed']=date("M d, Y",strtotime($row->retire_date_closed));
            $arr[$i]['duration']=$this->_commonmodel->calculateTotalYearMonth($row->retire_date_start,$row->retire_date_closed);

            $arrOtherDtls = $this->_RetireAssessment->checkAllTopPaidTransaction($row->busn_id,3,$row->retire_year);
            $topStatus='';
            foreach($arrOtherDtls as $p_val){
                $topStatus .=$p_val->top_transaction_no;
                if($p_val->payment_status==1){
                    $topStatus .=' - <span style="color:green;">Paid</span>';
                }else{
                    $topStatus .=' - <span style="color:red;">Pending</span>';
                }
                $topStatus .="<br>";
            }
            if(empty($topStatus)){
                $topStatus ='<span style="color:red;">Pending</span>';
            }
            $arr[$i]['top_no']=$topStatus;


            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/treasury-business-retirement-assessment/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Retirement of Business: Assessment">
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
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_RetireAssessment->getEditDetails($request->input('id'));
            if(isset($data)){
                $data->ownar_name=$data->rpo_first_name.' '.$data->rpo_middle_name.' '.$data->rpo_custom_last_name;
                if(!empty($data->suffix)){
                    $data->ownar_name .=", ".$data->suffix;
                }
            }
        }
        return view('Bplo.RetireAssessment.create',compact('data'));
    }
    
}