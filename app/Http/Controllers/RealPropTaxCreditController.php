<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RptTaxCredit;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Helpers\Helper;
use File;
use Response;
use App\Models\RptCtoBilling;
use App\Models\Barangay;
use App\Models\ProfileMunicipality;
use DB;

class RealPropTaxCreditController extends Controller
{
     public $data = [];
     public $postdata = [];
     public $arrBarangay = array(""=>"Select Barangay");
     private $slugs;
     public function __construct(){
        $this->_taxcredit = new RptTaxCredit(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_barangay    = new Barangay;
        $this->_muncipality = new ProfileMunicipality;
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'rpt-tax-credit-file';

        foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrBarangay = $this->arrBarangay;
        $arrBusinessnames = $arrBarangay;
        //dd($arrBarangay);
         /*foreach ($this->_taxcredit->GetBussinessids() as $val){
            if($val->busns_id_no)
             $arrBusinessnames[$val->id]="[".$val->busns_id_no."]=>[".$val->busn_name."]";
         }*/
        return view('realproptaxcredit.index',compact('startdate','enddate','arrBusinessnames'));
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_taxcredit->getList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['tdno']=$row->rvy_revision_year.'-'.$row->brgy_code.'-'.$row->rp_td_no;
            $arr[$i]['taxpayername']=$row->full_name;
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($row->tax_credit_sl_id,$row->tax_credit_gl_id);
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $arr[$i]['brgy']=$row->brgy_name;
            $arr[$i]['prop_type']=$row->pk_code.'-'.$row->propertyClass;
            $unitMeaure = ($row->unitMeasure != '')?config('constants.lav_unit_measure.'.$row->unitMeasure):'Square Meter';
			if($unitMeaure == 'Hectare'){
				$arr[$i]['area']=number_format($row->area,4).' '.$unitMeaure;
			}else{
				$arr[$i]['area']=number_format($row->area,3).' '.$unitMeaure;
			}
            
			
            $arr[$i]['assessed_value']=Helper::money_format($row->rp_assessed_value);

            $accountdescription = wordwrap($accountdescription, 100, "<br />\n");
            $arr[$i]['top_no']="<div class='showLess'>".$row->transaction_no."</div>";
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_amount']=Helper::money_format($row->total_paid_amount);
            $arr[$i]['date']=$row->cashier_or_date;
            $arr[$i]['credit_amount']=Helper::money_format($row->tax_credit_amount);
            $arr[$i]['description']="<div class='showLess'>".$accountdescription."</div>";
            $assessedType = ($row->additional_credit_amount > 0 && $row->tax_credit_amount_new > 0)?config('constants.paymentTerms.'.$row->payment_terms).', Assessed Value':(($row->additional_credit_amount > 0)?'Assessed Value':(($row->tax_credit_amount_new > 0)?config('constants.paymentTerms.'.$row->payment_terms):''));
            $arr[$i]['payment_type']=$assessedType;
             $arr[$i]['status']=($row->tax_credit_is_useup > 0?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Applied</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">Pending</span>');
            $arr[$i]['details']='<div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm viewdetails align-items-center"  title="view" previouscashierid='.$row->tax_credit_is_useup.' data-title="View" id='.$row->id.'>
                    <i class="ti-eye text-white"></i>
                    </a></div>';
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
        $precashid = $request->input('precashid');
        $data = $this->_taxcredit->getdetails($id);
        //dd($data);
		/* echo'<pre>';
		print_r($data);die; */
        //dd($data);
        $returarray = array();
        $startQtr = 11;
        $endQtr   = 44;
        $billingData = RptCtoBilling::with([
            'billingDetails'=>function($query){
                $query->select('id','cb_code','sd_mode');
            }
        ])
        ->join('rpt_cto_billing_details','rpt_cto_billing_details.cb_code','=','rpt_cto_billings.id')
        ->select(
            DB::raw('MIN(rpt_cto_billing_details.cbd_covered_year) as fromYear'),
            DB::raw('MAX(rpt_cto_billing_details.cbd_covered_year) as toYear'),
            DB::raw('MIN(rpt_cto_billing_details.sd_mode) as fromQtr'),
            DB::raw('MAX(rpt_cto_billing_details.sd_mode) as toQtr')
        )
        ->where('rpt_cto_billings.transaction_id',$data->txnId)
        ->first();
        if($billingData != null){
            $minSdCode = $billingData->fromQtr;
            $maxSdCode  = $billingData->toQtr;
            if($minSdCode != '14'){
              $startQtr = $minSdCode;
              $endQtr   = $maxSdCode;
                 }
        }
        $returarray['from'] = $billingData->fromYear.' - '.Helper::billing_quarters()[$startQtr];
        $returarray['to'] = $billingData->toYear.' - '.Helper::billing_quarters()[$endQtr];
        $returarray['currentoramount'] = $data->tax_credit_amount;
        $assessedType = ($data->additional_credit_amount > 0 && $data->tax_credit_amount_new > 0)?config('constants.paymentTerms.'.$data->payment_terms).', Assessed Value':(($data->additional_credit_amount > 0)?'Assessed Value':(($data->tax_credit_amount_new > 0)?config('constants.paymentTerms.'.$data->payment_terms):''));
        $returarray['additional_credit_amount'] = $data->additional_credit_amount;
        $returarray['tax_credit_amount_new'] = $data->tax_credit_amount_new;
        $returarray['payment_terms'] = $data->payment_terms;
        if($precashid <= 0){
            $html="";
            $returarray['reforno'] = $data->or_no;
            $returarray['ordate'] = $data->cashier_or_date;
            $returarray['oramount'] = $data->total_amount;
			$returarray['total_paid_amount'] = $data->total_paid_amount;
            $returarray['precashid'] = $precashid;
            $returarray['type'] = $assessedType;
            $returarray['cashier'] = $data->cashier; $accountdescription ="";
            //$returarray['fromYear']
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
            if($Gldesc)
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $returarray['chartofaccount'] = $accountdescription; 
        }else{
            $html="";
            $returarray['reforno'] = $data->or_no;
            $returarray['ordate'] = $data->cashier_or_date;
            $returarray['oramount'] = $data->total_amount;
			$returarray['total_paid_amount'] = $data->total_paid_amount;
            $returarray['cashier'] = $data->cashier; $accountdescription ="";
            $returarray['type'] = config('constants.paymentTerms.'.$data->payment_terms);
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
            if($Gldesc)
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $returarray['chartofaccount'] = $accountdescription;
            $returarray['precashid'] = $precashid;
            $data = $this->_taxcredit->getdetailsutilized($data->ccrpid);
            //dd($data);
            $returarray['currentreforno'] = $data->or_no;
            $returarray['currentordate'] = $data->cashier_or_date;
            $returarray['currentcashier'] = $data->cashier; $accountdescription ="";
            $Gldesc = $this->_taxcredit->getAccountGeneralLeaderbyid($data->tax_credit_sl_id,$data->tax_credit_gl_id);
            if($Gldesc)
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";
            $returarray['currentchartofaccount'] = $accountdescription;  


        }
        return view('realproptaxcredit.ajax.show',compact('returarray'));
    }
    
    public function exportdepartmentalcollection(Request $request){
        $data =$this->_taxcredit->getList($request);
        
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
            'O.R.Number',
            'Date',
            'Amount',
            'Details',
            'Status',
            'Cashier'
           ]);
           $i=1;
           foreach($data['data'] as $row){
                $fullname = $row->full_name;
                $Date = date("M d, Y",strtotime($row->created_at));
                if($row->status == '1'){ $status = "active"; } else{ $status = "Cancelled"; }
                   fputcsv($handle, [ 
                    $i,
                    $fullname,
                    $row->busn_name,
                    $row->cashier_particulars,
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
