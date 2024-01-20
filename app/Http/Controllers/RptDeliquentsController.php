<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\RptDelinquent;
use App\Models\RptProperty;
use App\Models\RptCtoBilling;
use App\Models\CtoAccountsReceivables;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use File;
use App\Http\Controllers\Bplo\TreasurerAssessmentController;
use Illuminate\Support\Facades\Mail;
use DB;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\SmsTemplate;
use App\Repositories\ComponentSMSNotificationRepository;
use Carbon\Carbon;

class RptDeliquentsController extends Controller
{
    public $data = [];
    public $barangay = array(""=>"Select Barangay");
    public $taxpayer = array(""=>"Select Taxpayers");
    public $arrPropKinds = [];
    public $taxDeclaration = array(""=>"Select Tax Declaration");
    private $slugs;
    public function __construct(){
        $this->_Delinquency = new RptDelinquent(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->_rptproperty = new RptProperty();
        $this->_accountreceivables = new CtoAccountsReceivables();
        foreach ($this->_rptproperty->getPropertyKinds() as $val) {
            $this->arrPropKinds[$val->id]=$val->pk_code.'-'.$val->pk_description;
        }
        $this->data = array('id'=>'','busn_id'=>'','last_paid_date'=>'','busns_id_no'=>'','busn_name'=>'','ownar_name'=>'','application_date'=>'','application_date'=>'','pm_desc'=>'','p_email_address'=>'');
        $this->slugs = 'rpt-deliquency';
    }
    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        $muncipality = $this->_accountreceivables->getMuncipality();
        if($muncipality){$muncipality->mun_no;}
            else{ $muncipality->mun_no = '200'; }
        foreach ($this->_accountreceivables->getBarangay($muncipality->mun_no) as $val) {
            $this->barangay[$val->id]=$val->brgy_name;
        }
        $barangay = $this->barangay;
        foreach ($this->_accountreceivables->getTaxpayer() as $val) {
            if($val->suffix){
              $this->taxpayer[$val->id]=$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name.', '.$val->suffix;
            }
            else{
                $this->taxpayer[$val->id]=$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name;
            }
        }
        $taxpayer = $this->taxpayer;
        foreach ($this->_accountreceivables->getTaxDecration() as $val) {
            if($val->suffix){
              $this->taxDeclaration[$val->id]='['.$val->rp_tax_declaration_no.'=>'.$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name.', '.$val->suffix.']';
            }
            else{
                $this->taxDeclaration[$val->id]='['.$val->rp_tax_declaration_no.'=>'.$val->rpo_first_name.' '.$val->rpo_middle_name.' '.$val->rpo_custom_last_name.']';
            }
        }
        $kinds       = $this->arrPropKinds;
        $taxDeclaration = $this->taxDeclaration;
        return view('rptdelinquency.index',compact('barangay','taxpayer','taxDeclaration','kinds'));
    }
    public function getList(Request $request){
        //dd('test');
        $data=$this->_Delinquency->getList($request);
        //dd($data);
        $arr=array();
        $i="0"; 
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;  

        foreach ($data['data'] as $row){

            $sr_no=$sr_no+1;
            $arr[$i]['srno']=$sr_no;  
            $arr[$i]['taxDeclarationNo']=$row->rp_tax_declaration_no;
			$ownar_name = wordwrap($row->full_name, 30, "<br />\n");
            $arr[$i]['ownar_name']="<span class='showLess2'>".$ownar_name."</span>";
            $address = wordwrap($row->address, 30, "\n");
            $arr[$i]['Address']="<span class='showLess2'>".$address."</span>";
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $arr[$i]['class']=$row->propertyClassNew;
            $updatecode = wordwrap($row->uc_code.'-'.$row->uc_description, 2, "<br />\n");
            $arr[$i]['updatecode']="<span class='showLess3'>".$updatecode."</span>";
            $arr[$i]['effectivity']=$row->effectivity_year;
            $arr[$i]['email']=$row->p_email_address;
            $arr[$i]['brgy_name']=$row->brgy_name;
            $arr[$i]['lot_no']=$row->rp_lot_cct_unit_desc;
            //$arr[$i]['period']=$row->fromYear.' to '.$row->toYear;
            $arr[$i]['assessedValue']= Helper::decimal_format($row->rp_assessed_value);
            $arr[$i]['del_basictax']=Helper::decimal_format($row->deliquentBasic);
            $arr[$i]['del_sef']=Helper::decimal_format($row->deliquentSEF);
            $arr[$i]['del_sht']=Helper::decimal_format($row->eliquentSht);
            $totaldeliquency = $row->deliquentBasic+$row->deliquentSEF+$row->eliquentSht;
            $arr[$i]['del_total']=Helper::decimal_format($totaldeliquency);
            $arr[$i]['last_or_no']=$row->or_no;
            $arr[$i]['last_or_amount']=number_format(($row->total_paid_amount != null)?$row->total_paid_amount:0,2);
            $arr[$i]['last_or_date']=($row->cashier_or_date != null)?date("m/d/Y",strtotime($row->cashier_or_date)):'';
            $arr[$i]['total_amount']=number_format($row->totalDue,2);

            $dated = (!empty($row->acknowledged_date))?'dated '.date("M d, Y h:i a",strtotime($row->acknowledged_date)):'';
            $approveDtls=($row->is_approved==1)?'Acknowledged '.$dated:'';
            $approveDtls = wordwrap($approveDtls, 20, "<br>\n");
            $arr[$i]['is_approved'] = "<span class='showLess'>".$approveDtls."</span>";

            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showDeliquencyDetails" data-url="'.url('/rpt-deliquency/store?id='.$row->carId).'"  title="View"  data-title="Manage Real Proprty Tax: Delinquency">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-primary ms-2">
                    <a title="Print Order Of Payment"  data-title="Print Order Of Payment" class="mx-3 btn print btn-sm  align-items-center" target="_blank" href="'.url('/rpt-deliquency/generatePaymentPdf?prop_id='.(int)$row->id.'&id='.(int)$row->carId).'" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center sendEmail" title="Send Email" data-receiveId = "'.$row->carId.'"prop_id="'.$row->id.'" email="'.$row->p_email_address.'">
                        <i class="ti-email text-white"></i>
                    </a>
                </div>
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center sendSMS" data-amount="'.$row->totalDue.'" title="Send SMS" data-receiveId = "'.$row->carId.'"prop_id="'.$row->id.'">
                        <i class="ti-control-shuffle text-white"></i>
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
        $id = $request->id;

        $receiableDetails = DB::table('cto_accounts_receivables')
                           ->select('rp_code')
                           ->where('id',$id)
                           ->first();  
        $propDetails = RptProperty::where('id',(isset($receiableDetails->rp_code))?$receiableDetails->rp_code:'')->first();  
        
        return view('rptdelinquency.create',compact('propDetails','id'));
    }

    public function getDetailsList(Request $request){
        $cbdIsPaid = $request->cbd_is_paid;
        $customerName = "CASE 
                 WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                 WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END";      
        $id = $request->id;
        $rpCode = $request->rp_code;

        $arDetails  = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('id',$request->ar_id)->first();
        //echo $arDetails->rp_code_chain;exit;
        $query = DB::table('cto_accounts_receivable_details as card')
                           ->join('cto_accounts_receivables AS car', 'car.id', '=', 'card.ar_id')
                           ->select('rp.rp_tax_declaration_no','rp.id','ctt.transaction_no','cc.cashier_or_date',
                            DB::raw($customerName.' as customername'),
                            'card.rp_app_effective_year','card.ar_covered_year','card.cbd_is_paid','card.or_no','card.ar_covered_year','card.or_no','card.rp_property_code','uc.uc_code',
                            DB::raw('SUM(COALESCE(card.basic_amount)) as basic_amount'),
                            DB::raw('SUM(COALESCE(basic_penalty_amount)) as basic_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.basic_discount_amount)) as basic_discount_amount'),
                            DB::raw('SUM(COALESCE(card.sef_amount)) as sef_amount'),
                            DB::raw('SUM(COALESCE(card.sef_penalty_amount)) as sef_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.sef_discount_amount)) as sef_discount_amount'),
                            DB::raw('SUM(COALESCE(card.sh_amount)) as sh_amount'),
                            DB::raw('SUM(COALESCE(card.sh_penalty_amount)) as sh_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.sh_discount_amount)) as sh_discount_amount'),
                            )
                           ->join('rpt_properties AS rp', 'card.rp_code', '=', 'rp.id')
                           ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rp.uc_code')
                           ->leftJoin('cto_top_transactions as ctt', 'ctt.id', '=', 'card.top_transaction_id')
                           ->leftJoin('cto_cashier as cc', 'cc.id', '=', 'card.cashier_id')
                           ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'card.pk_id')
                           ->join('barangays AS bgy', 'bgy.id', '=', 'card.brgy_code_id')
                           ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                           //->whereIn('card.rp_code',$chainToShow)
                           ->where('card.rp_property_code',$id)
                           ->whereIn('card.rp_code',json_decode($arDetails->rp_code_chain))
                           ->whereIn('card.cbd_is_paid',[0,2])
                           ->where('card.ar_covered_year','<',date("Y"))
                           ->groupBy('card.ar_covered_year')
                           ->groupBy('card.rp_code')
                           ->orderBy('rp.rp_td_no','asc')
                           ->orderBy('card.ar_covered_year');
        if(isset($cbdIsPaid) && $cbdIsPaid != ''){
            $query->where(function ($sql) use($cbdIsPaid) {
                $sql->where('card.cbd_is_paid',$cbdIsPaid);

            });
        }                   
        $receiableDetails = $query->get();            
        return view('rptdelinquency.ajax.getlist',compact('receiableDetails','customerName'));
    }

