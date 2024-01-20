<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\DelinquencyOutstanding;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use App\Http\Controllers\Bplo\TreasurerAssessmentController;
use Illuminate\Support\Facades\Mail;

class DelinquencyOutstandingController extends Controller
{
    public $data = [];
    private $slugs;
    public function __construct(){
        $this->_DlquencyOutstaning = new DelinquencyOutstanding(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busn_id'=>'','last_paid_date'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','application_date'=>'','application_date'=>'','pm_desc'=>'','p_email_address'=>'');
        $this->slugs = 'treasury-business-delinquent';
    }
    public function index(Request $request)
    {
        $arr = $this->_DlquencyOutstaning->getBarangayList();
        $arrBusiness = array(""=>"Please Select");
        $arrBarngay = array(""=>"Please Select");
        foreach($arr AS $key=>$val){
            $arrBarngay[$val->id]=$val->brgy_name;
        }
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.DelinquencyOutstanding.index',compact('arrBarngay','arrBusiness'));
    }
    public function getList(Request $request){
        $data=$this->_DlquencyOutstaning->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['busns_id_no']=$row->busns_id_no;

            $arr[$i]['ownar_name']=$row->full_name;
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['brgy_name']=$row->brgy_name;

            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount,2);
            $arr[$i]['cashier_or_date']=(!empty($row->cashier_or_date))?date("M d, Y",strtotime($row->cashier_or_date)):'';

            $arr[$i]['out_tax_due']=number_format($row->out_sub_amount,2);
            $arr[$i]['out_penalty']=number_format($row->out_surcharge_fee,2);
            $arr[$i]['out_interest']=number_format($row->out_interest_fee,2);
            $arr[$i]['out_total_amount']=number_format($row->out_total_amount,2);

            $arr[$i]['del_tax_due']=number_format($row->del_sub_amount,2);
            $arr[$i]['del_penalty']=number_format($row->del_surcharge_fee,2);
            $arr[$i]['del_interest']=number_format($row->del_interest_fee,2);
            $arr[$i]['del_total_amount']=number_format($row->del_total_amount,2);
            $arr[$i]['total_del_out_smt']=number_format($row->total_del_out_smt,2);

            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/business-ar/store?busn_id='.$row->id).'&pm_id='.$row->pm_id.'&period=" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="Manage Business Tax: Accounts Receivables">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
              
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center sendEmail" title="Send Email" d_id="'.$row->id.'" email="'.$row->p_email_address.'">
                        <i class="ti-email text-white"></i>
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
            $data = $this->_DlquencyOutstaning->getEditDetails($request->input('busn_id'));
            if(isset($data)){
                $data->period=$request->input('period');
                if($request->input('pm_id')>0){
                    //$data->pm_id=$request->input('pm_id');
                }
            }
        }
        return view('Bplo.DelinquencyOutstanding.create',compact('data'));
    }
}