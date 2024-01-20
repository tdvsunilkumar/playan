<?php
namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Models\Bplo\Delinquency;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use File;
use App\Http\Controllers\Bplo\TreasurerAssessmentController;
use Illuminate\Support\Facades\Mail;

class DelinquencyController extends Controller
{
    public $data = [];
    private $slugs;
    public function __construct(){
        $this->_Delinquency = new Delinquency(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busn_id'=>'','last_paid_date'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','application_date'=>'','application_date'=>'','pm_desc'=>'','p_email_address'=>'');
        $this->slugs = 'treasury-business-delinquent';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.Delinquency.index');
    }
    public function getList(Request $request){
        $data=$this->_Delinquency->getList($request);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['busns_id_no']=$row->busns_id_no;
            $ownar_name=$row->full_name;
            /* if(!empty($row->suffix)){
                $ownar_name .=", ".$row->suffix;
            } */
            $arr[$i]['ownar_name']=$ownar_name;
            $arr[$i]['email']=$ownar_name;
            $arr[$i]['busn_name']=$row->busn_name;
            $arr[$i]['app_code']=($row->app_code>0)?config('constants.arrBusinessApplicationType')[(int)$row->app_code]:'';
            $arr[$i]['email']=$row->p_email_address;
            //$last_paid_date = (!empty($row->last_paid_date))?date("M d, Y",strtotime($row->last_paid_date)):'';
            //$arr[$i]['last_paid_date']=$last_paid_date;
            $arr[$i]['tax_due']=number_format($row->sub_amount,2);
            $arr[$i]['penalty']=number_format($row->surcharge_fee,2);
            $arr[$i]['interest']=number_format($row->interest_fee,2);
            $arr[$i]['total_amount']=number_format($row->total_amount,2);

            $dated = (!empty($row->acknowledged_date))?'dated '.date("M d, Y h:i a",strtotime($row->acknowledged_date)):'';
            $approveDtls=($row->is_approved==1)?'Acknowledged '.$dated:'';
            $approveDtls = wordwrap($approveDtls, 20, "<br>\n");
            $arr[$i]['is_approved'] = "<span class='showLess'>".$approveDtls."</span>";


            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/treasury-business-delinquent/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="View"  data-title="Manage Business Tax: Delinquency">
                        <i class="ti-pencil text-white"></i>
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
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $data = $this->_Delinquency->getEditDetails($request->input('id'));
            if(isset($data)){
                $data->ownar_name=$data->full_name;
                /* if(!empty($data->suffix)){
                    $data->ownar_name .=", ".$data->suffix;
                } */
            }
        }
        return view('Bplo.Delinquency.create',compact('data'));
    }
    public function sendEmail(Request $request){
        $id=$request->input('id');
        $arrDtls = $this->_Delinquency->getEditDetails($request->input('id'));
        if(isset($arrDtls)){
            if(!empty($arrDtls->p_email_address)){
                $this->_TreasurerAssessment = new TreasurerAssessmentController(); 
                $data=array();
                $encrypt = $this->_commonmodel->encryptData($id);
                $approve_url = url('/approveDelinquency/'.$encrypt);

                $description = 'Your payment still pending, Please pay as soon as possible.';
                $html = $this->_TreasurerAssessment->generateHtmlOrPdf($arrDtls->busn_id,$arrDtls->year,$arrDtls->app_code,'delinquencyEmail',$arrDtls->retire_id);
                $html = str_replace("{APPROVE_URL}",$approve_url, $html);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$arrDtls->p_email_address, $html);

                $data['message'] = $html;
                $data['to_name']=$arrDtls->rpo_first_name;
                $data['to_email']=$arrDtls->p_email_address;
                $data['subject']='Delinquency Notice';

                Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                }); 
            }
        }
    }
    public function approveDelinquency(Request $request,$encrypt){
        $id = $this->_commonmodel->decryptData($encrypt);
        if($id>0){
            $arrData['is_approved']=1;
            $arrData['acknowledged_date']=date('Y-m-d H:i:s');
            $this->_Delinquency->updateData($id,$arrData);
            return view('errors.DelinquencyThankyou');
        }
    }
}