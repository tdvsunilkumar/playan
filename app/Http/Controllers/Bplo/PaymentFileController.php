<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\PaymentFile;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Mail;

class PaymentFileController extends Controller
{
    public $data = [];
    private $slugs;
    public $arrPayMode = [];
    public function __construct(){
        $this->_PaymentFile = new PaymentFile(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busn_id'=>'','last_paid_date'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','application_date'=>'','application_date'=>'','pm_desc'=>'','p_email_address'=>'','pm_id'=>'','period'=>'');
        $this->slugs = 'business-outstanding-payment';
        
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $arr = $this->_PaymentFile->getBarangayList();
        $arrBusiness = array(""=>"Please Select");
        $arrStatus = array("1"=>"Active","0"=>"In Active");
        $arrBarngay = array(""=>"Please Select");
        foreach($arr AS $key=>$val){
            $arrBarngay[$val->id]=$val->brgy_name;
        }
        return view('Bplo.paymentFile.index',compact('arrBarngay','arrBusiness','arrStatus'));
    }
    public function getList(Request $request){

        $data=$this->_PaymentFile->getList($request);
        $arr=array();
        $i="0"; 
        $pm_id =$request->input('pm_id');
        $period =$request->input('period');
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $ownar_name=$row->full_name;
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['app_code']=($row->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$row->app_code]:'';
            $arr[$i]['last_or_no']=$row->or_no;
            $arr[$i]['last_or_amount']=($row->total_amount>0)?number_format($row->total_amount,2):'';
            $arr[$i]['last_or_date']=(!empty($row->cashier_or_date))?date("M d, Y",strtotime($row->cashier_or_date)):'';

            
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-payment-file/store?busn_id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="View Payment History">
                        <i class="ti-eye text-white"></i>
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
        if($request->input('busn_id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        if($request->input('busn_id')>0 && $request->input('submit')==""){
            $data = $this->_PaymentFile->getEditDetails($request->input('busn_id'));
            if(isset($data)){
                $data->period=$request->input('period');
                if($request->input('pm_id')>0){
                    $data->pm_id=$request->input('pm_id');
                }
                $data->ownar_name=$data->full_name;
                /* if(!empty($data->suffix)){
                    $data->ownar_name .=", ".$data->suffix;
                } */
                $data->app_code=($data->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$data->app_code]:'';
            }
        } 
        $dataPSIC = $this->_PaymentFile->getBusinessPSIC($request->input('busn_id'));
        $arrDtls = $this->_PaymentFile->getAllTransactionDetials($request->input('busn_id'));
        foreach($arrDtls as &$val){
            $val->cashier = $this->_PaymentFile->getUserName($val->created_by);
            $val->asseseedBy = $this->_PaymentFile->getAssessedBy($val->top_transaction_id);
            $val->app_code=($val->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$val->app_code]:$data->app_code;
            $val->payment_mode=($val->pm_id>0)?config('constants.payMode')[(int)$val->pm_id]:'';
            if($val->pm_id>0){
                $val->pap_id=($val->pap_id>0)?config('constants.payModePartitionShortCut')[(int)$val->pm_id][(int)$val->pap_id]:'';
            }
            $val->cashier_or_date=(!empty($val->cashier_or_date))?date("M d, Y",strtotime($val->cashier_or_date)):'';
            $val->ownar_name=$val->full_name;
            /* if(!empty($val->suffix)){
                $val->ownar_name .=", ".$val->suffix;
            } */
            $val->busns_id_no = (empty($val->busns_id_no))?$data->busns_id_no:$val->busns_id_no;
            $val->busn_name = (empty($val->busn_name))?$data->busn_name:$val->busn_name;
            $val->ownar_name = (empty(trim($val->ownar_name)))?$data->ownar_name:$val->ownar_name;
            $val->brgy_name = (empty($val->brgy_name))?$data->brgy_name:$val->brgy_name;
        }
        return view('Bplo.paymentFile.create',compact('data','dataPSIC','arrDtls'));
    }
}