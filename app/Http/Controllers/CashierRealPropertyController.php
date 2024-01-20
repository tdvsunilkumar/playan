<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CashierRealProperty;
use App\Models\CommonModelmaster;
use App\Models\Barangay;
use Illuminate\Validation\Rule;
use App\Models\ProfileMunicipality;
use App\Models\RptCtoBillingDetail;
use App\Models\RptCtoBillingDetailsPenalty;
use App\Models\RptCtoBillingDetailsDiscount;
use Illuminate\Http\Request;
use App\Models\RptCtoBilling;
use App\Models\RptDelinquent;
use App\Helpers\Helper;
use DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use File;
use App\Models\SmsTemplate;
use App\Repositories\ComponentSMSNotificationRepository;


class CashierRealPropertyController extends Controller
{
    public $data = [];
    public $dataDtls = [];
    private $slugs;
    public $arrBarangay = array(""=>"Select Barangay");
    public $ortype_id ="";
    public function __construct(){
        $this->_cashierrealproperty = new CashierRealProperty(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_Barangay = new Barangay();
        $this->_muncipality = new ProfileMunicipality;
        $this->data = array('id'=>'','cashier_year'=>date('Y'),'cashier_or_date'=>date("d/m/Y"),'top_transaction_id'=>'','client_citizen_id'=>'','or_no'=>'','total_amount'=>'','total_paid_amount'=>'0.00','total_amount_change'=>'0.00','total_paid_surcharge'=>'','payment_terms'=>'1','payment_type'=>'Walk-In','payment_type'=>'Walk-In','tax_credit_amount'=>'0.00','tfoc_is_applicable'=>'2','net_tax_due_amount'=>'0.00','created_at'=>date("d/m/Y"),'cashier_batch_no'=>'','tcm_id'=>'','tax_credit_gl_id'=>'','tax_credit_sl_id'=>'','previous_cashier_id'=>'');
        $this->dataRealProp = array('cashier_year'=>date('Y'),'top_transaction_id'=>'','cb_code'=>'','rp_code'=>'','pk_code'=>'','rp_tax_declaration_no'=>'');
        $this->dataDtls = array('cashier_year'=>date('Y'),'top_transaction_id'=>'','cb_code'=>'','cbd_code'=>'','pk_id'=>'','trevs_id' => '','tax_revenue_year'=>'','basic_tfoc_id'=>'','basic_gl_id'=>'','basic_sl_id'=>'','basic_amount'=>'','basic_discount_tfoc_id'=>'','basic_discount_gl_id'=>'','basic_discount_sl_id'=>'','basic_discount_amount'=>'','basic_penalty_tfoc_id'=>'','basic_penalty_gl_id'=>'','basic_penalty_sl_id'=>'','basic_penalty_amount'=>'','sef_tfoc_id'=>'','sef_gl_id'=>'','sef_sl_id'=>'','sef_amount'=>'','sef_discount_tfoc_id'=>'','sef_discount_gl_id'=>'','sef_discount_sl_id'=>'','sef_discount_amount'=>'','sef_penalty_tfoc_id'=>'','sef_penalty_gl_id'=>'','sef_penalty_sl_id'=>'','sef_penalty_amount'=>'','sh_tfoc_id'=>'','sh_gl_id'=>'','sh_sl_id'=>'','sh_amount'=>'','sh_discount_tfoc_id'=>'','sh_discount_gl_id'=>'','sh_discount_sl_id'=>'','sh_discount_amount'=>'','sh_penalty_tfoc_id'=>'','sh_penalty_gl_id'=>'','sh_penalty_sl_id'=>'','sh_penalty_amount'=>'');
        $this->slugs = 'cashier-real-property';

        foreach ($this->_muncipality->getRptActiveMuncipalityBarngyCodes() as $val) {
            $this->arrBarangay[$val->id]=$val->brgy_code.'-'.$val->brgy_name;
        }
        $getortype = $this->_cashierrealproperty->GetOrtypeid('2');
                $this->ortype_id =  $getortype->ortype_id;    
    }

    public function taxCollectionIndex(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $arrBarangay = $this->arrBarangay;
        return view('cashierrealproperty.taxcollection.index',compact('arrBarangay'));
    }

    public function taxCollectiongetList(Request $request){
        //$this->_cashierrealproperty->updateAccountReceibables(201,true);
        $data=$this->_cashierrealproperty->getTaxCollectionList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['date']=date("d/m/Y",strtotime($row->cashier_or_date));
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['full_name']=$row->full_name;
            $arr[$i]['cbd_assessed_value']=Helper::decimal_format($row->cbd_assessed_value);
            $arr[$i]['currentYearBasicTax']=Helper::decimal_format($row->currentYearBasicTax);
            $arr[$i]['previousYearBasicTax']=Helper::decimal_format($row->previousYearBasicTax);
            $arr[$i]['priorYearBasicTaxes']=Helper::decimal_format($row->priorYearBasicTaxes);
            
            $arr[$i]['currentYearSefTax']=Helper::decimal_format($row->currentYearSefTax);
            $arr[$i]['previousYearSefTax']=Helper::decimal_format($row->previousYearSefTax);
            $arr[$i]['priorYearSefTaxes']=Helper::decimal_format($row->priorYearSefTaxes);

            $arr[$i]['currentYearShtTax']=Helper::decimal_format($row->currentYearShtTax);
            $arr[$i]['previousYearShtTax']=Helper::decimal_format($row->previousYearShtTax);
            $arr[$i]['priorYearShtTaxes']=Helper::decimal_format($row->priorYearShtTaxes);

            $arr[$i]['penalty']=Helper::decimal_format($row->penalty);

            $arr[$i]['taxCredit']=Helper::decimal_format($row->taxCredit);
            $arr[$i]['advancePayment']=Helper::decimal_format($row->advancePayment);
            $arr[$i]['total_paid_amount']=Helper::decimal_format($row->total_paid_amount);


            /*$arr[$i]['details']='';
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');

            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cashier-real-property/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Real Property Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                        <a href="'.url('/cashier-real-property/printReceipt?id='.$row->id).'" target="_blank" title="Print Eng Cashiering"  data-title="Print Eng Cashiering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
                            <i class="ti-printer text-white"></i>
                        </a></div>';*/
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

    public function downloadExcel(Request $request){
        $data = $this->_cashierrealproperty->getTaxCollectionList($request,true);
        $excelData = $data['data'];
        //dd($excelData);
       return view('cashierrealproperty.ajax.exporttable',compact('excelData'));
        
    }

    public function index(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $arrBarangay = $this->arrBarangay;
        return view('cashierrealproperty.index',compact('arrBarangay'));
    }

    public function getList(Request $request){
        //$this->_cashierrealproperty->updateAccountReceibables(559,true);
        $data=$this->_cashierrealproperty->getList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['ownar_name']=$row->full_name;
            $arr[$i]['top_no']=$row->top_no;;
            $arr[$i]['or_no']=$row->or_no;
            $arr[$i]['total_paid_amount']=number_format($row->total_paid_amount,2);
            $arr[$i]['tax_credit']=number_format($row->tax_credit_amount,2);
            $arr[$i]['payment_terms'] = ($row->payment_terms > 0) ? '<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">' . config('constants.paymentTerms')[(int)$row->payment_terms] . '</span>' : '';
            $arr[$i]['details']='';
            $arr[$i]['status']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-danger" style="padding: 0.1rem 0.5rem !important;">Cancelled</span>');

            $arr[$i]['Date']=date("M d, Y",strtotime($row->created_at));
            $arr[$i]['cashier']=$row->username;
            $arr[$i]['action']='
                <div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/cashier-real-property/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Real Property Cashiering">
                        <i class="ti-eye text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                        <a href="'.url('/cashier-real-property/printReceipt?id='.$row->id).'" target="_blank" title="Print Eng Cashiering"  data-title="Print Eng Cashiering"  class="mx-3 btn btn-sm print text-white digital-sign-btn">
                            <i class="ti-printer text-white"></i>
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
    public function printReceipt(Request $request)
    {
        $id = $request->input('id'); 
        $ctcdata  = $this->_cashierrealproperty->getCertificateDetails($id);
        $billings = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
                    ->addSelect([
                        'priorYearBasicAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(basic_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearSefAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sef_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearShAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sh_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearBasicPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(basic_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearSefPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(sef_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearShPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(sh_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearFrom' => RptCtoBillingDetail::select(
                            DB::raw("MIN(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])->addSelect([
                        'priorYearTo' => RptCtoBillingDetail::select(
                            DB::raw("MAX(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','<',date("Y")-1)
                    ])

                    ->addSelect([
                        'previousYearBasicAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(basic_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])->addSelect([
                        'previousYearSefAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sef_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])
                    ->addSelect([
                        'previousYearShAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sh_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])->addSelect([
                        'previousYearBasicPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(basic_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])->addSelect([
                        'previousYearSefPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(sef_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])->addSelect([
                        'previousYearShPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(sh_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])->addSelect([
                        'previousYearFrom' => RptCtoBillingDetail::select(
                            DB::raw("MIN(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])->addSelect([
                        'previousYearTo' => RptCtoBillingDetail::select(
                            DB::raw("MAX(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y")-1)
                    ])

                    ->addSelect([
                        'currentYearBasicAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(basic_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearSefAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sef_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearShAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sh_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearBasicPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(basic_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearSefPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(sef_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearShPenalty' => RptCtoBillingDetailsPenalty::select(
                            DB::raw("COALESCE(SUM(sh_penalty_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearBasicDisc' => RptCtoBillingDetailsDiscount::select(
                            DB::raw("COALESCE(SUM(basic_discount_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearSefDisc' => RptCtoBillingDetailsDiscount::select(
                            DB::raw("COALESCE(SUM(sef_discount_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearShDisc' => RptCtoBillingDetailsDiscount::select(
                            DB::raw("COALESCE(SUM(sh_discount_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearFrom' => RptCtoBillingDetail::select(
                            DB::raw("MIN(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])->addSelect([
                        'currentYearTo' => RptCtoBillingDetail::select(
                            DB::raw("MAX(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','=',date("Y"))
                    ])

                    ->addSelect([
                        'advanceYearBasicAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(basic_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearSefAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sef_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearShAmount' => RptCtoBillingDetail::select(
                            DB::raw("COALESCE(SUM(sh_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearBasicDisc' => RptCtoBillingDetailsDiscount::select(
                            DB::raw("COALESCE(SUM(basic_discount_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearSefDisc' => RptCtoBillingDetailsDiscount::select(
                            DB::raw("COALESCE(SUM(sef_discount_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearShDisc' => RptCtoBillingDetailsDiscount::select(
                            DB::raw("COALESCE(SUM(sh_discount_amount))"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearFrom' => RptCtoBillingDetail::select(
                            DB::raw("MIN(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])->addSelect([
                        'advanceYearTo' => RptCtoBillingDetail::select(
                            DB::raw("MAX(cbd_covered_year)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')->where('cbd_covered_year','>',date("Y"))
                    ])
                    ->where('cb_or_no',isset($ctcdata->or_no)?$ctcdata->or_no:'')
                    ->groupBy('rpt_cto_billings.id')
                    ->get();
        $defaultFeesarr = $this->_cashierrealproperty->GetReqiestfees($id);
        //dd($billings[1]);
        // cash details
        switch ($ctcdata->payment_terms) {
            case 2:
                $arrPaymentbankDetails = $this->_cashierrealproperty->GetPaymentbankdetails($id);
                break;

            case 3:
                $arrPaymentbankDetails = $this->_cashierrealproperty->GetPaymentcheckdetails($id);
                break;
            
            default:
                $arrPaymentbankDetails =  (object)[]; 
                break;
        }

        // print reciept
        $data = [
            'province' => (isset($billings[0]->rptProperty->barangay->province->prov_desc))?$billings[0]->rptProperty->barangay->province->prov_desc:'',
            'date' => $ctcdata->created_at,
            'or_number' => $ctcdata->or_no,
            'payor' => $ctcdata->full_name,
            'transactions' => $defaultFeesarr,
            'total' => $ctcdata->net_tax_due_amount,
            'payment_terms' => $ctcdata->payment_terms,
            'cash_details' => $arrPaymentbankDetails,
            'surcharge' => $ctcdata->total_paid_surcharge,
            'interest' => $ctcdata->total_paid_interest,
            'muncipality' => (isset($billings[0]->rptProperty->locality->mun_desc))?$billings[0]->rptProperty->locality->mun_desc:'',
            'system_user' => (isset($ctcdata->username))?$ctcdata->username:'',
            'treasurer_name' => (isset($billings[0]->rptProperty->locality->newLocality->tresurer->standard_name))?$billings[0]->rptProperty->locality->newLocality->tresurer->standard_name:'',
            'tresurer_pos'   => (isset($billings[0]->rptProperty->locality->newLocality->loc_treasurer_position))?$billings[0]->rptProperty->locality->newLocality->loc_treasurer_position:'',
            'billings'  => $billings
            
        ];
        //dd($data);
        return $this->printReceiptF($data);
    }


    public function printReceiptF($data)
    {
        PDF::SetTitle('Receipt: '.$data['or_number'].'');    
        PDF::SetMargins(0, 0, 0,false);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('L', 'A4');
        //PDF::setPaper('a4', 'landscape');
        $border = 0;
        $topPos = 0;
        $htmldynahistory = '';
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        PDF::StartTransform();
        PDF::writeHTMLCell(0, 0, 0,0, '', $border,0);//Or 
        PDF::SetFont('Helvetica', '', 10);
        PDF::Rotate('360');
        /*PDF::writeHTMLCell(50, 0, 95,20,strtoupper($data['province']), $border,0,0,1,'C');//Payor
        PDF::writeHTMLCell(50, 0, 120,20,strtoupper($data['muncipality']), $border,0,0,1,'R');
        PDF::writeHTMLCell(50, 0, 140,20,date("d M Y",strtotime($data['date'])), $border,0,0,1,'R');
        PDF::writeHTMLCell(50, 0, 140,10,$data['or_number'], $border,0,0,1,'R');
        PDF::writeHTMLCell(50, 0, 92,30,$data['payor'], $border,0,0,1,'R');
        PDF::writeHTMLCell(40, 0, 140,30,Helper::numberToWord($data['total']), $border,0,0,1,'C');
        PDF::writeHTMLCell(40, 0, 180,30,Helper::number_format($data['total']), $border,0,0,1);
        $defaultX = 40;
        $defaultY = 40;
        $addX     = 10;
        $addY     = 10;*/
        PDF::writeHTMLCell(50, 0, 30,30,strtoupper($data['province']), $border,0,0,1,'C');//Payor
        PDF::writeHTMLCell(50, 0, 70,30,strtoupper($data['muncipality']), $border,0,0,1,'R');
        PDF::writeHTMLCell(50, 0, 140,30,date("d M Y",strtotime($data['date'])), $border,0,0,1,'R');
        PDF::writeHTMLCell(50, 0, 150,10,$data['or_number'], $border,0,0,1,'R');
        PDF::writeHTMLCell(50, 0, 42,35,$data['payor'], $border,0,0,1,'R');
        PDF::SetFont('Helvetica', '', 8);
       // PDF::SetFontSpacing(0);
        PDF::writeHTMLCell(55, 4, 140,35,Helper::numberToWord($data['total']), $border,0,0,1,'C',0);
        PDF::writeHTMLCell(61, 0, 152,35,Helper::number_format($data['total']), $border,0,0,1,'R');
        PDF::SetFont('Helvetica', '', 10);
        $defaultX = 0;
        $defaultY = 60;
        $addX     = 120;
        $addY     = 150;

        
        $defaultHeightForRange = 60;
        $defaultHeightForAssesValue = 63;
        $defaultHeightForLabels = 60;
        $defaultHeightForBasicValues = 60;
        $totalNetDue = 0;
        foreach ($data['billings'] as $key => $value) {
            PDF::writeHTMLCell(30, 0, $defaultX,$defaultY,strtoupper((isset($value->rptProperty->taxpayer_name))?$value->rptProperty->taxpayer_name:''), $border,0,0,1,'R');
            PDF::writeHTMLCell(30, 0, $defaultX+25,$defaultY,strtoupper((isset($value->rptProperty->barangay->brgy_name))?$value->rptProperty->barangay->brgy_name:''), $border,0,0,1,'C');
            PDF::writeHTMLCell(50, 0, $defaultX+50,$defaultY,strtoupper((isset($value->rptProperty->complete_pin))?$value->rptProperty->complete_pin:''), $border,0,0,1,'L');
            PDF::writeHTMLCell(50, 0, $defaultX+50,$defaultY+4,'TD# '.strtoupper((isset($value->rptProperty->rp_td_no))?$value->rptProperty->barangay->brgy_code.'-'.$value->rptProperty->rp_td_no.' '.$value->rptProperty->class_for_kind->pc_class_code.$value->rptProperty->propertyKindDetails->pk_code:''), $border,0,0,1,'L');
            $billingDetailsData = [];
            if(isset($value->advanceYearBasicAmount) && $value->advanceYearBasicAmount != null){
                $billingDetailsData[] = [
                    'type'        => 'advance',
                    'from'        => $value->advanceYearFrom,
                    'to'          => $value->advanceYearTo,
                    'basicAmount' => $value->advanceYearBasicAmount,
                    'basicPenalty' => 0,
                    'basicDiscount' => $value->advanceYearBasicDisc,
                    'sefAmount' => $value->advanceYearSefAmount,
                    'sefPenalty' => 0,
                    'sefDiscount' => $value->advanceYearSefDisc,
                    'shAmount' => $value->advanceYearShAmount,
                    'shPenalty' => 0,
                    'shDiscount' => $value->advanceYearShDisc,
                ];
            }
            if(isset($value->currentYearBasicAmount) && $value->currentYearBasicAmount != null){
                $billingDetailsData[] = [
                    'type'        => 'current',
                    'from'        => $value->currentYearFrom,
                    'to'          => $value->currentYearTo,
                    'basicAmount' => $value->currentYearBasicAmount,
                    'basicPenalty' => $value->currentYearBasicPenalty,
                    'basicDiscount' => $value->currentYearBasicDisc,
                    'sefAmount' => $value->currentYearSefAmount,
                    'sefPenalty' => $value->currentYearSefPenalty,
                    'sefDiscount' => $value->currentYearSefDisc,
                    'shAmount' => $value->currentYearShAmount,
                    'shPenalty' => $value->currentYearShPenalty,
                    'shDiscount' => $value->currentYearShDisc,
                ];
            }
            if(isset($value->previousYearBasicAmount) && $value->previousYearBasicAmount != null){
                $billingDetailsData[] = [
                    'type'        => 'previous',
                    'from'        => $value->previousYearFrom,
                    'to'          => $value->previousYearTo,
                    'basicAmount' => $value->previousYearBasicAmount,
                    'basicPenalty' => $value->previousYearBasicPenalty,
                    'basicDiscount' => 0,
                    'sefAmount' => $value->previousYearSefAmount,
                    'sefPenalty' => $value->previousYearSefPenalty,
                    'sefDiscount' => 0,
                    'shAmount' => $value->previousYearShAmount,
                    'shPenalty' => $value->previousYearShPenalty,
                    'shDiscount' => 0,
                ];
            }
            if(isset($value->priorYearBasicAmount) && $value->priorYearBasicAmount != null){

                $billingDetailsData[] = [
                    'type'        => 'prior',
                    'from'        => $value->priorYearFrom,
                    'to'          => $value->priorYearTo,
                    'basicAmount' => $value->priorYearBasicAmount,
                    'basicPenalty' => $value->priorYearBasicPenalty,
                    'basicDiscount' => 0,
                    'sefAmount' => $value->priorYearSefAmount,
                    'sefPenalty' => $value->priorYearSefPenalty,
                    'sefDiscount' => 0,
                    'shAmount' => $value->priorYearShAmount,
                    'shPenalty' => $value->priorYearShPenalty,
                    'shDiscount' => 0,
                ];
            }
            $totalPartialNetDue = 0;
            foreach ($billingDetailsData as $bill) {
                //dd($bill);
            if($bill['type'] == 'prior' || $bill['type'] == 'previous'){
                $penalityOrDiscount = Helper::decimal_format(($bill['basicPenalty'] != null)?$bill['basicPenalty']:0.00).'<br/>'.Helper::decimal_format(($bill['sefPenalty'] != null)?$bill['sefPenalty']:0.00).'<br/>'.Helper::decimal_format(($bill['shPenalty'] != null)?$bill['shPenalty']:0.00);
            }if($bill['type'] == 'current'){
                if($bill['basicDiscount'] > 0){
                    $penalityOrDiscount = '('.Helper::decimal_format(($bill['basicDiscount'] != null)?$bill['basicDiscount']:0.00).')<br/>('.Helper::decimal_format(($bill['sefDiscount'] != null)?$bill['sefDiscount']:0.00).')<br/>('.Helper::decimal_format(($bill['shDiscount'] != null)?$bill['shDiscount']:0.00).')';
                }else{
                    $penalityOrDiscount = Helper::decimal_format(($bill['basicPenalty'] != null)?$bill['basicPenalty']:0.00).'<br/>'.Helper::decimal_format(($bill['sefPenalty'] != null)?$bill['sefPenalty']:0.00).'<br/>'.Helper::decimal_format(($bill['shPenalty'] != null)?$bill['shPenalty']:0.00);
                }
                
            }if($bill['type'] == 'advance'){
                $penalityOrDiscount = '('.Helper::decimal_format(($bill['basicDiscount'] != null)?$bill['basicDiscount']:0.00).')<br/>('.Helper::decimal_format(($bill['sefDiscount'] != null)?$bill['sefDiscount']:0.00).')<br/>('.Helper::decimal_format(($bill['shDiscount'] != null)?$bill['shDiscount']:0.00).')';
                
            }
            $basicAmount   = ($bill['basicAmount'] != null)?$bill['basicAmount']:0;
            $basicPenalty  = ($bill['basicPenalty'] != null)?$bill['basicPenalty']:0;
            $basicDiscount = ($bill['basicDiscount'] != null)?$bill['basicDiscount']:0;
            $totalBasicDue = $basicAmount+$basicPenalty-$basicDiscount;

            $sefAmount   = ($bill['sefAmount'] != null)?$bill['sefAmount']:0;
            $sefPenalty  = ($bill['sefPenalty'] != null)?$bill['sefPenalty']:0;
            $sefDiscount = ($bill['sefDiscount'] != null)?$bill['sefDiscount']:0;
            $totalSefDue = $sefAmount+$sefPenalty-$sefDiscount;

            $shAmount   = ($bill['shAmount'] != null)?$bill['shAmount']:0;
            $shPenalty  = ($bill['shPenalty'] != null)?$bill['shPenalty']:0;
            $shDiscount = ($bill['shDiscount'] != null)?$bill['shDiscount']:0;
            $totalShDue = $shAmount+$shPenalty-$shDiscount;
            $totalPartialNetDue += $totalBasicDue+$totalSefDue+$totalShDue;

            PDF::writeHTMLCell(30, 0, $defaultX+90,$defaultHeightForRange,$bill['from'].'-'.$bill['to'], $border,0,0,1,'L');
            PDF::writeHTMLCell(30, 0, $defaultX+90,$defaultHeightForAssesValue,Helper::decimal_format($value->rptProperty->assessed_value_for_all_kind), $border,0,0,1,'L');
            PDF::writeHTMLCell(30, 0, $defaultX+115,$defaultHeightForLabels,'Basic<br />SEF<br />Housing', $border,0,0,1,'L');
            PDF::writeHTMLCell(30, 0, $defaultX+130,$defaultHeightForBasicValues,Helper::decimal_format($bill['basicAmount']).'<br/>'.Helper::decimal_format($bill['sefAmount']).'<br/>'.Helper::decimal_format($bill['shAmount']), $border,0,0,1,'L');

            PDF::writeHTMLCell(30, 0, $defaultX+150,$defaultHeightForBasicValues,$penalityOrDiscount, $border,0,0,1,'L');

            PDF::writeHTMLCell(30, 0, $defaultX+170,$defaultHeightForBasicValues,Helper::decimal_format($totalBasicDue).'<br/>'.Helper::decimal_format($totalSefDue).'<br/>'.Helper::decimal_format($totalShDue), $border,0,0,1,'L');
            $defaultHeightForRange += 10;
            $defaultHeightForAssesValue += 10;
            $defaultHeightForLabels += 15;
            $defaultHeightForBasicValues += 15;
            }
            $totalNetDue += $totalPartialNetDue;
            $billingDetails = [];

            $defaultY += 60;
            $defaultHeightForRange += 8;
            $defaultHeightForAssesValue += 8;
            $defaultHeightForLabels += 8;
            $defaultHeightForBasicValues += 8;
        }
        PDF::writeHTMLCell(30, 0, $defaultX+170,$defaultHeightForBasicValues,Helper::decimal_format($totalNetDue), $border,0,0,1,'L');
        //dd($defaultHeightForBasicValues);
        PDF::writeHTMLCell(50, 0, $defaultX+170,$defaultHeightForBasicValues+35,$data['treasurer_name'], $border,0,0,1,'L');
        PDF::writeHTMLCell(30, 0, $defaultX+170,$defaultHeightForBasicValues+10,$data['system_user'], $border,0,0,1,'L');
        
        $folder =  public_path().'/uploads/digital_certificates/';
        if(!File::exists($folder)) { 
            File::makeDirectory($folder, 0755, true, true);
        }
        $filename = $data['or_number'].'.pdf';

        $arrSign= $this->_commonmodel->isSignApply('cashier_real_property_collecting_officer');
        $isSignVeified = isset($arrSign)?$arrSign->status:0;
        
        $signType = $this->_commonmodel->getSettingData('sign_settings');

        if(!$signType || !$isSignVeified){
            //dd('d');
            PDF::Output($folder.$filename);
        }else{
            $signature = $this->_commonmodel->getuserSignature(Auth::user()->id);
            $path =  public_path().'/uploads/e-signature/'.$signature;
            if($isSignVeified==1 && $signType==2){
                $arrData['signerXyPage'] = $arrSign->pos_x.','.$arrSign->pos_y.','.$arrSign->pos_x_end.','.$arrSign->pos_y_end.','.$arrSign->d_page_no;
                if(!empty($signature) && File::exists($path)){
                    // Apply Digital Signature
                    PDF::Output($folder.$filename,'F');
                    $arrData['signaturePath'] = $signature;
                    $arrData['filename'] = $filename;
                    return $this->_commonmodel->applyDigitalSignature($arrData);
                }
            }
            if($isSignVeified==1 && $signType==1){
                // Apply E-Signature
                if(!empty($signature) && File::exists($path)){
                    PDF::Image($path,$arrSign->esign_pos_x, $arrSign->esign_pos_y, $arrSign->esign_resolution);
                }
            }
        }
        //dd('d');

        PDF::Output($folder.$filename);
    }
    
    public function store(Request $request){
        if($request->isMethod('get')){
            session()->forget('acceptedTdsForComputation');
        }
        $startEndYearAndQtrsObj = [];
        $arrTransactionNum=array(""=>"Please Select");
        $arrFund=array(""=>"Select");
        $arrBank=array(""=>"Select");
        $arrCancelReason = array(""=>"Please Select");
        $arrChequeTypes = array(""=>"Select");
        $arrCheque=array();
        $arrBankDtls=array();
        $arrChequeBankDtls=array("id"=>"","check_type_id"=>"","opayment_date"=>"","fund_id"=>"","bank_id"=>"","bank_account_no"=>"","opayment_transaction_no"=>"","opayment_check_no"=>"","opayment_amount"=>"");
        $barangyCode = (session()->has('cashierRealPropertySelectedBrgy'))?session()->get('cashierRealPropertySelectedBrgy'):'';
        $brngyDetails = $this->_Barangay->getActiveBarangayCode($barangyCode);
        $i=0;
        foreach($arrChequeBankDtls as $key=>$val){
            $arrCheque[$i][$key]=$val;
            $arrBankDtls[$i][$key]=$val;
        }
        foreach ($this->_cashierrealproperty->getFundCode() as $val) {
            $arrFund[$val->id]=$val->code;
        } 
        foreach ($this->_cashierrealproperty->getBankList() as $val) {
            $arrBank[$val->id]=$val->bank_code;
        }
        
        foreach ($this->_cashierrealproperty->getCancelReason() as $val) {
            $arrCancelReason[$val->id]=$val->ocr_reason;
        }
        foreach ($this->_cashierrealproperty->getChequeTypes() as $val) {
            $arrChequeTypes[$val->id]=$val->ctm_description;
        }

        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');     
        }
        $isOrAssigned = (int)$this->_cashierrealproperty->checkORAssignedORNot();

        $data = (object)$this->data;
        if($request->input('id')>0 && $request->input('submit')==""){
            $startEndYearAndQtrsObj = DB::table('cto_cashier_details')
                                      ->where('cashier_id',$request->id)
                                      ->join('rpt_cto_billing_details','rpt_cto_billing_details.id','=','cto_cashier_details.cbd_code')
                                      ->select('cto_cashier_details.cbd_code',
                                        DB::raw('MAX(rpt_cto_billing_details.cbd_covered_year) as maxYear'),
                                        DB::raw('MIN(rpt_cto_billing_details.cbd_covered_year) as minYear'),
                                        DB::raw("CASE WHEN rpt_cto_billing_details.sd_mode != 14 THEN MIN(rpt_cto_billing_details.sd_mode) ELSE 11 END as startMode"),
                                        DB::raw("CASE WHEN rpt_cto_billing_details.sd_mode != 14 THEN MAX(rpt_cto_billing_details.sd_mode) ELSE 44 END as endMode")
                                    )
                                      ->first();                          
            $data          = $this->_cashierrealproperty->getEditDetails($request->input('id'));
            //$creditDetails = $this->_cashierrealproperty->getTaxCreditDetails($data->input('id'));
            $data->created_at = date("d/m/Y",strtotime($data->created_at));
            $arrPaymentDetails = $this->_cashierrealproperty->getPaymentModeDetails($request->input('id'),3);

            $arrCheque = json_decode(json_encode($arrPaymentDetails), true);

            $arrPaymentDetails = $this->_cashierrealproperty->getPaymentModeDetails($request->input('id'),2);
            $arrBankDtls = json_decode(json_encode($arrPaymentDetails), true);
            
        }
        if($request->isMethod('post')){
            //dd('i am post request');
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = str_replace(",","", $request->input($key));
            }
            foreach((array)$this->dataDtls as $key=>$val){
                $this->dataDtls[$key] = $request->input($key);
            }
            $clientdata = $this->_commonmodel->getClientName($this->data['client_citizen_id']);
            $taxpayername = $clientdata->full_name;
            $this->data['taxpayers_name'] = $taxpayername;
            $this->data['cashier_particulars']='Real Property Tax Fee';
            $this->data['ortype_id'] =  config('constants.orTypeIds.real_property'); // Accountable Form No. 56
            //dd()
            $this->dataDtls['cashier_year'] = $this->data['cashier_year'] = date('Y');
            $this->dataDtls['cashier_month'] = $this->data['cashier_month'] = date('m');
            $this->dataDtls['tfoc_is_applicable'] = $this->data['tfoc_is_applicable'] ='2';
            $this->dataDtls['payee_type'] = $this->data['payee_type'] = "1";
            $this->dataDtls['client_citizen_id'] =$this->data['client_citizen_id'];

            $this->dataDtls['updated_by'] = $this->data['updated_by']=\Auth::user()->id;
            $this->dataDtls['updated_at'] = $this->data['updated_at'] = date('Y-m-d H:i:s');
            
            //dd($this->data);
            if($request->input('id')>0){
                unset($this->data['created_at']);
                unset($this->data['cashier_batch_no']);
                $this->_cashierrealproperty->updateData($request->input('id'),$this->data);
                $success_msg = 'Cashiering updated successfully.';
                $lastinsertid = $request->input('id');
            }else{
                $this->dataDtls['created_by'] = $this->data['created_by']=\Auth::user()->id;
                $this->dataDtls['created_at'] = $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['status'] = '1'; 
                $this->data['payment_type'] = 'Walk-In';
                $this->data['cashier_or_date'] = date("Y-m-d");

                $issueNumber = $this->getPrevIssueNumber();
                $cashier_issue_no = str_pad($issueNumber, 5, '0', STR_PAD_LEFT);
                $cashier_batch_no = date('Y')."-".$cashier_issue_no;

                $getorRegister = $this->_commonmodel->Getorregisterid($this->ortype_id,$this->data['or_no']);
                    if($getorRegister != Null){
                      $uptassignmentrarr = array('or_count'=>$getorRegister->or_count -1,'latestusedor' => $this->data['or_no']);
                        $this->_cashierrealproperty->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);  
                          
                      $this->data['or_assignment_id'] =  $getorRegister->assignid; 
                      $coaddata =$this->_commonmodel->getCoanoRegister($getorRegister->id);   
                      $this->data['or_register_id'] =  $getorRegister->id; 
                      $this->data['coa_no'] =  $coaddata->coa_no; 
                      if($getorRegister->or_count == 1){
                        $uptregisterarr = array('cpor_status'=>'2');
                        $this->_cashierrealproperty->updateOrRegisterData($getorRegister->id,$uptregisterarr);
                        $uptassignmentrarr = array('ora_is_completed'=>'1');
                        $this->_cashierrealproperty->updateOrAssignmentData($getorRegister->assignid,$uptassignmentrarr);
                      }   
                }

                $this->data['cashier_issue_no'] = $issueNumber; 
                $this->data['cashier_batch_no'] = $cashier_batch_no;
                $lastinsertid = $this->_cashierrealproperty->addData($this->data);
                //dd($this->data);
                //$lastinsertid = 64;
                //$this->_cashierrealproperty->updateAccountReceibables($lastinsertid);
                
                // update latest used OR
                if($request->input('isuserrange')){
                    $arrOrData = array('latestusedor' => $this->data['or_no']);
                    $ortype_id=config('constants.orTypeIds.real_property');  // Accountable Form No. 56
                    $this->_cashierrealproperty->updateOrUsed($ortype_id,$arrOrData);
                }

                // set used credit amount
                $this->updateUsedCreditAmount($request->input('previous_cashier_id'),1);

                $this->dataDtls['cashier_id'] = $lastinsertid;
                $this->dataDtls['cashier_issue_no'] =$issueNumber;
                $this->dataDtls['cashier_batch_no'] =$cashier_batch_no;
                $success_msg = 'Cashiering added successfully.';
                session()->put('RPT_PRINT_CASHIER_ID',$lastinsertid);
            }

            $Cashierid = $lastinsertid;
            //$Cashierid = 118;
            $sessionData = session()->get('acceptedTdsForComputation');
            $acceptedTds = ($sessionData != null)?$sessionData:[];
            session()->forget('acceptedTdsForComputation');
            $arrDetails = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])->whereIn('id',$acceptedTds)->get();
            $cashierDetails = DB::table('cto_cashier')->where('id',$Cashierid)->first();
            //dd($cashierDetails);
            $iterations = 1;
            if(count($arrDetails) >0){
                foreach ($arrDetails as $key => $value){
                    //$arrTfoc = $this->_cashierrealproperty->getTfocDtls($value);
                    $this->dataRealProp['cashier_year'] = date("Y");
                    $this->dataRealProp['cashier_month'] = date("m");
                    $this->dataRealProp['cashier_id'] = $lastinsertid;
                    $this->dataRealProp['top_transaction_id'] = $value->transaction_id;
                    $this->dataRealProp['tfoc_is_applicable'] = 2;
                    $this->dataRealProp['or_no'] = (isset($cashierDetails->or_no))?$cashierDetails->or_no:'';
                    // Apply tax credit
                    if($iterations == count($arrDetails)){
                    if($request->input('tcm_id')>0 && $this->data['total_amount_change']>0){
                        $propertyDetails = RptCtoBilling::select('rp_property_code')->where('id',(isset($acceptedTds[0]))?$acceptedTds[0]:'')->first();
                        $this->dataRealProp['tax_credit_amount'] = $this->data['total_amount_change'];
                    }
                    $this->dataRealProp['tcm_id'] = $this->data['tcm_id'];
                    $this->dataRealProp['tax_credit_gl_id'] = $this->data['tax_credit_gl_id'];
                    $this->dataRealProp['tax_credit_sl_id'] = $this->data['tax_credit_sl_id'];
                    $this->dataRealProp['previous_cashier_id'] = $this->data['previous_cashier_id'];
                    }
                    $iterations++;
                    $this->dataRealProp['cb_code'] = $value->id;
                    $this->dataRealProp['rp_code'] = $value->rp_code;
                    $this->dataRealProp['rp_property_code'] = $value->rp_property_code;
                    $this->dataRealProp['pk_code'] =  $value->pk_code; // Accountable Form No. 56
                    $this->dataRealProp['rp_tax_declaration_no'] = $value->rp_tax_declaration_no;
                    $this->dataRealProp['cb_billing_mode'] = $value->cb_billing_mode;
                    $this->dataRealProp['cb_control_no'] = $value->cb_control_no;
                    $this->dataRealProp['transaction_no'] = $value->transaction_no;
                    $this->dataRealProp['created_by'] = \Auth::user()->id;
                    $this->dataRealProp['created_at'] = date("Y-m-d H:i:s");
                    $checkdetailexist =  $this->_cashierrealproperty->checkCBBillingRecordIsExist($value->id,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->_cashierrealproperty->updateCashierDetailsRealPropertyData($checkdetailexist[0]->id,$this->dataRealProp);
                    } else{
                        $this->_cashierrealproperty->addCashierDetailsRealPropertyData($this->dataRealProp);
                    }
                    /* Update is paid status of billing */
                    $billingObj = RptCtoBilling::find($value->id);
                    $billingObj->cb_is_paid = 1;
                    $billingObj->cb_or_no   = $this->data['or_no'];
                    $billingObj->save();
                    $this->deleteDelinquencyData($value->id);
                    /* Update is paid status of billing */
                    /* Save Data in cashier details */
                    if($value->billingDetails != null){
                    foreach ($value->billingDetails as $bill) {
                        $arrayData = $bill->toArray();
                        foreach((array)$this->dataDtls as $key=>$val){
                            if(in_array($key,array_keys($arrayData))){
                                $this->dataDtls[$key] = $arrayData[$key];
                            }
                        
                     }
                     $this->dataDtls['cbd_code'] = $bill->id;
                     $this->dataDtls['pk_id'] = $value->rptProperty->pk_id;
                     $penltyData = DB::table('rpt_cto_billing_details_penalties')->where('cb_code',$bill->cb_code)->where('sd_mode',$bill->sd_mode)->where('cbd_covered_year',$bill->cbd_covered_year)->first();
                     if($penltyData != null){
                        $penltyData = (array)$penltyData;
                        foreach((array)$this->dataDtls as $key=>$val){
                            if(in_array($key,array_keys($penltyData))){
                                $this->dataDtls[$key] = $penltyData[$key];
                            }
                        
                     }
                     }
                     $discData = DB::table('rpt_cto_billing_details_discounts')->where('cb_code',$bill->cb_code)->where('sd_mode',$bill->sd_mode)->where('cbd_covered_year',$bill->cbd_covered_year)->first();
                     if($discData != null){
                        $discData = (array)$discData;
                        foreach((array)$this->dataDtls as $key=>$val){
                            if(in_array($key,array_keys($discData))){
                                $this->dataDtls[$key] = $discData[$key];
                            }
                        
                     }
                     }
                     $checkdetailexist =  $this->_cashierrealproperty->checkCBBillingDetailsRecordIsExist($bill->id,$Cashierid);
                    if(count($checkdetailexist) > 0){
                        $this->_cashierrealproperty->updateCashierDetailsData($checkdetailexist[0]->id,$this->dataDtls);
                    } else{
                        $savedID = $this->_cashierrealproperty->addCashierDetailsData($this->dataDtls);
                        $this->_cashierrealproperty->addDataInCasheringIncome($savedID);
                    }
                    $this->_cashierrealproperty->updateAccountReceibables($Cashierid,true);
                     //dd($this->dataDtls);
                    }
                }
                    /* Save Data in cashier details */
                }
            }//exit;
            $p_type = $request->input('payment_terms');
            if(!empty($request->input('fund_id'.$p_type))){
                foreach ($request->input('fund_id'.$p_type) as $key => $value){   
                    $paymentdata = array();
                    foreach($arrChequeBankDtls as $p_kay=>$p_val){
                        if($p_kay!='id' && isset($request->input($p_kay.$p_type)[$key])){
                            $paymentdata[$p_kay]=$request->input($p_kay.$p_type)[$key];
                        }
                    }
                    $paymentdata['opayment_amount'] = str_replace(",","", $paymentdata['opayment_amount']);
                    $paymentdata['cashier_id'] =$Cashierid;
                    $paymentdata['payment_terms'] = $p_type;
                    $paymentdata['updated_by'] =\Auth::user()->id;
                    $paymentdata['updated_at'] = date('Y-m-d H:i:s');
                    $pid = $request->input('pid'.$p_type)[$key];
                    if($pid > 0){
                        $this->_cashierrealproperty->updateCashierPaymentData($pid,$paymentdata);
                    }else{
                        $paymentdata['opayment_month'] = date('m');
                        $paymentdata['created_by'] = \Auth::user()->id;
                        $paymentdata['created_at'] = date('Y-m-d H:i:s');
                        $paymentdata['opayment_year'] = date('Y');
                        $paymentdata['status'] = 1;
                        $this->_cashierrealproperty->addCashierPaymentData($paymentdata);
                    }
                }
                $this->_cashierrealproperty->deleteOtherPaymentMode($p_type,$Cashierid);
            }
            // Save Payment done details
            $this->savePaymentDetails($Cashierid,$this->data['top_transaction_id']);
            // Log Details Start
            $logDetails['module_id'] =$Cashierid;
            $logDetails['log_content'] = 'Real Property Cashiering Created by '.\Auth::user()->name;
            $this->_commonmodel->updateLog($logDetails);
            $checkTxnCompleted = DB::table('cto_top_transactions')
                                          ->where('id',$this->data['top_transaction_id'])
                                          ->where('is_paid',1)
                                          ->get();
            $this->sensCashierSMS($Cashierid);
            if(!$checkTxnCompleted->isEmpty()){
                //return redirect()->route('rptcashier.index')->with('success', __($success_msg));
                return response()->json([
                    'status' => 'success',
                    'msg'    => $success_msg,
                    'id'     => $lastinsertid
                ]);
            }else{
                return response()->json([
                    'status' => 'partial',
                    'msg'    => 'OR #'.$this->data['or_no'].' has been generated successfully!',
                    'id'     => $lastinsertid
                ]);
            }                             
        }
        return view('cashierrealproperty.create',compact('data','arrFund','arrBank','arrBankDtls','arrCheque','arrCancelReason','arrChequeTypes','isOrAssigned','brngyDetails','startEndYearAndQtrsObj'));
    }

    public function sensCashierSMS($cashierId=''){
        $cashDetails = DB::table('cto_cashier as cc')
                                ->join('clients as c','c.id','=','cc.client_citizen_id')
                                ->join('cto_cashier_real_properties as ccrp','ccrp.cashier_id','=','cc.id')
                                ->join('rpt_properties as rp','rp.id','=','ccrp.rp_code')
                                ->join('rpt_property_kinds as rpk','rpk.id','=','rp.pk_id')
                                ->join('cto_top_transactions as ctt','ctt.id','=','cc.top_transaction_id')
                                ->select('cc.cashier_or_date','c.full_name','c.p_mobile_no','cc.net_tax_due_amount','ctt.transaction_no as tran_num',DB::raw('GROUP_CONCAT(DISTINCT rp.rp_tax_declaration_no) as tdNo'),DB::raw('GROUP_CONCAT(DISTINCT rpk.pk_description) as kind'))
                                ->where('cc.id',$cashierId)
                                ->first();
        $smsTemplate=SmsTemplate::searchBySlug($this->slugs)->first();
        if(!empty($smsTemplate) && $cashDetails->p_mobile_no != null)
        {
            $receipient = $cashDetails->p_mobile_no;
            $msg=$smsTemplate->template;
            $msg = str_replace('<NAME>', $cashDetails->full_name,$msg);
            $msg = str_replace('<DATE>', date('d/m/Y',strtotime($cashDetails->cashier_or_date)),$msg);
            $msg = str_replace('<PROPERTY_KIND>', $cashDetails->kind,$msg);
            $msg = str_replace('<TAX_DECLARATION_NO>', $cashDetails->tdNo,$msg);
            $msg = str_replace('<TOP_NO>',$cashDetails->tran_num,$msg);
            $msg = str_replace('<AMOUNT>',Helper::decimal_format($cashDetails->net_tax_due_amount),$msg);
            $msg = preg_replace("/[\n\r]/","\\n", $msg);
            try {
                $this->send($msg, $receipient);
            } catch (\Exception $e) {
                dd($e);
                 session()->forget('billingTempData');
                 return response()->json([
                        'status' => 'success',
                        'msg'    => 'Bill generated successfully!',
                        'cno'    => $controlNoForMultiple,
                        'txnNo'  =>  $savedBillingDetails->transaction_id
                    ]);
            }
        }
    }

    public function send($message, $receipient)
    {   
        $interface = new ComponentSMSNotificationRepository;
        $validate = $interface->validate();
        if ($validate > 0) {
            $setting = $interface->fetch_setting();
            $details = array(
                'type_id' => 1,
                'action_id' => NULL, // put action id please refer to sms_actions, mark as null if action applicable
                'masking_code' => $setting->mask->code,
                'messages' => $message,
                'created_at' => Carbon::now(),
                'created_by' => \Auth::user()->id
            );
            $message = $interface->create($details);
           
                //$this->sendSms($receipient, $message);
                $interface->send($receipient, $message);

            return true;
        } else {
            return false;
        }
    }

    public function deleteDelinquencyData($billingId = ''){
        $billingDetails = RptCtoBillingDetail::where('cb_code',$billingId)->get();
        foreach ($billingDetails as $bill) {
            RptDelinquent::where('rp_code',$bill->rp_code)->where('year',$bill->cbd_covered_year)->delete();
        }
    }
    public function updateUsedCreditAmount($cashierId,$status){
        if($cashierId>0){
            $arr['tax_credit_is_useup']=(int)$status;
            $this->_cashierrealproperty->updateCashierDetailsRealPropertyData($cashierId,$arr);
        }
    }
    public function creditAmountApply(){
        $sessionData  = session()->get('acceptedTdsForComputation');
        $acceptedTds  = ($sessionData != null)?$sessionData:[];
        $obj = $obj1  = RptCtoBilling::whereIn('id',$acceptedTds);//->pluck('rp_property_code')->toArray();
        $getPropCodes = $obj->pluck('rp_property_code')->toArray();
        $objForTxnId  = $obj1->get();
        $uniquePropCodes = array_unique($getPropCodes);
        //dd($uniquePropCodes);
        if(count($uniquePropCodes) > 1 || count($uniquePropCodes) == 0){
            $arr['isValid']=2;
            $arr['msg']    = 'Tax Credit is applicable only for Same Property Code Billings';
            return response()->json($arr);
        }
        /* Check is this last TD for multiple cashering */
        $txnDetails        = $objForTxnId->where('rp_property_code',(isset($uniquePropCodes[0])?$uniquePropCodes[0]:0))->first();
        $txnId             = (isset($txnDetails->transaction_id))?$txnDetails->transaction_id:0;
        $txnBillingObj     = $paidBillingsObj = RptCtoBilling::where('transaction_id',$txnId)->where('rp_property_code',(isset($uniquePropCodes[0]))?$uniquePropCodes[0]:0);
        $noOfCodesUnderTxn = $txnBillingObj->count();
        $paidCodes         = $paidBillingsObj->where('cb_is_paid',1)->count();
        if(($noOfCodesUnderTxn-($paidCodes+count($acceptedTds)) != 0)){
            $arr['isValid']=2;
            $arr['msg']    = 'Tax Credit is applicable only for Last Property Code in seperate billing!';
            return response()->json($arr);
        }
        //dd($paidCodes);
        /* Check is this last TD for multiple cashering */
        $propKind     = DB::table('rpt_properties')->select('pk_id')->where('rp_property_code',(isset($uniquePropCodes[0]))?$uniquePropCodes[0]:0)->first();
        //dd($propKind);
        $arrExist     = $this->_cashierrealproperty->checkCreditFacilityExist(($propKind != null)?$propKind->pk_id:0);
        if(isset($arrExist)){
            $arr['isValid']=1;
            $arr['tcm_id']=$arrExist->id;
            $arr['tax_credit_gl_id']=$arrExist->tcm_gl_id;
            $arr['tax_credit_sl_id']=$arrExist->tcm_sl_id;
        }else{
            $arr['isValid']=0;
            $url = url('/treasurer-tax-credit');
            $arr['errMsg']='Please add Tax Credit (Account Assignment) for Apply credit <a href="'.$url.'" target="_blank">Click Here</a>';
        }
        
        return response()->json($arr);
    }
    public function getPrevIssueNumber(){
        $number=1;
        $arrPrev = $this->_cashierrealproperty->getPreviousIssueNumber();
        if(isset($arrPrev)){
            $number = (int)$arrPrev->cashier_issue_no+1;
        }
        return $number;
    }
    public function checkOrUsedOrNot(Request $request){
        $or_no = $request->input('or_no');
        $isUsed = $this->_cashierrealproperty->checkOrUsedOrNot($or_no);
        $arr['isUsed']=$isUsed;
        if($isUsed){
            $arr['errMsg']='This O.R. No. already used, Please try other';
        }
         if(empty($isUsed)){
        $isUsed = $this->_commonmodel->checkOrinrange($or_no,$this->ortype_id);
        if(empty($isUsed)){
            $arr['isUsed']= 1;
            $arr['errMsg']='This O.R. No. not available in O.R. Range';
        } }
        echo json_encode($arr);
    }
    public function savePaymentDetails($cashierId,$top_transaction_id){
        if($cashierId>0){
            $checkAllBillingPaid = DB::table('rpt_cto_billings')
                     ->where('id',$top_transaction_id)
                     ->where('cb_is_paid',0)
                     ->get();
            $arrData=array();
            $arrData['is_paid']=1;
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            //dd($checkAllBillingPaid);
            if($checkAllBillingPaid->isEmpty()){
                $this->_cashierrealproperty->updateTopTransaction($top_transaction_id,$arrData);
            }
            //Update payment status in final assessments
            $arrAss = $this->_cashierrealproperty->getTopTransAssessmentIds($top_transaction_id);
            if(isset($arrAss)){
                $arrData=array();
                $arrData['payment_status']=1;
                $arrData['cashier_id']=$cashierId;
                $arrData['payment_date']=date('Y-m-d');
                $arrData['updated_by']=\Auth::user()->id;
                $arrData['updated_at']= date('Y-m-d H:i:s');
                $finalIds = explode(",",$arrAss->final_assessment_ids);
                $this->_cashierrealproperty->updateFinalAssessment($finalIds,$arrData);
            }
        }
    }
    public function cancelOr(Request $request){
        $pswVeriStatus = (session()->has('casheringVerifyPsw'))?((session()->get('casheringVerifyPsw') == true)?true:false):false;
       // dd($pswVeriStatus);
        if(!$pswVeriStatus){
                    return response()->json(['status'=>'verifypsw','msg'=>'Needs to verify password']);
                }
        session()->forget('casheringVerifyPsw');
        $id = $cashierId = $request->input('cashier_id');
        $transactionId = $request->input('top_transaction_id');
        $prev_cashier_id = $request->input('prev_cashier_id');
        //dd($prev_cashier_id);
        $ocr_id= $request->input('cancelreason');
        $remark= $request->input('remarkother');
        $updataarray = array('ocr_id'=>$ocr_id,'cancellation_reason'=>$remark,'status'=>'0');
        $this->_cashierrealproperty->updateData($id,$updataarray);
        
        $arrData=array();
        $arrData['is_paid']=0;
        $arrData['updated_by']=\Auth::user()->id;
        $arrData['updated_at']= date('Y-m-d H:i:s');
        $this->_cashierrealproperty->updateTopTransaction($transactionId,$arrData);

        $billingDataToUpdate = [
            'cb_or_no'   => '',
            'cb_is_paid' => 0
        ];
        $orDetails = DB::table('cto_cashier')->where('id',$cashierId)->first();
        if($orDetails != null){
            try {
                RptCtoBilling::where('cb_or_no',$orDetails->or_no)->update($billingDataToUpdate);
            } catch (\Exception $e) {
                
            }
        }

        //Update payment status in final assessments
        $arrAss = $this->_cashierrealproperty->getTopTransAssessmentIds($transactionId);
        if(isset($arrAss)){
            $arrData=array();
            $arrData['payment_status']=2; //Cancelled
            $arrData['updated_by']=\Auth::user()->id;
            $arrData['updated_at']= date('Y-m-d H:i:s');
            $finalIds = explode(",",$arrAss->final_assessment_ids);
            $this->_cashierrealproperty->updateFinalAssessment($finalIds,$arrData);
        }
        // update flag to zero for used credit amount
        $this->updateUsedCreditAmount($prev_cashier_id,0);
        $this->_cashierrealproperty->deleteCashierIncomeData($cashierId);
        $this->_cashierrealproperty->updateAccountReceibables($cashierId,false);
        // Log Details Start
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = 'Real Property O.R. Cancelled by '.\Auth::user()->name;
        $this->_commonmodel->updateLog($logDetails);
        return response()->json(['status' => 'success','msg' => 'O.R. No. Cancelled Successfully!']);
    }
    public function getOrnumber(Request $request){ 
        $checkflag = $request->input('orflag');
        $orNumber  =1;
        $ortype_id = $this->ortype_id;  // Accountable Form No. 56
        if($checkflag == '1'){
           $getorno = $this->_cashierrealproperty->getLatestOrNumber($ortype_id);
            if(!empty($getorno->or_no)){
                $orNumber = $getorno->or_no +1;
            }
        }else{
            $getorrange = $this->_commonmodel->getGetOrrange($this->ortype_id,\Auth::user()->id);
            if(empty($getorrange->latestusedor)){
                if(!empty($getorrange->ora_from)){
                    $orNumber = $getorrange->ora_from;
                }
            }else{
                $orNumber = $getorrange->latestusedor + 1;
            }
            
        }
        $orNumber = str_pad($orNumber, 7, '0', STR_PAD_LEFT);
        echo $orNumber;
    }

    public function acceptTd(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'td_no'  => 'required',
            ],
            [
                'td_no.required'=>'Required Field',
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $tdToAccept = $request->td_no;
        $previous_cashier_id = $request->input('previous_cashier_id');
        $cashierId = $request->input('id');
        $sessionData = session()->get('acceptedTdsForComputation');
        $acceptedTds = ($sessionData != null)?$sessionData:[];
        if(!in_array($tdToAccept,$acceptedTds)){
            $acceptedTds[] = $tdToAccept;
        }
        session()->put('acceptedTdsForComputation',$acceptedTds); 
        $startQtr = 11;
        $endQtr   = 44;
        $billingData = RptCtoBilling::with('billingDetails')->where('id',$tdToAccept)->first();
        if($billingData != null){
            $minSdCode = $billingData->billingDetails->min('sd_mode');
            $maxSdCode  = $billingData->billingDetails->max('sd_mode');
            if($minSdCode != '14'){
              $startQtr = $minSdCode;
              $endQtr   = $maxSdCode;
                 }
        }
        $arrCreditDtls = $this->getAppliedCreditDetails($billingData->rp_property_code,$previous_cashier_id,$cashierId);
        //dd
        return response()->json([
            'status' => 'success',
            'msg'    => 'Td accepted successfully!',
            'data'   => [
                'fromYear' => (isset($billingData->cb_covered_from_year))?$billingData->cb_covered_from_year:'',
                'toYear'   => (isset($billingData->cb_covered_to_year))?$billingData->cb_covered_to_year:'',
                'srtQtr' => $startQtr,
                'endQur' => $endQtr,
                'tax_credit_amount' => $arrCreditDtls['tax_credit_amount'],
                'previous_cashier_id' => $arrCreditDtls['previous_cashier_id'],
                'previous_or_date'    => $arrCreditDtls['previous_or_date'],
                'previous_or_no'      => $arrCreditDtls['previous_or_no']
            ]
        ]);
    }

    public function removeTd(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'td_no'  => 'required',
            ],
            [
                'td_no.required'=>'Required Field',
            ]
        );
        $arr=array('status'=>'validation_error');
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            return response()->json($arr);
        }
        $tdToRemove = $request->td_no;
        //dd($tdToRemove);
        $sessionData = session()->get('acceptedTdsForComputation');
        $acceptedTds = ($sessionData != null)?$sessionData:[];
        $key = array_search($tdToRemove, $acceptedTds);
        session()->forget('acceptedTdsForComputation.'.$key); 
        return response()->json([
            'status' => 'success',
            'msg'    => 'Bill removed successfully!',
            'data'   => [
                'fromYear' => '',
                'toYear'   => '',
                'srtQtr' => '',
                'endQur' => ''
            ]
        ]);
    }

    public function loadAcceptedTds(Request $request){
        if($request->has('id') && $request->id > 0){
            $acceptedTdsColl = DB::table('cto_cashier_real_properties')->where('cashier_id',$request->id)->pluck('cb_code');
            $acceptedTds     = $acceptedTdsColl->toArray();
            //dd($acceptedTdsColl);
        }else{
            $sessionData = session()->get('acceptedTdsForComputation');
            $acceptedTds = ($sessionData != null)?$sessionData:[];
        }

        //$billingData = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
                                  //->whereIn('id',$acceptedTds)->get();
        $billingDetails = DB::table('rpt_cto_billing_details as cbd')
                             ->join('rpt_properties as rp','rp.id','=','cbd.rp_code')
                             ->leftJoin('rpt_cto_billing_details_discounts as cbdd',function($j){
                                 $j->on('cbdd.cb_code','=','cbd.cb_code')
                                  ->on('cbdd.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdd.sd_mode','=','cbd.sd_mode');
                             })
                             ->leftJoin('rpt_cto_billing_details_penalties as cbdp',function($j){
                                 $j->on('cbdp.cb_code','=','cbd.cb_code')
                                  ->on('cbdp.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdp.sd_mode','=','cbd.sd_mode');
                             })
                             ->select(
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','rp.rp_tax_declaration_no',
                                DB::raw('COALESCE(cbd.basic_amount,0) as basicAmount'),
                                DB::raw('COALESCE(cbdd.basic_discount_amount,0) as basicDiscount'),
                                DB::raw('COALESCE(cbdp.basic_penalty_amount,0) as basicPenalty'),

                                DB::raw('COALESCE(cbd.sef_amount,0) as sefAmount'),
                                DB::raw('COALESCE(cbdd.sef_discount_amount,0) as sefDiscount'),
                                DB::raw('COALESCE(cbdp.sef_penalty_amount,0) as sefPenalty'),

                                DB::raw('COALESCE(cbd.sh_amount,0) as shAmount'),
                                DB::raw('COALESCE(cbdd.sh_discount_amount,0) as shDiscount'),
                                DB::raw('COALESCE(cbdp.sh_penalty_amount,0) as shPenalty'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDue')
                               )
                             ->whereIn('cbd.cb_code',$acceptedTds)->get();
         //dd($billingData);
        return view('cashierrealproperty.ajax.show',compact('billingDetails'));
    }

    public function loadCasheringInfo(Request $request){
        if($request->has('id') && $request->id > 0){
            $acceptedTdsColl = DB::table('cto_cashier_real_properties')->where('cashier_id',$request->id)->pluck('cb_code');
            $acceptedTds     = $acceptedTdsColl->toArray();
            
        }else{
            $sessionData = session()->get('acceptedTdsForComputation');
            $acceptedTds = ($sessionData != null)?$sessionData:[];
        }
        /*$sessionData = session()->get('acceptedTdsForComputation');
        $acceptedTds = ($sessionData != null)?$sessionData:[];*/
        $casheringInfo = $this->_cashierrealproperty->getEditDetails($request->input('id'));
        //dd($casheringInfo);
        $billingData = RptCtoBilling::with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])
        ->addSelect(
                    [
                        'totalBasicDue' => RptCtoBillingDetail::select(DB::raw("SUM(basic_total_due) AS totalBasicDue"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id'),
                        'totalPenaltyDue' => RptCtoBillingDetailsPenalty::select(DB::raw("SUM(penalty_total_due)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id') ,
                        'totalDiscountDue' => RptCtoBillingDetailsDiscount::select(DB::raw("SUM(dicount_total_due)"))
                        ->whereColumn('cb_code', 'rpt_cto_billings.id')            
                ]
            )
        ->whereIn('rpt_cto_billings.id',$acceptedTds)
        ->get();
        return view('cashierrealproperty.ajax.casheiringinfo',compact('billingData','casheringInfo'));

    }

    public function getPaymentDetails(Request $request){
        if($request->has('forRefresh') && $request->forRefresh == 0){
            session()->forget('acceptedTdsForComputation');
        }
        $transactionId = $request->input('transactionId');
        $cashierId = $request->input('id');
        //dd($cashierId);
        $previous_cashier_id = $request->input('previous_cashier_id');
        $arrTrans = $this->_cashierrealproperty->getTopTransactionDtls($transactionId);
        $billingData = [];
        if($arrTrans != null){
            $billingData = RptCtoBilling::where('transaction_no',$arrTrans->transaction_no)->with(['rptProperty','billingDetails.billingPenaltyDetails','billingDetails.billingDiscountDetails'])->get();
        }
        $cashierDetails = DB::table('cto_cashier')->where('id',$cashierId)->first();
        if($request->has('id') && $request->id > 0){
            $acceptedTdsColl = DB::table('cto_cashier_real_properties')->where('cashier_id',$request->id)->pluck('cb_code');
            $acceptedTds     = $acceptedTdsColl->toArray();
            //dd($acceptedTdsColl);
        }else{
            $sessionData = session()->get('acceptedTdsForComputation');
            $acceptedTds = ($sessionData != null)?$sessionData:[];
        }
        /*$arrCreditDtls = $this->getAppliedCreditDetails($arrTrans->busn_id,$previous_cashier_id,$cashierId);
        $arr['tax_credit_amount']=$arrCreditDtls['tax_credit_amount'];
        $arr['previous_cashier_id']=$arrCreditDtls['previous_cashier_id'];
        $arr['previous_or_date']=$arrCreditDtls['previous_or_date'];
        $arr['previous_or_no']=$arrCreditDtls['previous_or_no'];*/

        $view = view('cashierrealproperty.ajax.tdlisting',compact('billingData','acceptedTds','cashierDetails'))->render();
        return response()->json([
            'status' => 'success',
            'data'   => [
                'view' => $view,
                'taxpayer' => (isset($arrTrans->ownar_name))?$arrTrans->ownar_name:'',
                'client_id'=> (isset($arrTrans->clientId))?$arrTrans->clientId:'',
            ]
        ]);
        
    }

    public function getAppliedCreditDetails($propId,$previous_cashier_id,$cashierId){
        $arr['tax_credit_amount']='0.00';
        $arr['previous_cashier_id']='0';
        $arr['previous_or_date']='';
        $arr['previous_or_no']='N/A';
        //dd($propId);
        $arrExist = $this->_cashierrealproperty->checkExistCreditAmout($propId,$previous_cashier_id,$cashierId);
        $sessionData  = session()->get('acceptedTdsForComputation');
        $acceptedTds  = ($sessionData != null)?$sessionData:[];
        $getPropCodes  = RptCtoBilling::whereIn('id',$acceptedTds)->pluck('rp_property_code')->toArray();
        $uniquePropCodes = array_unique($getPropCodes);
        if(isset($arrExist) && count($uniquePropCodes) == 1){
            $arr['tax_credit_amount']=number_format($arrExist->tax_credit_amount,2);
            $arr['previous_cashier_id']=$arrExist->id;
            if(isset($arrExist->cashier_or_date)){
                $arr['previous_or_date']=date("d/m/Y",strtotime($arrExist->cashier_or_date));
            }
            $arr['previous_or_no']=$arrExist->or_no;
        }
        
        return $arr;
    }
    public function getPeriod($pm_id,$period_id){
        $html='';
        if($pm_id>0 && $period_id>0){
            $priod = config('constants.payModePartitionShortCut')[$pm_id][$period_id];
            $html='<option value="'.$period_id.'" selected>'.$priod.'</option>';
        }
        return $html;
    }
    public function addHiddendField($val){
        $html ='
        <input type="hidden" name="client_citizen_id" value="'.$val->client_id.'">
        <input type="hidden" name="busn_id" value="'.$val->busn_id.'">
        <input type="hidden" name="app_code" value="'.$val->app_code.'">
        <input type="hidden" name="pm_id" value="'.$val->pm_id.'">
        <input type="hidden" name="pap_id" value="'.$val->pap_id.'">';
        return $html;
    }
    public function getPertucularHtml($val){
        $surchage_interest = $val->surcharge_fee+$val->interest_fee;
        $total = $val->tfoc_amount+$surchage_interest;
        $html = '<tr class="font-style">
            <td>'.$val->assess_year.'</td>
            <td>'.$val->description.'</td>
            <td>'.number_format($val->tfoc_amount,2).'</td>
            <td>'.number_format($surchage_interest,2).'</td>
            <td>'.number_format($total,2).'</td>
        </tr>';

        $html .='
        <input type="hidden" name="surcharge_sl_id[]" value="'.$val->surcharge_sl_id.'">
        <input type="hidden" name="surcharge_fee[]" value="'.$val->surcharge_fee.'">
        <input type="hidden" name="interest_sl_id[]" value="'.$val->interest_sl_id.'">
        <input type="hidden" name="interest_fee[]" value="'.$val->interest_fee.'">
        <input type="hidden" name="subclass_id[]" value="'.$val->subclass_id.'">
        <input type="hidden" name="tfoc_id[]" value="'.$val->tfoc_id.'">
        <input type="hidden" name="tfc_amount[]" value="'.$val->tfoc_amount.'">';
        return $html;
    }
    public function generateFinalTotalHtml($totalAmount,$finalTotal,$totalCharges){
        $html = '<tr class="font-style">
            <td colspan="2" style="text-align: right;"><b>Total</b></td>
            <td>'.number_format($totalAmount,2).'</td>
            <td>'.number_format($totalCharges,2).'</td>
            <td class="red">'.number_format($finalTotal,2).'</td>
        </tr>';
        return $html;
    }

    public function getBillingTdAndOrNo($billingId = '214'){
        //dd($billingId);
        $billingDetails = DB::table('cto_cashier_real_properties as ccrp')
                                     ->join('cto_cashier as cc','cc.id','=','ccrp.cashier_id')
                                     ->join('cto_cashier_details as ccd','ccd.cashier_id','=','cc.id')
                                     ->join('rpt_cto_billing_details as cbd','cbd.id','=','ccd.cbd_code')
                                    ->leftJoin('rpt_cto_billing_details_discounts as cbdd',function($j){
                                 $j->on('cbdd.cb_code','=','cbd.cb_code')
                                  ->on('cbdd.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdd.sd_mode','=','cbd.sd_mode');
                             })
                             ->leftJoin('rpt_cto_billing_details_penalties as cbdp',function($j){
                                 $j->on('cbdp.cb_code','=','cbd.cb_code')
                                  ->on('cbdp.cbd_covered_year','=','cbd.cbd_covered_year')
                                  ->on('cbdp.sd_mode','=','cbd.sd_mode');
                             })
                             ->select(
                                'cc.or_no','cc.cashier_or_date','cc.top_transaction_id','cc.total_paid_amount','ccrp.rp_code','cbd.rpo_code',
                                DB::raw('((SUM(COALESCE(cbd.basic_amount,0))+SUM(COALESCE(cbd.sef_amount,0))+SUM(COALESCE(cbd.sh_amount,0)))+(SUM(COALESCE(cbdp.basic_penalty_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0)))-(SUM(COALESCE(cbdd.basic_discount_amount,0))+SUM(COALESCE(cbdd.sef_discount_amount,0))+SUM(COALESCE(cbdd.sh_discount_amount,0)))) as totalDue')
                             )
                                     ->where('cc.id',$billingId)
                                     ->groupBy('ccrp.rp_code')
                                     ->get();

          return $billingDetails;
    }

    public function updateCashierBillHistoryTaxpayers(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $cashier_id = $request->input('remote_cashier_id');
            $arrCash = $this->getBillingTdAndOrNo($cashier_id);
            if(isset($arrCash)){
                foreach ($arrCash as $cash) {
                    $transaction_no = DB::table('cto_top_transactions')->where('id',$cash->top_transaction_id)->pluck('transaction_no')->first();
                if(isset($transaction_no)){
                    $data['or_no'] = $cash->or_no;
                    $data['or_date'] = $cash->cashier_or_date;
                    $data['total_paid_amount'] = $cash->total_paid_amount;
                    $data['payment_status'] = 1;
                    $data['payment_date'] = date("Y-m-d");
                    $data['is_synced'] = 0;
                    //This is for Main Server
                    DB::table('rpt_bill_summary')->where('rp_code',$cash->rp_code)->where('transaction_no',$transaction_no)->where('client_id',$cash->rpo_code)->update($data);
                    // This is for Remote Server
                    try {
                        $remortServer = DB::connection('remort_server');
                        $remortServer->table('rpt_bill_summary')->where('rp_code',$cash->rp_code)->where('transaction_no',$transaction_no)->where('client_id',$cash->rpo_code)->update($data);
                        DB::table('rpt_bill_summary')->where('rp_code',$cash->rp_code)->where('transaction_no',$transaction_no)->where('client_id',$cash->rpo_code)->update(array('is_synced'=>1));
                    }catch (\Throwable $error) {
                        return $error;
                    }
                    echo json_encode(array("message"=>"Successfully updated"));
                }
            }
            }
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }

    public function updateRptOnlineAccessTaxpayers(Request $request){
        if($request->session()->get('IS_SYNC_TO_TAXPAYER')){
            $cashier_id = $request->input('remote_cashier_id');
            $arrCash = $this->getBillingTdAndOrNo($cashier_id);
            if(isset($arrCash)){
                foreach ($arrCash as $cash) {
                    $transaction_no = DB::table('cto_top_transactions')->where('id',$cash->top_transaction_id)->pluck('transaction_no')->first();
                if(isset($transaction_no)){
                    $data['or_no'] = $cash->or_no;
                    $data['or_date'] = $cash->cashier_or_date;
                    //$data['total_paid_amount'] = $cash->total_paid_amount;
                    $data['payment_status'] = 1;
                    $data['payment_date'] = date("Y-m-d");
                    $data['is_synced'] = 0;
                    //This is for Main Server
                    DB::table('rpt_property_online_accesses')->where('rp_code',$cash->rp_code)->where('taxpayer_id',$cash->rpo_code)->update($data);
                    // This is for Remote Server
                    try {
                        $remortServer = DB::connection('remort_server');
                        $remortServer->table('rpt_property_online_accesses')->where('rp_code',$cash->rp_code)->where('taxpayer_id',$cash->rpo_code)->update($data);
                        DB::table('rpt_property_online_accesses')->where('rp_code',$cash->rp_code)->where('taxpayer_id',$cash->rpo_code)->update(array('is_synced'=>1));
                    }catch (\Throwable $error) {
                        return $error;
                    }
                    echo json_encode(array("message"=>"Successfully updated"));
                }
            }
            }
        }else{
            echo json_encode(array("message"=>"Not synched, because IS_SYNC_TO_TAXPAYER is zero"));
        }
    }

    public function geAjaxfundselectlist(Request $request){
        $term=$request->input('term');
        $query = DB::table('acctg_fund_codes')->select('id','code as text');
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(code)'),'like',"%".strtolower($term)."%");
            });

        }  
        
        $data = $query->simplePaginate(20);             
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
    }

    public function geAjaxbankselectlist(Request $request){
        $term=$request->input('term');
        $query = DB::table('cto_payment_banks')->select('id','bank_code as text');
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(bank_code)'),'like',"%".strtolower($term)."%");
            });

        }  
        
        $data = $query->simplePaginate(20);             
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
        
    }

    public function geAjaxchequeselectlist(Request $request){
        $term=$request->input('term');
        $query = DB::table('check_type_masters')->select('id','ctm_description as text')->where('is_active','1')->orderby('ctm_description', 'ASC');
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(ctm_description)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
        
    }

    public function geAjaxtopnoselectlist(Request $request){
        $term=$request->input('term');
        $data = $this->_cashierrealproperty->getTransactions($request);              
         $morePages=true;
         $pagination_obj= json_encode($data);
           if (empty($data->nextPageUrl())){
            $morePages=false;
           }
            $results = array(
              "results" => $data->items(),
              "pagination" => array(
                "more" => $morePages
              )
            );
        return response()->json($results);
        
    }
}