    public function generateViewForDeliquency($data = '',$displayType = 'email'){
        $customerName = "CASE 
                 WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                 WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END";   
        $arDetails  = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('id',$data->receiveAbleID)->first();
        $receiableDetails = DB::table('cto_accounts_receivable_details as card')
                           ->select('rp.rp_tax_declaration_no','rp.id','ctt.transaction_no','cc.cashier_or_date',
                            DB::raw($customerName.' as customername'),
                            'card.rp_app_effective_year','card.ar_covered_year','card.or_no','card.or_no',
                            DB::raw('SUM(COALESCE(card.basic_amount)) as basic_amount'),
                            DB::raw('SUM(COALESCE(basic_penalty_amount)) as basic_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.basic_discount_amount)) as basic_discount_amount'),
                            DB::raw('SUM(COALESCE(card.sef_amount)) as sef_amount'),
                            DB::raw('SUM(COALESCE(card.sef_penalty_amount)) as sef_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.sef_discount_amount)) as sef_discount_amount'),
                            DB::raw('SUM(COALESCE(card.sh_amount)) as sh_amount'),
                            DB::raw('SUM(COALESCE(card.sh_penalty_amount)) as sh_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.sh_discount_amount)) as sh_discount_amount'),
                            'rp.dist_code','bgy.brgy_name','rp.pr_tax_arp_no','card.rp_assessed_value'
                            )
                           ->join('rpt_properties AS rp', 'card.rp_code', '=', 'rp.id')
                           ->leftJoin('cto_top_transactions as ctt', 'ctt.id', '=', 'card.top_transaction_id')
                           ->leftJoin('cto_cashier as cc', 'cc.id', '=', 'card.cashier_id')
                           ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'card.pk_id')
                           ->join('barangays AS bgy', 'bgy.id', '=', 'card.brgy_code_id')
                           ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                           ->where('card.ar_id',$data->receiveAbleID)
                           ->whereIn('card.rp_code',json_decode($arDetails->rp_code_chain))
                           ->whereIn('card.cbd_is_paid',[0,2])
                           ->where('card.ar_covered_year','<',date("Y"))
                           ->groupBy('card.ar_covered_year')
                           ->groupBy('card.rp_code')
                           ->orderBy('rp.rp_td_no','asc')
                           ->orderBy('card.ar_covered_year')
                           ->get();
                           //dd($receiableDetails);
        return view('mails.realPropertyTaxDeliquency',compact('data','displayType','receiableDetails'))->render();
    }
    public function generatePaymentPdf(Request $request)
    {
        $data = RptProperty::where('id', $request->prop_id)->first(); 
        $data->receiveAbleID = $request->id;


        if (isset($data)) {
            $customerName = "CASE 
                 WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                 WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END";   
        $arDetails  = DB::table('cto_accounts_receivables')->select('rp_code_chain','acknowledged_date','is_approved')->where('id',$data->receiveAbleID)->first();
        $receiableDetails = DB::table('cto_accounts_receivable_details as card')
                           ->select('rp.rp_tax_declaration_no','rp.id','ctt.transaction_no','cc.cashier_or_date',
                            DB::raw($customerName.' as customername'),
                            'card.rp_app_effective_year','card.ar_covered_year','card.or_no','card.or_no',
                            DB::raw('SUM(COALESCE(card.basic_amount)) as basic_amount'),
                            DB::raw('SUM(COALESCE(basic_penalty_amount)) as basic_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.basic_discount_amount)) as basic_discount_amount'),
                            DB::raw('SUM(COALESCE(card.sef_amount)) as sef_amount'),
                            DB::raw('SUM(COALESCE(card.sef_penalty_amount)) as sef_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.sef_discount_amount)) as sef_discount_amount'),
                            DB::raw('SUM(COALESCE(card.sh_amount)) as sh_amount'),
                            DB::raw('SUM(COALESCE(card.sh_penalty_amount)) as sh_penalty_amount'),
                            DB::raw('SUM(COALESCE(card.sh_discount_amount)) as sh_discount_amount'),
                            'rp.dist_code','bgy.brgy_name','rp.pr_tax_arp_no','card.rp_assessed_value'
                            )
                           ->join('rpt_properties AS rp', 'card.rp_code', '=', 'rp.id')
                           ->leftJoin('cto_top_transactions as ctt', 'ctt.id', '=', 'card.top_transaction_id')
                           ->leftJoin('cto_cashier as cc', 'cc.id', '=', 'card.cashier_id')
                           ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'card.pk_id')
                           ->join('barangays AS bgy', 'bgy.id', '=', 'card.brgy_code_id')
                           ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                           ->where('card.ar_id',$data->receiveAbleID)
                           ->whereIn('card.rp_code',json_decode($arDetails->rp_code_chain))
                           ->whereIn('card.cbd_is_paid',[0,2])
                           ->where('card.ar_covered_year','<',date("Y"))
                           ->groupBy('card.ar_covered_year')
                           ->groupBy('card.rp_code')
                           ->orderBy('rp.rp_td_no','asc')
                           ->orderBy('card.ar_covered_year')
                           ->get();

            $documentFileName = $request->prop_id . "-Deliquency.pdf";
            $document = new PDF([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => '3',
                'margin_top' => '8',
                'margin_bottom' => '8',
                'margin_footer' => '2',
            ]);

            $header = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
            ];

            // Pass the $receiableDetails to the view
            $html = view('mails.realPropertyAccountReceiablesPDF', compact('data', 'receiableDetails','arDetails'))->render();
            $document->WriteHTML($html);
            Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));
            $folder = public_path() . '/uploads/summary';
            
            if (!File::exists($folder)) { 
                File::makeDirectory($folder, 0777, true, true);
            }
            
            $filename = public_path() . "/uploads/summary/" . $documentFileName;
            $document->Output($filename, "F");

            // Get the file back from storage with the given header information
            return Storage::disk('public')->download($documentFileName, 'Request', $header);
        }
    }
    
    public function sendEmail(Request $request){
        $arrDtls = RptProperty::where('id',$request->prop_id)->first(); 
        $arrDtls->receiveAbleID = $request->id;
        $id=$request->input('id');

        /*$arrDtls = RptProperty::where('id',163)->first(); 
        $arrDtls->receiveAbleID = 29;*/
        //$id=$request->input('id');
        //return $this->generateViewForDeliquency($arrDtls,'email');
        //dd($arrDtls->rptProperty->propertyOwner->p_email_address);
        if(isset($arrDtls)){
            if(!empty($arrDtls->propertyOwner->p_email_address)){
                //$this->_TreasurerAssessment = new TreasurerAssessmentController(); 
                $data=array();
                //$encrypt = $this->_commonmodel->encryptData($id);
                $encrypt = encrypt($id);
                $approve_url = url('/rpt-deliquency/approveDelinquency/'.$encrypt);
                //dd($approve_url);
                $description = 'Your payment still pending, Please pay as soon as possible.';
                $html = $this->generateHtmlOrPdf($arrDtls,2020,'delinquencyEmail');
                $html = str_replace("{APPROVE_URL}",$approve_url, $html);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$arrDtls->propertyOwner->p_email_address, $html);

                $data['message'] = $html;
                $data['to_name']=$arrDtls->propertyOwner->rpo_first_name;
                $data['to_email']=$arrDtls->propertyOwner->p_email_address;
                $data['subject']='Delinquency Notice';

                Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                }); 
            }
        }
    }

    public function sendDeliquentSMS(Request $request){
            $arrDtls = RptProperty::where('id',$request->prop_id)->first(); 
            $arrDtls->receiveAbleID = $request->id;
            $id=$request->input('id');
            $smsTemplate=SmsTemplate::searchBySlug($this->slugs)->first();
//dd($arrDtls->propertyOwner->p_mobile_no);
            if(!empty($smsTemplate) && isset($arrDtls->propertyOwner->p_mobile_no) && $arrDtls->propertyOwner->p_mobile_no != null){
            $receipient = $arrDtls->propertyOwner->p_mobile_no;
            $msg=$smsTemplate->template;
            $msg = str_replace('<NAME>', (isset($arrDtls->propertyOwnerfull_name))?$arrDtls->propertyOwnerfull_name:'',$msg);
            $msg = str_replace('<DATE>', date('d/m/Y'),$msg);
            $msg = str_replace('<PROPERTY_KIND>', (isset($arrDtls->propertyKindDetails->pk_description))?$arrDtls->propertyKindDetails->pk_description:'Land',$msg);
            $msg = str_replace('<TAX_DECLARATION_NO>', $arrDtls->rp_tax_declaration_no,$msg);
            $msg = str_replace('<AMOUNT>',Helper::decimal_format($request->amount),$msg);
            $msg = preg_replace("/[\n\r]/","\\n", $msg);
            
            try {
                $response = $this->send($msg, $receipient);
                return response()->json([
                        'status' => 'success',
                        'msg'    => 'SMS sent Successfully!',
                    ]);
            } catch (\Exception $e) {
                 return response()->json([
                        'status' => 'error',
                        'msg'    => $e->getMessage(),
                    ]);
            }
        }else{
            return response()->json([
                        'status' => 'error',
                        'msg'    => 'Something went wrong, Please try again!',
                    ]);
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

    public function generateHtmlOrPdf($data,$year,$displayType){
        if($displayType=="pdf"){
            $this->generatePdfFile($bus_id,$html,$data,$displayType);
        }elseif($displayType=='delinquencyEmail'){
            return $this->generateViewForDeliquency($data);
        }elseif($displayType=='assessmentEmail'){
            $data['isShowBtn']=0;
            $data['username']=$arrBussDtls->rpo_custom_last_name;
            $Finalhtml = view('mails.taxOrderPaymentEmail',compact('html','data','displayType'));
            return $Finalhtml;
        }
        // ************* End Display Content Details ************************
    }
    public function approveDelinquency(Request $request,$encrypt){
        
        $id = decrypt($encrypt);
        if($id>0){
            //dd('d');
            $arrData['is_approved']=1;
            $arrData['acknowledged_date']=date('Y-m-d H:i:s');
            $this->_Delinquency->updateData($id,$arrData);
            return view('errors.DelinquencyThankyou');
        }
    }

    public function getDeliquentsTds($value=''){
        $tdsNotPaidYet = RptProperty::with([
            'revisionYearDetails'=>function($q){
                $q->select('id','rvy_revision_year');
            }
        ])
        ->select('rpt_properties.id','rpt_properties.rp_property_code','rpt_properties.pk_is_active','rpt_properties.rp_app_taxability','rpt_properties.rvy_revision_year_id','rpt_properties.rp_td_no','rpt_properties.rp_app_effective_year','billd.cbd_covered_year',DB::raw('MAX(billd.sd_mode) as lastPaidMode'))
        //->where('pk_is_active',1)

        ->leftJoin('rpt_cto_billings as bill',function($j){
                                 $j->on('bill.rp_code','=','rpt_properties.id');
                                 $j->where('bill.cb_is_paid',1);
                                 $j->join('rpt_cto_billing_details as billd',function($jagain){
                                    $jagain->on('billd.rp_code','=','bill.rp_code')
                                           ->orderBy('billd.cbd_covered_year','DESC');
                                 });
                                 //$j->whereRaw('billd.cbd_covered_year = rpt_delinquents.year');
                               })
        ->where('rp_app_effective_year','<',date("Y"))
        ->where('rp_app_taxability',1)
        ->whereNotIn('rpt_properties.id',function($query){
                      $query->from('rpt_cto_billings as bill')
                      ->leftJoin('rpt_cto_billing_details as billd',function($join){
                          $join->on('billd.rp_code','=','billd.rp_code');
                     });
                      $query->select('billd.rp_code')
                      ->whereIn('billd.sd_mode',[44,14])
                      ->where('bill.cb_is_paid',1);
            })
        ->groupBy('rpt_properties.id')->get()
          ->take(50);
          //dd($tdsNotPaidYet);
          foreach ($tdsNotPaidYet as $value) {
            //dd($value);
              $startingYear = (isset($value->rp_app_effective_year))?$value->rp_app_effective_year:date("Y");
              //dd($startingYear);
              $dataToSaveInDelq = [
                'rp_code' => $value->id,
                'rp_property_code' => $value->rp_property_code,
                'created_at'       => date("Y-m-d H:i:s")
              ];
              $rptPropObj = new RptProperty;
              $billObj   = new RptCtoBilling;
              for ($i=$startingYear; $i <= date("Y")-1 ; $i++) { 
                $modes     = [14];
              if($value->lastPaidMode != null && $value->cbd_covered_year == $i && !in_array($value->lastPaidMode,[14,44])){
                $indesx = array_search($value->lastPaidMode,array_keys(Helper::billing_quarters()));
                $qtrOfBilling = array_keys(Helper::billing_quarters())[$indesx+1];
                $modes  = array_slice(array_keys(Helper::billing_quarters()),$indesx+1);
                  }
                $getPenalityRateDate = $billObj->getPenalityRateData($i);
                $penaltyRate = (isset($getPenalityRateDate->cps_maximum_penalty))?$getPenalityRateDate->cps_maximum_penalty:0;  
                   foreach($modes as $mode){
                    if($getPenalityRateDate->cps_penalty_limitation == 1){
                        $response = $rptPropObj->calculatePenaltyFee($value->id,$i,$mode,$penaltyRate,true);
                    }else{
                        $moth = date("n");
                                    $getPenalityRateDate = DB::table('rpt_cto_penalty_tables')->where('cpt_effective_year',$i)->where('cpt_current_year',date("Y
                                        "))->first();
                                        if($getPenalityRateDate != null){
                                            $monthProp = 'cpt_month_'.$moth;
                                            $penalityRate        = $getPenalityRateDate->$monthProp;
                                     }
                        $response = $rptPropObj->calculatePenaltyFee($value->id,$i,$mode,$penalityRate,true);
                    }
                   
                   $dataToSaveInDelq['year'] = $i;
                   $dataToSaveInDelq['sd_mode'] = $mode;
                   $dataToSaveInDelq['basic_amount'] = $response['basicAmount'];
                   $dataToSaveInDelq['sef_amount'] = $response['basicSefAmount'];
                   $dataToSaveInDelq['sh_amount'] = $response['basicShAMount'];
                   $dataToSaveInDelq['basic_penalty'] = $response['basicPenalty'];
                   $dataToSaveInDelq['sef_penalty'] = $response['sefPenalty'];
                   $dataToSaveInDelq['sh_penalty'] = $response['shPenalty'];
                   $dataToSaveInDelq['total_amount'] = ($response['basicAmount']+$response['basicPenalty'])+($response['basicSefAmount']+$response['sefPenalty'])+($response['basicShAMount']+$response['shPenalty']);
                   $checkAlreadyExist = $this->_Delinquency
                                             ->where('rp_code',$value->id)
                                             ->where('year',$i)
                                             ->get();
                    if($checkAlreadyExist->isEmpty()){
                        $this->_Delinquency->adData($dataToSaveInDelq);
                    }
                   }
                                            
              }
          }
        //dd($tdsNotPaidYet);
    }
}
