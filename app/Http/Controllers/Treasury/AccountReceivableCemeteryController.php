<?php

namespace App\Http\Controllers\Treasury;

use App\Http\Controllers\Controller;
use App\Models\Treasury\AccountReceivableCemetery;
use App\Exports\ArCemeteryExport;
use Illuminate\Http\Request;
use App\Models\CommonModelmaster;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;

class AccountReceivableCemeteryController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_accrcblcemetery = new AccountReceivableCemetery(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'cemetery-ar';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrDepaertments = array('0'=>'All');
         foreach ($this->_accrcblcemetery->GetCemeteryName() as $val) {
             $arrDepaertments[$val->id]=$val->cem_name;
         }
        $arrlocations = array(""=>"Select Location");
        foreach ($this->_accrcblcemetery->Getlocationarray() as $val) {
             $arrlocations[$val->id]=$val->brgy_name;
         } 
        // print_r($arrlocations); exit;
        return view('treasury.accountreceivable.cemetery.index',compact('startdate','enddate','arrDepaertments','arrlocations'));
    }

    public function getpaymentlist(Request $request){
    	$id = $request->input('id');

    	$data=$this->_accrcblcemetery->getpaymentList($request,$id);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['ordate']="";
            $arr[$i]['orno']=$row->or_no;
            $arr[$i]['amount']=$row->cem_total_amount;
            $arr[$i]['payment']=$row->cem_paid_amount;
            $arr[$i]['balance']=$row->cem_remaining_balance;
            $arr[$i]['status']=($row->cem_status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Paid</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');;
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

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_accrcblcemetery->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']='<input type="checkbox" class="select_item" name="selected_items['. $row->id .']" value="'. $row->id .'">';
            $arr[$i]['transactionno']=$row->transaction_no;
            $arr[$i]['name']=$row->cit_fullname;
            $arr[$i]['address']=$row->full_address;
            $arr[$i]['location']=$row->brgy_name;
            $arr[$i]['totalamt']=number_format($row->total_amount, 2, '.', ',');;
            $arr[$i]['remainingamt']=number_format($row->remaining_amount, 2, '.', ',');;
            $arr[$i]['topno']=str_pad($row->top_transaction_id, 6, '0', STR_PAD_LEFT);
            $arr[$i]['orno']=$row->or_no;
            $arr[$i]['oramount']=number_format($row->total_paid_amount, 2, '.', ','); 
            $arr[$i]['status']=$row->status;
            $arr[$i]['action']='<a href="javascript:;" class="action-btn viewdetails bg-info btn m-1 btn-sm align-items-center" title="view summary" data-row-id="'.$row->id.'" data-row-code="'.$row->transaction_no.'" data-bs-toggle="tooltip" data-bs-placement="top"><i class="la la-file-text text-white"></i></a>';
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
    public function viewdetails(Request $request){
        $id = $request->input('id'); 
        $department = $request->input('department'); $payeetype = $request->input('payeetype');
        $userid = $request->input('userid');
        $data = $this->_accrcblcemetery->getdetails($id);
        $returarray = array();
        $returarray['orno'] = $data->or_no; $html="";
        if($department=='9' || $department=='6' || $department =='10'){
            if($payeetype =='1'){
                $returarray['taxpayer'] = $data->rpo_first_name." ".$data->rpo_middle_name." ".$data->rpo_custom_last_name; 
            }else{
                $citizendtl = $this->_commonmodel->getCitizenName($userid);
                    $returarray['taxpayer']=$citizendtl->cit_first_name." ".$citizendtl->cit_middle_name." ".$citizendtl->cit_last_name;
            }
        }else{
           $returarray['taxpayer'] = $data->rpo_first_name." ".$data->rpo_middle_name." ".$data->rpo_custom_last_name; 
        }

        if($department=='2'){

            $details       = $this->_accrcblcemetery->getDetailsrows($id); $i=1; 
            $yearWiseData  = $this->_accrcblcemetery->getYearlyWiseData($id);
            $taxCreditData = $this->_accrcblcemetery->getYearlyWiseData($id);
            $tdWiseData    = $this->_accrcblcemetery->getTdWiseData($id);
           // dd($yearWiseData);
            $view = view('report.departmentcollection.ajax.list',compact('yearWiseData','tdWiseData'))->render();
            $html .= $view;
        }
        
        else if($department=='9' || $department=='3' || $department=='6' || $department=='10'){
            $details = $this->_accrcblcemetery->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th></thead><tbody>";
             $totalamount = 0;
            foreach ($details as $k => $val) {  
            $Gldesc = $this->_accrcblcemetery->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
            $gldesc = ""; $paymentdesc="";
            if(!empty($Gldesc)){
            $gldesc = $Gldesc->code." - ".$Gldesc->gldescription; 
            $paymentdesc = $Gldesc->prefix." - ".$Gldesc->description;
             }
            $totalamount = $totalamount + $val->tfc_amount;
            $html .='<tr><td>'.$i.'</td><td>'.$gldesc.'</td><td>'.$paymentdesc.'</td><td>'.number_format((float)$val->tfc_amount, 2, '.', ',').'</td></tr>';
            $i++;
           }
            $html .='<tr><td></td><td></td><td><b>Total</b></td><td style="border-top: 3px solid black"><b>'.number_format((float)$totalamount, 2, '.', ',').'</b></td></tr> </tbody>';
        }else if($department=='1'){
            $details = $this->_accrcblcemetery->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th><th>Penalty</th><th>Interest</th><th>Total</th></thead><tbody>";
             $totalamount = 0; $totalpenalty=0; $interest =0; $Alltotal =0; $totalpenalty = 0; $totalinterest =0;
            foreach ($details as $k => $val) {  
            $Gldesc = $this->_accrcblcemetery->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
            $gldesc = $Gldesc->code." - ".$Gldesc->gldescription; 
            $paymentdesc = $Gldesc->prefix." - ".$Gldesc->description;
            $totalamount = $totalamount + $val->tfc_amount;  $totalpenalty = $totalpenalty + $val->surcharge_fee;
            $totalinterest = $totalinterest + $val->interest_fee;
            $total=  $val->tfc_amount + $val->surcharge_fee +$val->interest_fee;
            $Alltotal = $Alltotal + $total;
            $html .='<tr><td>'.$i.'</td><td>'.$gldesc.'</td><td>'.$paymentdesc.'</td><td>'.number_format((float)$val->tfc_amount, 2, '.', ',').'</td><td>'.number_format((float)$val->surcharge_fee, 2, '.', ',').'</td><td>'.number_format((float)$val->interest_fee, 2, '.', ',').'</td><td>'.number_format((float)$total, 2, '.', ',').'</td></tr>';
            $i++;
           }
            $html .='<tr><td></td><td></td><td><b>Total</b></td><td style="border-top: 3px solid black"><b>'.number_format((float)$totalamount, 2, '.', ',').'</b></td><td style="border-top: 3px solid black"><b>'.number_format((float)$totalpenalty, 2, '.', ',').'</b></td><td style="border-top: 3px solid black"><b>'.number_format((float)$totalinterest, 2, '.', ',').'</b></td><td style="border-top: 3px solid black"><b>'.number_format((float)$Alltotal, 2, '.', ',').'</b></td></tr></tbody>';
           if(($data->tcm_id)> 0 && ($data->tax_credit_gl_id)> 0 && ($data->tax_credit_sl_id)> 0 && ($data->tax_credit_amount)> 0){
              $html.="<thead><th>No</th> <th>Tax Credit Gl Description</th><th>Description</th><th colspan='4' style='text-align:center;'>Credit Amount</th></thead><tbody>";
              $Gldesctaccredit = $this->_accrcblcemetery->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
              $taxcreditgldesc = $Gldesctaccredit->code." - ".$Gldesctaccredit->gldescription; 
              $taxcreditdesc = $Gldesctaccredit->prefix." - ".$Gldesctaccredit->description;
              $html .='<tr><td>1</td><td>'.$taxcreditgldesc.'</td><td>'.$taxcreditdesc.'</td><td colspan="4" style="text-align:center;">'.number_format((float)$data->tax_credit_amount, 2, '.', ',').'</td></tr></tbody>';
           } 

        }else{
            $details = $this->_accrcblcemetery->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th></thead><tbody>";
            foreach ($details as $k => $val) { 
            $Gldesc = $this->_accrcblcemetery->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
            $gldesc =""; $paymentdesc ="";
            if(isset($Gldesc)){
                $gldesc = $Gldesc->code." - ".$Gldesc->gldescription; 
                $paymentdesc = $Gldesc->prefix." - ".$Gldesc->description;
            }
            $html .='<tr><td>'.$i.'</td><td>'.$gldesc.'</td><td>'.$paymentdesc.'</td><td>'.number_format((float)$val->tfc_amount, 2, '.', ',').'</td></tr>';
            $i++;
           }
            $html .=' </tbody>';
         
        }
        if($department=='3'){  $totalamount =0;
           $details = $this->_accrcblcemetery->getDetailofEngDefault($id); $i=1; $html.="<thead><th>No.</th> <th colspan ='2' >Fees Description</th><th>Amount</th></thead><tbody>";
            foreach ($details as $k => $val) {
                $totalamount = $totalamount + $val->tfc_amount;
            $html .='<tr><td>'.$i.'</td><td colspan="2">'.$val->fees_description.'</td><td>'.number_format((float)$val->tfc_amount, 2, '.', ',').'</td></tr>';
            $i++;
            }
            $html .='<tr><td></td><td colspan="2"><b>Total</b></td><td style="border-top: 3px solid black"><b>'.number_format((float)$totalamount, 2, '.', ',').'</b></td></tr> </tbody>';
        }
        $returarray['dynamicdata'] = $html;
        echo json_encode($returarray);
    }

    public function exportarcemetery(Request $request){
		return Excel::download(new ArCemeteryExport($request->get('keywords')), 'arcemetery_sheet'.time().'.xlsx');
	}
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'busloc_desc'=>'required|unique:bplo_business_locations,busloc_desc,'.(int)$request->input('id'),
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
