<?php
namespace App\Http\Controllers\Report;
use App\Http\Controllers\Controller;
use App\Models\Report\DepartmentalCollection;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use File;
use Response;
class DepartmentalCollectionController extends Controller
{
     public $data = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_departmentalcollection = new DepartmentalCollection(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'reports-departmental-collection';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrDepaertments = array('0'=>'All');
         foreach ($this->_departmentalcollection->GetDepartmrntsArray() as $val) {
             $arrDepaertments[$val->id]=$val->pcs_name;
         }
        return view('report.departmentcollection.index',compact('startdate','enddate','arrDepaertments'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_departmentalcollection->getList($request);
        //echo "<pre>"; print_r($data); exit;
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
        	$sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            if($row->tfoc_is_applicable =='9' || $row->tfoc_is_applicable =='6' || $row->tfoc_is_applicable =='10'){
                if($row->payee_type==1){
                    $arr[$i]['taxpayername']=$row->full_name; 
                }else{
                   $citizendtl = $this->_commonmodel->getCitizenName($row->client_citizen_id);
                   $arr[$i]['taxpayername']=$citizendtl->cit_fullname;  
                }
            }else{
               $arr[$i]['taxpayername']=$row->full_name; 
            }
            if($row->tfoc_is_applicable == '2'){
              $arr[$i]['total_amount']=number_format($row->net_tax_due_amount, 2, '.', ','); 
              $arr[$i]['businessname'] = "";
              $gettdnodata = $this->_departmentalcollection->Gettdnoofrpt($row->id);
              $arr[$i]['tdno']="";
              if(!empty($gettdnodata)){
				       $arr[$i]['tdno']=$gettdnodata->rp_tax_declaration_no;
              }
            }
            else{
        				$arr[$i]['total_amount']=number_format($row->total_amount, 2, '.', ','); 
                if($row->tfoc_is_applicable == '10'){
                       $arr[$i]['total_amount']=number_format($row->total_paid_amount, 2, '.', ','); 
                } 
        				$arr[$i]['businessname']=$row->busn_name;
        				$arr[$i]['tdno']="";
            }
            $arr[$i]['perticulars']=$row->cashier_particulars;
            $arr[$i]['ortype']=$row->ortype_name;
            $arr[$i]['topno']=$row->transaction_no;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['date']=date("M d, Y",strtotime($row->created_at));
            
            $arr[$i]['details']='<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm viewdetails align-items-center"  title="view" department='.$row->tfoc_is_applicable.' payeetype='.$row->payee_type.' userid='.$row->client_citizen_id.' itemNo='.$sr_no.' data-title="View" id='.$row->id.'>
                    <i class="ti-eye text-white"></i>
                    </a></div>';
            if($row->status == '1'){ $status = '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>'; } else{ $status = '<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>'; }
            $arr[$i]['status']=$status;
            $arr[$i]['cashier']=$row->cashier;
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
        $data = $this->_departmentalcollection->getdetails($id);
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

            $details       = $this->_departmentalcollection->getDetailsrows($id); $i=1; 
            $yearWiseData  = $this->_departmentalcollection->getYearlyWiseData($id);
            $taxCreditData = $this->_departmentalcollection->getTaxCreditTdWise($id);
            $tdWiseData    = $this->_departmentalcollection->getTdWiseData($id);
            //dd($taxCreditData);
            $view = view('report.departmentcollection.ajax.list',compact('yearWiseData','tdWiseData','taxCreditData'))->render();
            $html .= $view;
        }
        
        else if($department=='9' || $department=='3' || $department=='6' || $department=='10'){
            $details = $this->_departmentalcollection->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th></thead><tbody>";
             $totalamount = 0;
            foreach ($details as $k => $val) {  
            $Gldesc = $this->_departmentalcollection->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
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
            $details = $this->_departmentalcollection->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th><th>Penalty</th><th>Interest</th><th>Total</th></thead><tbody>";
             $totalamount = 0; $totalpenalty=0; $interest =0; $Alltotal =0; $totalpenalty = 0; $totalinterest =0;
            foreach ($details as $k => $val) {  
            $Gldesc = $this->_departmentalcollection->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
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
              $Gldesctaccredit = $this->_departmentalcollection->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
              $taxcreditgldesc = $Gldesctaccredit->code." - ".$Gldesctaccredit->gldescription; 
              $taxcreditdesc = $Gldesctaccredit->prefix." - ".$Gldesctaccredit->description;
              $html .='<tr><td>1</td><td>'.$taxcreditgldesc.'</td><td>'.$taxcreditdesc.'</td><td colspan="4" style="text-align:center;">'.number_format((float)$data->tax_credit_amount, 2, '.', ',').'</td></tr></tbody>';
           } 

        }else{
            $details = $this->_departmentalcollection->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th></thead><tbody>";
            foreach ($details as $k => $val) { 
            $Gldesc = $this->_departmentalcollection->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
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
        if($department=='3' || $department=='4'){  $totalamount =0;
           $details = $this->_departmentalcollection->getDetailofEngDefault($id); $i=1; $html.="<thead><th>No.</th> <th colspan ='2' >Fees Description</th><th>Amount</th></thead><tbody>";
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


    public function viewdetailsnew(Request $request){
    	$id = $request->input('id'); 
        $department = $request->input('department'); $payeetype = $request->input('payeetype');
        $userid = $request->input('userid');
    	$data = $this->_departmentalcollection->getdetails($id);
    	$returarray = array();
    	$returarray['orno'] = $data->or_no;
        if($department=='9' || $department=='6'){
            if($payeetype =='1'){
                $returarray['taxpayer'] = $data->rpo_first_name." ".$data->rpo_middle_name." ".$data->rpo_custom_last_name; 
            }else{
                $citizendtl = $this->_commonmodel->getCitizenName($userid);
                    $returarray['taxpayer']=$citizendtl->cit_first_name." ".$citizendtl->cit_middle_name." ".$citizendtl->cit_last_name;
            }
        }else{
           $returarray['taxpayer'] = $data->rpo_first_name." ".$data->rpo_middle_name." ".$data->rpo_custom_last_name; 
        }
    	
        if($department=='9' || $department=='3' || $department=='6'){
            $details = $this->_departmentalcollection->getDetailsrows($id); $i=1; 

             $html='<thead><tr>
            <th rowspan="2" style="padding-top: 40px;text-align: center;border: 1px solid;font-size: 11px;">No.</th>
            <th rowspan="2" style="padding-top: 40px;text-align: center;border: 1px solid;font-size: 11px;">Tax Yaer</th>
             <th colspan="3" style="padding-top: 30px;text-align: center;border: 1px solid;font-size: 11px;">Basic Tax</th>
             <th colspan="3" style="padding-top: 30px;text-align: center;border: 1px solid;font-size: 11px;">SEF Tax</th>
             <th colspan="3" style="padding-top: 30px;text-align: center;border: 1px solid;font-size: 11px;">SH Tax</th>
             <th rowspan="2" colspan="3" style="padding-top: 40px;text-align: center;border: 1px solid;font-size: 11px;padding-left: 35px;">Total Amount</th>
             </tr>
              <tr><th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th> 
              <th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th>
              <th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th></tr>
            </thead><tbody>';
             $html .='<tr><td style="font-size: 11px;">'.$i.'</td>
            <td  style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td>0</td></tr></tbody>';

            $html.='<thead><tr>
            <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">No.</th>
            <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Tax Declaration No.</th>
             <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Gl Description</th>
             <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Payment Description</th>
             <th colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">Basic Tax</th>
             <th colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">SEF Tax</th>
             <th colspan="3" style="text-align: center;border: 1px solid;font-size: 11px;">SH Tax</th>
             <th rowspan="2" style="text-align: center;border: 1px solid;font-size: 11px;">Total Amount</th>
             </tr>
              <tr><th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th> 
              <th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th>
              <th style="text-align: center;border: 1px solid;">Amount</th>
              <th style="text-align: center;border: 1px solid;">Discount</th>
              <th style="text-align: center;border: 1px solid;">Penalty</th></tr>
            </thead><tbody>';
           
             $totalamount = 0;
            foreach ($details as $k => $val) {  
            $Gldesc = $this->_departmentalcollection->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
            $gldesc = $Gldesc->code." - ".$Gldesc->gldescription; 
            $paymentdesc = $Gldesc->prefix." - ".$Gldesc->description;
            $totalamount = $totalamount + $val->tfc_amount;
            $html .='<tr><td style="font-size: 11px;">'.$i.'</td>
            <td  style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">'.$gldesc.'</td>
            <td style="font-size: 12px;">'.$paymentdesc.'</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td style="font-size: 12px;">12345</td>
            <td>'.number_format((float)$val->tfc_amount, 2, '.', ',').'</td></tr>';
            $i++;
           }
            $html .='<tr><td></td><td></td><td></td><td><b>Total</b></td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black">0</td>
            <td style="border-top: 3px solid black"><b>'.number_format((float)$totalamount, 2, '.', ',').'</b></td></tr> </tbody>';
            

            //  $html.="<thead><th colspan='2'>No</th> <th colspan='4'>Tax Credit Gl Description</th><th colspan='4'>Description</th><th colspan='4' style='text-align:center;'>Credit Amount</th></thead><tbody>";
            
            // $html .='<br><br><tr><td colspan="2">1</td>
            // <td colspan="4">2</td>
            // <td colspan="4">3</td>
            // <td colspan="4">4</td>
            // </tr></tbody>';
          


        }else if($department=='1'){
            $details = $this->_departmentalcollection->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th><th>Penalty</th><th>Interest</th><th>Total</th></thead><tbody>";
             $totalamount = 0; $totalpenalty=0; $interest =0; $Alltotal =0; $totalpenalty = 0; $totalinterest =0;
            foreach ($details as $k => $val) {  
            $Gldesc = $this->_departmentalcollection->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
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
              $html.="<thead><th>No.</th> <th>Tax Credit Gl Description</th><th>Description</th><th colspan='4' style='text-align:center;'>Credit Amount</th></thead><tbody>";
              $Gldesctaccredit = $this->_departmentalcollection->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
              $taxcreditgldesc = $Gldesctaccredit->code." - ".$Gldesctaccredit->gldescription; 
              $taxcreditdesc = $Gldesctaccredit->prefix." - ".$Gldesctaccredit->description;
              $html .='<tr><td>1</td><td>'.$taxcreditgldesc.'</td><td>'.$taxcreditdesc.'</td><td colspan="4" style="text-align:center;">'.number_format((float)$data->tax_credit_amount, 2, '.', ',').'</td></tr></tbody>';
           } 

        }else{
            $details = $this->_departmentalcollection->getDetailsrows($id); $i=1; $html="<thead><th>No.</th> <th>Gl Description</th><th>Payment Description</th><th>Amount</th></thead><tbody>";
            foreach ($details as $k => $val) { 
            $Gldesc = $this->_departmentalcollection->getAccountGeneralLeaderbyid($val->sl_id,$val->agl_account_id);
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
           $details = $this->_departmentalcollection->getDetailofEngDefault($id); $i=1; $html.="<thead><th>No.</th> <th colspan ='2' >Fees Description</th><th>Amount</th></thead><tbody>";
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
    
    public function exportdepartmentalcollection(Request $request){
        $data =$this->_departmentalcollection->getListexport($request);
		
        $headers = array(
          'Content-Type' => 'text/csv'
        );
        
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }
        
        $filename =  public_path("files/departmentalcollectionlists.csv");
        $handle = fopen($filename, 'w');
        
        fputcsv($handle, [ 
           'No.',
			'Taxpayer.',
			'Permit No',
			'Business Name',
			'Particulars',
            'TOP NO',
			'O.R.Number',
			'Date',
			'Amount',
			'Details',
			'Status',
			'Cashier'
           ]);
           $i=1;
           foreach($data['data'] as $row){
				$fullname = $row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
				$Date = date("M d, Y",strtotime($row->created_at));
				if($row->status == '1'){ $status = "active"; } else{ $status = "Cancelled"; }
				   fputcsv($handle, [ 
					$i,
					$fullname,
					$row->busn_name,
					$row->cashier_particulars,
                    $row->transaction_no,
					$row->or_no,
					$Date,
					$row->total_amount,
					$row->total_amount,
					$status,
					$row->cashier
				   ]);
				   
			$i++;
           }
          fclose($handle);
          return Response::download($filename, "departmentalcollectionlists.csv", $headers);
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
