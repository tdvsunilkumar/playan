<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RptPartialPayment;
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
use App\Models\RptProperty;
use DB;

class RptPartialPaymentController extends Controller
{
    public $data = [];
     public $postdata = [];
     public $arrBarangay = array(""=>"Select Barangay");
     private $slugs;
     public $arrTaxDeclaration = [];
     public function __construct(){
        $this->_partialpayment = new RptPartialPayment(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_barangay    = new Barangay;
        $this->_muncipality = new ProfileMunicipality;
        $this->data = array('id'=>'','busloc_desc'=>'');  
        $this->slugs = 'rpt-partial-payment';

        foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
        foreach ($this->_partialpayment->getTaxdecwithName() as $val) {
            $this->arrTaxDeclaration[$val->id]= $val->rp_tax_declaration_no;
        }
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $startdate = date('Y-m-d');   $enddate = date('Y-m-d');
        $startdate=Date('Y-m-d', strtotime('-15 days'));
        $arrBarangay = $this->arrBarangay;
        $arrBusinessnames = $arrBarangay;
        $arrTaxDeclaration = $this->arrTaxDeclaration;
        //dd($arrBarangay);
         /*foreach ($this->_partialpayment->GetBussinessids() as $val){
            if($val->busns_id_no)
             $arrBusinessnames[$val->id]="[".$val->busns_id_no."]=>[".$val->busn_name."]";
         }*/
        return view('realpartialpayment.index',compact('startdate','enddate','arrBusinessnames','arrTaxDeclaration'));
    }

    public function countBalance($year, $sdModes, $id){
        $totalDue = 0;
        foreach ($sdModes as $key => $sdMode) {
            $propObj = New RptProperty;
            $res = $propObj->calculatePenaltyFee($id, $year, $sdMode);
            $basicAmount = $res['basicAmount']+$res['basicPenalty']-$res['basicDisc'];
            $sefAmount = $res['basicSefAmount']+$res['sefPenalty']-$res['sefDisc'];
            $shAmount = $res['basicShAMount']+$res['shPenalty']-$res['shDisc'];
            $totalDue += $basicAmount+$sefAmount+$shAmount;
        }
        return $totalDue;
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read'); 
        $data=$this->_partialpayment->getList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  
        foreach ($data['data'] as $row){
            if($row->endQtr != 14){
                $indesx        = array_search($row->endQtr,array_keys(Helper::billing_quarters()));
                $sdModes = array_slice(array_keys(Helper::billing_quarters()),$indesx+1);
                $balance = $this->countBalance($row->endYear,$sdModes,$row->propertyId);
            }else{
                $balance = 0;
            }
            
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['tdno']=$row->rvy_revision_year.'-'.$row->brgy_code.'-'.$row->rp_td_no;
            $arr[$i]['taxpayername']=$row->full_name;
            /*$Gldesc = $this->_partialpayment->getAccountGeneralLeaderbyid($row->tax_credit_sl_id,$row->tax_credit_gl_id);
            $accountdescription = "[".$Gldesc->code." - ".$Gldesc->gldescription."]=>[".$Gldesc->prefix." - ".$Gldesc->description."]";*/
            $arr[$i]['brgy']=$row->brgy_name;
            $arr[$i]['prop_type']=$row->pk_code.'-'.$row->propertyClass;
            $unitMeaure = ($row->unitMeasure != '')?config('constants.lav_unit_measure.'.$row->unitMeasure):'';
            $arr[$i]['area']=Helper::decimal_format($row->area).' '.$unitMeaure;
            $arr[$i]['assessed_value']=Helper::money_format($row->assessedValue);
            $arr[$i]['total_amount']=Helper::money_format($row->total_paid_amount+$balance);
            //$accountdescription = wordwrap($accountdescription, 100, "<br />\n");
            if($row->startQtr == 14){
                $period = $row->startYear.' 1st Qtr - '.$row->endYear.' 4th Qtr';
            }else{
                $period = $row->startYear.' '.Helper::billing_quarters()[$row->startQtr].' - '.$row->endYear.' '.Helper::billing_quarters()[$row->endQtr];
            }
            $arr[$i]['orperiod']=$period;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['date']=$row->cashier_or_date;
            $arr[$i]['oramount']=Helper::money_format($row->total_paid_amount);
            $arr[$i]['balance']=Helper::money_format($balance);
            $arr[$i]['details'] = '';
            $arr[$i]['details']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showPartialPayDetails" title="View Details" data-sr="'.$sr_no.'" data-id="'.$row->cb_code.'" data-url="'.url('rpt-partial-payment/show').'"  data-title="View Details">
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

     public function show(Request $request){
        $id = $request->id;
        $billingData = RptCtoBilling::where('rpt_cto_billings.id',$id)->with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
        ->leftJoin('cto_top_transactions', function($join){
            $join->on('cto_top_transactions.transaction_ref_no','=','rpt_cto_billings.id')
                 ->where('cto_top_transactions.tfoc_is_applicable',2);
        })
        ->leftjoin('rpt_cto_billing_details as cbd',function($joina){
                                    $joina->on('cbd.cb_code','=','rpt_cto_billings.id')->where('cbd.sd_mode','!=',14);
                                })
        ->join('cto_cashier as cc','cc.or_no','=','rpt_cto_billings.cb_or_no')
        ->select('rpt_cto_billings.*','cto_top_transactions.transaction_no as new_transaction_no','cc.cashier_or_date',
            DB::raw('MIN(cbd.cbd_covered_year) as startYear'),
            DB::raw('MAX(cbd.cbd_covered_year) as endYear'),
            DB::raw('MIN(cbd.sd_mode) as startQtr'),
            DB::raw('MAX(cbd.sd_mode) as endQtr'),)
        ->first();
       
        if($billingData->endQtr != 14){
                $indesx        = array_search($billingData->endQtr,array_keys(Helper::billing_quarters()));
                $sdModes = array_slice(array_keys(Helper::billing_quarters()),$indesx+1);
            }else{
                $sdModes = [];
            }
        $pendingQtrData = [];
        foreach ($sdModes as $key => $sdMode) {
            $propObj = New RptProperty;
            $res = $propObj->calculatePenaltyFee($billingData->rp_code, $billingData->endYear, $sdMode);
            $basicAmount = $res['basicAmount']+$res['basicPenalty']-$res['basicDisc'];
            $sefAmount = $res['basicSefAmount']+$res['sefPenalty']-$res['sefDisc'];
            $shAmount = $res['basicShAMount']+$res['shPenalty']-$res['shDisc'];
            $totalDue = $basicAmount+$sefAmount+$shAmount;
            $res['total'] = $totalDue;
            $res['year']  = $billingData->endYear;
            $res['qtr']  = $sdMode;
            $res['asses_value']  = $billingData->rptProperty->assessed_value_for_all_kind;
             $pendingQtrData[] = $res;
        }
        //dd($pendingQtrData);
        return view('realpartialpayment.ajax.show',compact('billingData','pendingQtrData'));
    }
    
    public function exportdepartmentalcollection(Request $request){
        $data =$this->_partialpayment->getList($request);
        
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
                $fullname = $row->rpo_first_name." ".$row->rpo_middle_name." ".$row->rpo_custom_last_name;
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
