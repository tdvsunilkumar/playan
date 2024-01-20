<?php

namespace App\Http\Controllers;

use App\Models\CtoAccountsReceivables;
use App\Models\RptProperty;
use App\Models\RptCtoBilling;
use App\Models\RptCtoTaxRevenue;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;
use File;
use App\Models\CommonModelmaster;
use Illuminate\Support\Facades\Mail;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Storage;

class AccountRreceivablesPropertyController extends Controller
{

    public $data = [];
    public $barangay = array(""=>"Select Barangay");
    public $taxpayer = array(""=>"Select Taxpayers");
    public $arrPropKinds = [];
    public $taxDeclaration = array(""=>"Select Tax Declaration");
    private $slugs;

    public function __construct(){
        $this->_accountreceivables = new CtoAccountsReceivables();
        $this->_commonmodel = new CommonModelmaster();
        $this->_rptproperty = new RptProperty();
        $this->_ctobilling = new RptCtoBilling;
        foreach ($this->_rptproperty->getPropertyKinds() as $val) {
            $this->arrPropKinds[$val->id]=$val->pk_code.'-'.$val->pk_description;
        }
        $this->data = array('id'=>'','bot_occupancy_type'=>'');
        $this->slugs = 'administrative/fire-protection/occupancy-type';
          
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
        $kinds       = $this->arrPropKinds;
        return view('accountrreceivablesproperty.index',compact('kinds'));
    }
    
    
    public function getList(Request $request){
       // $this->addOrUpdateInCtoReceive(205,450);
        $data=$this->_accountreceivables->getList($request);
        $arr=array();
        $i="0";    
        $j=(int)$request->input('start')-1; 
        $j=$j>0? $j+1:0;
        foreach ($data['data'] as $row){
            $j=$j+1;
            $status =($row->status == 1) ? '' : 
            '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            $arr[$i]['srno']=$j;
            $arr[$i]['arpno']=$row->rp_tax_declaration_no;
            $customername = wordwrap($row->customername, 30, "<br />\n");
            $arr[$i]['taxpayer']="<span class='showLess2'>".$customername."</span>";
            //$address = wordwrap($row->full_address, 30, "\n");
            $address = wordwrap('', 30, "\n");
            $arr[$i]['Address']="<span class='showLess2'>".$row->full_address."</span>";
            $arr[$i]['location']=strtoupper($row->brgy_name);
            $arr[$i]['pin']=$row->rp_pin_declaration_no;
            $rp_lot_cct_unit_desc = wordwrap($row->rp_lot_cct_unit_desc, 30, "<br />\n");
            $arr[$i]['lot']="<span class='showLess2'>".$rp_lot_cct_unit_desc."</span>";
            $arr[$i]['class']=$row->propertyClassNew;
            $updatecode = wordwrap($row->uc_code.'-'.$row->uc_description, 2, "<br />\n");
            $arr[$i]['updatecode']="<span class='showLess3'>".$updatecode."</span>";
            $arr[$i]['effectivity']=$row->effectivity_year;
            $arr[$i]['top']="";
            $arr[$i]['orno']=$row->or_no;
            $arr[$i]['ordate']=($row->cashier_or_date != null)?date("m/d/Y",strtotime($row->cashier_or_date)):'';
            $arr[$i]['oramount']=Helper::decimal_format($row->total_paid_amount);
            $arr[$i]['assessedvalue']=Helper::decimal_format($row->rp_assessed_value);
            $arr[$i]['out_basictax']=Helper::decimal_format($row->outStandingBasic);
            $arr[$i]['out_sef']=Helper::decimal_format($row->outStandingSef);
            $arr[$i]['out_sht']=Helper::decimal_format($row->outStandingSht);
            $totalOut = $row->outStandingBasic+$row->outStandingSef+$row->outStandingSht;
            $arr[$i]['out_total']=Helper::decimal_format($totalOut);

            $arr[$i]['del_basictax']=Helper::decimal_format($row->deliquentBasic);
            $arr[$i]['del_sef']=Helper::decimal_format($row->deliquentSEF);
            $arr[$i]['del_sht']=Helper::decimal_format($row->eliquentSht);
            $totaldeliquency = $row->deliquentBasic+$row->deliquentSEF+$row->eliquentSht;
            $arr[$i]['del_total']=Helper::decimal_format($totaldeliquency);
            $arr[$i]['total']=Helper::decimal_format($totalOut+$totaldeliquency);
            
            $arr[$i]['is_active']=($row->status==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['action']='
                <div class="action-btn bg-info ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center showDetails" data-url="'.url('/account-receivables-property/show?id='.$row->carId).'"  title="Edit"  data-title="Manage Occupancy Type ">
                        <i class="ti-eye text-white"></i>
                    </a>
                    </div>
                    '.$status.'
                </div>';
                $arr[$i]['action'] .='<div class="action-btn bg-primary ms-2">
                    <a title="Print Order Of Payment"  data-title="Print Order Of Payment" class="mx-3 btn print btn-sm  align-items-center" target="_blank" href="'.url('/account-receivables-property/generatePaymentPdf?prop_id='.(int)$row->id.'&id='.(int)$row->carId).'" >
                        <i class="ti-printer text-white"></i>
                    </a>
                </div>';
                $arr[$i]['action'] .='<div class="action-btn bg-info ms-2">
                    <a data-toggle="modal" href="javascript:void(0)"  id="displayAnnotationSpecialPropertyStatusModal" class="btn btn-primary sendEmailDtlsIndex btn-sm " style="padding:0px;background: none;bolder:none;" data-rp_code="'.$row->id.'" data-user_email="'.$row->p_email_address.'" data-receiableId="'.$row->carId.'" ><i class="ti-email text-white" style="padding:0px"></i></a>
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
    
    public function ActiveInactive(Request $request){
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('is_active' => $is_activeinactive);
        $this->_bfpoccupancytype->updateActiveInactive($id,$data);
}


// Function to find the chain
public function findChain($array ,$startId) {
 

$chain = [$startId];

    foreach ($array as $item) {
        if ($item->id == $startId && $item->rp_app_cancel_by_td_id !== null) {
            // Split the comma-separated values into an array
            $nextIds = explode(',', $item->rp_app_cancel_by_td_id);

            // Recursively find the chain for each next ID
            foreach ($nextIds as $nextId) {
                $nextId = trim($nextId);
                if (!in_array($nextId, $chain)) {
                    $chain[] = $nextId;
                    $subChain = $this->findChain($array, $nextId);
                    $chain = array_merge($chain, $subChain);
                }
            }
        }
    }

    return $chain;
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
        return view('accountrreceivablesproperty.ajax.getlist',compact('receiableDetails','customerName'));
    }

    public function sendEmail(Request $request){
        //dd('sss');
        $arrDtls = RptProperty::where('id',$request->prop_id)->first(); 
        $arrDtls->receiveAbleID = $request->id;
       // dd($arrDtls);
        if(isset($arrDtls)){
            if(!empty($arrDtls->propertyOwner->p_email_address)){
                $data=array();
                $encrypt = $this->_commonmodel->encryptData($request->prop_id);
                $approve_url = url('/approveDelinquency/'.$encrypt);
                $description = 'Your payment still pending, Please pay as soon as possible.';
                $html = $this->generateHtmlOrPdf($arrDtls,2020,'deliqnuencyOutstanding');
                $html = str_replace("{APPROVE_URL}",$approve_url, $html);
                $html = str_replace("{DESCRIPTION}",$description, $html);
                $html = str_replace("{USER_EMAIL}",$arrDtls->propertyOwner->p_email_address, $html);

                $data['message'] = $html;
                $data['to_name']=$arrDtls->propertyOwner->rpo_first_name;
                $data['to_email']=$arrDtls->propertyOwner->p_email_address;
                $data['subject']='TAX DELINQUENCY AND OUTSTANDING NOTICE';
                try {
                     Mail::send([], ['data' =>$data], function ($m) use ($data) {
                    $m->to($data['to_email'], $data['to_name']);
                    $m->subject($data['subject']);
                    $m->setBody($data['message'], 'text/html');
                });
                 } catch (\Exception $e) {
                    dd($e);
                     
                 } 
            }
        }
    }
    public function generateHtmlOrPdf($data,$year,$displayType){
        if($displayType=="pdf"){
            $this->generatePdfFile($bus_id,$html,$data,$displayType);
        }elseif($displayType=='deliqnuencyOutstanding'){
            return $this->generateViewForDeliquency($data);
        }elseif($displayType=='assessmentEmail'){
            $data['isShowBtn']=0;
            $data['username']=$arrBussDtls->rpo_custom_last_name;
            $Finalhtml = view('mails.taxOrderPaymentEmail',compact('html','data','displayType'));
            return $Finalhtml;
        }
        // ************* End Display Content Details ************************
    }

    public function generateViewForDeliquency($data = '',$displayType = 'email'){
        //dd();
        $customerName = "CASE 
                 WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                 WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END";   

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
                            )
                           ->join('rpt_properties AS rp', 'card.rp_code', '=', 'rp.id')
                           ->leftJoin('cto_top_transactions as ctt', 'ctt.id', '=', 'card.top_transaction_id')
                           ->leftJoin('cto_cashier as cc', 'cc.id', '=', 'card.cashier_id')
                           ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'card.pk_id')
                           ->join('barangays AS bgy', 'bgy.id', '=', 'card.brgy_code_id')
                           ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                           ->where('card.ar_id',$data->receiveAbleID)
                           ->groupBy('card.ar_covered_year')
                           ->groupBy('card.rp_code')
                           ->orderBy('rp.rp_td_no','asc')
                           ->orderBy('card.ar_covered_year')
                           ->get();
        return view('mails.realPropertyAccountReceiables',compact('data','displayType','receiableDetails'))->render();
    }

    public function show(Request $request){   
        $id = $request->id;

        $receiableDetails = DB::table('cto_accounts_receivables')
                           ->select('rp_code')
                           ->where('id',$id)
                           ->first();  
        $propDetails = RptProperty::where('id',(isset($receiableDetails->rp_code))?$receiableDetails->rp_code:'')->first();  
        
        return view('accountrreceivablesproperty.show',compact('propDetails','id'));
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
                                )
                               ->join('rpt_properties AS rp', 'card.rp_code', '=', 'rp.id')
                               ->leftJoin('cto_top_transactions as ctt', 'ctt.id', '=', 'card.top_transaction_id')
                               ->leftJoin('cto_cashier as cc', 'cc.id', '=', 'card.cashier_id')
                               ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'card.pk_id')
                               ->join('barangays AS bgy', 'bgy.id', '=', 'card.brgy_code_id')
                               ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                               ->where('card.ar_id',$data->receiveAbleID)
                               ->groupBy('card.ar_covered_year')
                               ->groupBy('card.rp_code')
                               ->orderBy('rp.rp_td_no','asc')
                               ->orderBy('card.ar_covered_year')
                               ->get();

            $documentFileName = $request->prop_id . "-AccountReceiables.pdf";
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
            $html = view('mails.realPropertyAccountReceiablesPDF', compact('data', 'receiableDetails'))->render();
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
    public function store(Request $request){
        $data = (object)$this->data;
       if($request->input('id')>0 && $request->input('submit')==""){
            $data = BfpOccupancyType::find($request->input('id'));
            
        }
        
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
             if($image = $request->file('bot_occupancy_pdf')) {
             $destinationPath =  public_path().'/uploads/bfpocuupancy/';
                if(!File::exists($destinationPath)) { 
                    File::makeDirectory($destinationPath, 0755, true, true);
                }
             $filename =  $this->data['bot_occupancy_type'];  
             $filename = str_replace(" ", "", $filename);   
             $occupancypdf = $filename. "." . $image->extension();
             $image->move($destinationPath, $occupancypdf);
             $this->data['bot_occupancy_pdf'] = $occupancypdf;
             // echo $profileImage;
             }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_bfpoccupancytype->updateData($request->input('id'),$this->data);
                $success_msg = 'BFP Occupancy Type updated successfully.';
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['is_active'] = 1;
               
                $this->_bfpoccupancytype->addData($this->data);
                $success_msg = 'BFP Occupancy Type added successfully.';
            }
            return redirect()->route('bfpoccupancytype.index')->with('success', __($success_msg));
        }
        return view('bfpoccupancytype.create',compact('data'));
        
    }

    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'bot_occupancy_type' => 'required|unique:bfp_occupancy_types,bot_occupancy_type,' .$request->input('id'),
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

   
    public function Delete(Request $request){
        $id = $request->input('id');
            $BfpOccupancyType = BfpOccupancyType::find($id);
            if($BfpOccupancyType->created_by == \Auth::user()->creatorId()){
                $BfpOccupancyType->delete();
            }
    }



    public function collectDataForNonMaturedRates($prop,$taxRevenueYear,$year){
        $propObj   = new RptProperty;
        $dataToSend = [];
        $qtrFirst   = [];
        $qurSecond  = [];
        $qurThird   = [];
        $qtrForth   = [];
        for ($i=1; $i <= date("n"); $i++) { 
            $month = $i;
            $sdMode = $this->findSdMode($i);
            $data = $propObj->calculatePenaltyDiscMonthly($prop->rp_code,$year,$i,$sdMode);
            if($sdMode == 11){
                $qtrFirst[] = $data;
            }if($sdMode == 22){
                $qurSecond[] = $data;
            }if($sdMode == 33){
                $qurThird[] = $data;
            }if($sdMode == 44){
                $qtrForth[] = $data;
            }
            
        }
        $dataToSend['11'] = (!empty($qtrFirst))?$qtrFirst[count($qtrFirst)-1]:[];
        $dataToSend['22'] = (!empty($qurSecond))?$qurSecond[count($qurSecond)-1]:[];
        $dataToSend['33'] = (!empty($qurThird))?$qurThird[count($qurThird)-1]:[];
        $dataToSend['44'] = (!empty($qtrForth))?$qtrForth[count($qtrForth)-1]:[];
        return $dataToSend;
    }

    public function arrangeDataForChildTable($prop,$taxDetails,$taxRevenueYear,$mode){
        $revenueCodeDetails = $this->_ctobilling->getRevenueCodeDetails($taxRevenueYear,$taxRevenueYear,$prop->pk_id);
        $receiableDetailsSetupBas = $this->_accountreceivables->getSetupDetails($prop->pk_id,1);
        $receiableDetailsSetupsef = $this->_accountreceivables->getSetupDetails($prop->pk_id,2);
        $receiableDetailsSetupsht = $this->_accountreceivables->getSetupDetails($prop->pk_id,3);
        $data = [
            'ar_id' => $prop->arcId,
            'top_transaction_id' => $prop->top_transaction_id,
            'payee_type' => $prop->payee_type,
            'taxpayer_id' => $prop->taxpayer_id,
            'pcs_id' => $prop->pcs_id,
            'rp_property_code' => $prop->rp_property_code,
            'rp_code' => $prop->rp_code,
            'pk_id' => $prop->pk_id,
            'ar_covered_year' => (isset($taxDetails['coveredYear']))?$taxDetails['coveredYear']:'',
            'sd_mode' => $mode,
            'rp_app_effective_year' => $prop->rp_app_effective_year,
            'rp_assessed_value' => $prop->rp_assessed_value,
            'rvy_revision_year_id' => $prop->rvy_revision_year_id,
            'brgy_code_id' => $prop->brgy_code_id,
            'trevs_id' => $taxRevenueYear,
            'penalty_rate' => (isset($taxDetails['penalityRate']))?$taxDetails['penalityRate']:'',
            'tax_revenue_year' => $taxRevenueYear,
            'basic_tfoc_id' => (isset($revenueCodeDetails->basic_tfoc_id))?$revenueCodeDetails->basic_tfoc_id:0,
            'basic_gl_id' => (isset($revenueCodeDetails->basic_gl_id))?$revenueCodeDetails->basic_gl_id:0,
            'basic_sl_id' => (isset($revenueCodeDetails->basic_sl_id))?$revenueCodeDetails->basic_sl_id:0,
            'basic_ar_gl_id' => (isset($receiableDetailsSetupBas->gl_id))?$receiableDetailsSetupBas->gl_id:0,
            'basic_ar_sl_id' => (isset($receiableDetailsSetupBas->sl_id))?$receiableDetailsSetupBas->sl_id:0,
            'basic_amount' => (isset($taxDetails['basicAmount']))?$taxDetails['basicAmount']:'',
            'basic_discount_tfoc_id' => (isset($revenueCodeDetails->basic_discount_tfoc_id))?$revenueCodeDetails->basic_discount_tfoc_id:0,
            'basic_discount_gl_id' => (isset($revenueCodeDetails->basic_d_gl_id))?$revenueCodeDetails->basic_d_gl_id:0,
            'basic_discount_sl_id' => (isset($revenueCodeDetails->basic_d_sl_id))?$revenueCodeDetails->basic_d_sl_id:0,
            'basic_discount_amount' => (isset($taxDetails['basicDisc']))?$taxDetails['basicDisc']:'',
            'basic_penalty_tfoc_id' => (isset($revenueCodeDetails->basic_penalty_tfoc_id))?$revenueCodeDetails->basic_penalty_tfoc_id:0,
            'basic_penalty_gl_id' => (isset($revenueCodeDetails->basic_p_gl_id))?$revenueCodeDetails->basic_p_gl_id:0,
            'basic_penalty_sl_id' => (isset($revenueCodeDetails->basic_p_sl_id))?$revenueCodeDetails->basic_p_sl_id:0,
            'basic_penalty_amount' => (isset($taxDetails['basicPenalty']))?$taxDetails['basicPenalty']:'',
            'sef_amount' =>(isset($taxDetails['basicSefAmount']))?$taxDetails['basicSefAmount']:'',
            'sef_tfoc_id' => (isset($revenueCodeDetails->sef_tfoc_id))?$revenueCodeDetails->sef_tfoc_id:0,
            'sef_gl_id' => (isset($revenueCodeDetails->sef_gl_id))?$revenueCodeDetails->sef_gl_id:0,
            'sef_sl_id' => (isset($revenueCodeDetails->sef_sl_id))?$revenueCodeDetails->sef_sl_id:0,
            'sef_ar_gl_id' => (isset($receiableDetailsSetupsef->gl_id))?$receiableDetailsSetupsef->gl_id:0,
            'sef_ar_sl_id' => (isset($receiableDetailsSetupsef->sl_id))?$receiableDetailsSetupsef->sl_id:0,
            'sef_discount_tfoc_id' => (isset($revenueCodeDetails->sef_discount_tfoc_id))?$revenueCodeDetails->sef_discount_tfoc_id:0,
            'sef_discount_gl_id' => (isset($revenueCodeDetails->sef_d_gl_id))?$revenueCodeDetails->sef_d_gl_id:0,
            'sef_discount_sl_id' => (isset($revenueCodeDetails->sef_d_sl_id))?$revenueCodeDetails->sef_d_sl_id:0,
            'sef_discount_amount' => (isset($taxDetails['sefDisc']))?$taxDetails['sefDisc']:'',
            'sef_penalty_tfoc_id' => (isset($revenueCodeDetails->sef_penalty_tfoc_id))?$revenueCodeDetails->sef_penalty_tfoc_id:0,
            'sef_penalty_gl_id' => (isset($revenueCodeDetails->sef_p_gl_id))?$revenueCodeDetails->sef_p_gl_id:0,
            'sef_penalty_sl_id' => (isset($revenueCodeDetails->sef_p_sl_id))?$revenueCodeDetails->sef_p_sl_id:0,
            'sef_penalty_amount' => (isset($taxDetails['sefPenalty']))?$taxDetails['sefPenalty']:'',
            'sh_amount' => (isset($taxDetails['basicShAMount']))?$taxDetails['basicShAMount']:'',
            'sh_tfoc_id' => (isset($revenueCodeDetails->sh_tfoc_id))?$revenueCodeDetails->sh_tfoc_id:0,
            'sh_gl_id' => (isset($revenueCodeDetails->sh_gl_id))?$revenueCodeDetails->sh_gl_id:0,
            'sh_sl_id' => (isset($revenueCodeDetails->sh_sl_id))?$revenueCodeDetails->sh_sl_id:0,
            'sh_ar_gl_id' => (isset($receiableDetailsSetupsht->gl_id))?$receiableDetailsSetupsht->gl_id:0,
            'sh_ar_sl_id' => (isset($receiableDetailsSetupsht->sl_id))?$receiableDetailsSetupsht->sl_id:0,
            'sh_discount_tfoc_id' => (isset($revenueCodeDetails->sh_discount_tfoc_id))?$revenueCodeDetails->sh_discount_tfoc_id:0,
            'sh_discount_gl_id' => (isset($revenueCodeDetails->sh_d_gl_id))?$revenueCodeDetails->sh_d_gl_id:0,
            'sh_discount_sl_id' => (isset($revenueCodeDetails->sh_d_sl_id))?$revenueCodeDetails->sh_d_sl_id:0,
            'sh_discount_amount' => (isset($taxDetails['shDisc']))?$taxDetails['shDisc']:'',
            'sh_penalty_tfoc_id' => (isset($revenueCodeDetails->sh_penalty_tfoc_id))?$revenueCodeDetails->sh_penalty_tfoc_id:0,
            'sh_penalty_gl_id' => (isset($revenueCodeDetails->sh_p_gl_id))?$revenueCodeDetails->sh_p_gl_id:0,
            'sh_penalty_sl_id' => (isset($revenueCodeDetails->sh_p_sl_id))?$revenueCodeDetails->sh_p_sl_id:0,
            'sh_penalty_amount' => (isset($taxDetails['shPenalty']))?$taxDetails['shPenalty']:'',
            'cashier_id' => $prop->rp_last_cashier_id,
            'last_updated' => date("Y-m-d",strtotime($taxDetails['coveredYear'].'-01-01')),
            'status' => 1,
            'cbd_is_paid' => $taxDetails['cbd_is_paid'],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'created_by' => $prop->created_by,
            'updated_by' => $prop->created_by
        ];
        return $data;
    }

    public function checkForPreviousOwner($prop = ''){
        $previousOwners = DB::table('rpt_properties')
                          ->where('rp_property_code',$prop->rp_property_code)
                          ->where('pk_is_active',9)
                          /*->whereNotIn('id',DB::table('cto_accounts_receivable_details')->pluck('rp_code'))
                          ->whereNotIn('rp_app_effective_year',DB::table('cto_accounts_receivable_details')->pluck('ar_covered_year'))*/
                          ->pluck('rp_app_effective_year');
        return $previousOwners;
    }

    public function addUpdateDataReceivables($value=''){
        $offset = DB::table('cto_accounts_receivables')->where('cron_job_last_update',1)->select('id')->first();
        DB::table('cto_accounts_receivables')->update(['cron_job_last_update'=>0]);
        $query =  DB::table('cto_accounts_receivables as acr')
                          ->select('rp.rp_app_effective_year','rp.id','acr.id as arcId','acr.last_updated','acr.top_transaction_id',
                            'acr.payee_type','acr.taxpayer_id','acr.pcs_id','acr.rp_property_code','acr.rp_code','acr.pk_id','acr.rvy_revision_year_id','acr.brgy_code_id','acr.rp_assessed_value','acr.rp_last_cashier_id','acr.rp_code_chain','acr.created_by','acr.updated_by',
                            DB::raw('COALESCE(acr.outstand_basic_amount,0) as outstand_basic_amount'),
                            DB::raw('COALESCE(acr.outstand_basic_interest,0) as outstand_basic_interest'),
                            DB::raw('COALESCE(acr.outstand_basic_discount,0) as outstand_basic_discount'),
                            DB::raw('COALESCE(acr.outstand_sef_amount,0) as outstand_sef_amount'),
                            DB::raw('COALESCE(acr.outstand_sef_interest,0) as outstand_sef_interest'),
                            DB::raw('COALESCE(acr.outstand_sef_discount,0) as outstand_sef_discount'),
                            DB::raw('COALESCE(acr.outstand_sht_amount,0) as outstand_sht_amount'),
                            DB::raw('COALESCE(acr.outstand_sht_interest,0) as outstand_sht_interest'),
                            DB::raw('COALESCE(acr.outstand_sht_discount,0) as outstand_sht_discount'),

                            /*DB::raw('COALESCE(acr.delinq_basic_amount,0) as delinq_basic_amount'),*/
                            DB::raw('COALESCE(acr.delinq_basic_amount,0) as delinq_basic_amount'),
                            DB::raw('COALESCE(acr.delinq_basic_interest,0) as delinq_basic_interest'),
                            DB::raw('COALESCE(acr.delinq_basic_discount,0) as delinq_basic_discount'),
                            DB::raw('COALESCE(acr.delinq_sef_amount,0) as delinq_sef_amount'),
                            DB::raw('COALESCE(acr.delinq_sef_interest,0) as delinq_sef_interest'),
                            DB::raw('COALESCE(acr.delinq_sef_discount,0) as delinq_sef_discount'),
                            DB::raw('COALESCE(acr.delinq_sht_amount,0) as delinq_sht_amount'),
                            DB::raw('COALESCE(acr.delinq_sht_interest,0) as delinq_sht_interest'),
                            DB::raw('COALESCE(acr.delinq_sht_discount,0) as delinq_sht_discount'),
                        )
                          ->join('rpt_properties as rp','rp.id','=','acr.rp_code')
                          ->where('pcs_id',2)
                          ->where('acr.is_active',1)
                          ->orderBy('acr.id','DESC')
                          ->limit(50);
                          if($offset != null){
                            $query->where('acr.id','<',$offset->id);
                          }
                          $receiables = $newReceiveables = $query->get();    
                          foreach ($receiables as $key => $prop) {
                            DB::table('cto_accounts_receivables')->where('id',$prop->arcId)->update(['cron_job_last_update'=>1]);
                            $cloneProp = (array)$prop;
                            $allEffectiveYears = $this->checkForPreviousOwner($prop);
                            
                            if(!$allEffectiveYears->isEmpty()){
                                $yearOfBilling = $allEffectiveYears->min();
                            }else{
                                if($prop->last_updated == null){
                                        $yearOfBilling = $prop->rp_app_effective_year;
                                    }else{
                                        $yearOfBilling = date("Y",strtotime($prop->last_updated));
                                    }
                            } 

                              $billObj   = new RptCtoBilling;
                              $propObj   = new RptProperty;
                              
                              
                              for ($i = $yearOfBilling; $i <= date("Y"); $i++) {

                            $newPropObj = $billObj->getGrAndPreviousOwnerData($prop->rp_property_code,$prop->id)->where('rp_app_effective_year','<=',$i)->last();  
                           if($newPropObj != null){
                            $cloneProp['rp_assessed_value']     =    $newPropObj->rp_assessed_value;      
                            $cloneProp['rp_app_effective_year'] = $newPropObj->rp_app_effective_year;
                            $cloneProp['rp_code'] = $newPropObj->id;
                            $cloneProp['id'] = $newPropObj->id;
                            $cloneProp['taxpayer_id'] = $newPropObj->rpo_code;
                            $cloneProp['pk_is_active'] = $newPropObj->pk_is_active;
                            $cloneProp['created_against'] = $newPropObj->created_against;
                        }
                            $getPenalityRateDate = $billObj->getPenalityRateData($i);
                              /* For Mutured Penalty Rates */
                             $alreadyPaidYearsObj = DB::table('rpt_cto_billing_details as cbd')
                                                   ->join('rpt_cto_billings as cb',function($j){
                                                      $j->on('cb.id','=','cbd.cb_code')->where('cb.cb_is_paid',1);
                                                   })
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
                                                   ->where('cbd.rp_property_code',$cloneProp['rp_property_code'])
                                                   ->where('cbd.cbd_covered_year',$i);
                                                   
                              $alreadyPaidYears = $alreadyPaidYearsObj->pluck('cbd.sd_mode')->toArray();
                              $penaltyRateData  = $alreadyPaidYearsObj->select(
                                DB::raw('COALESCE(cbdp.basic_penalty_rate,0) as basic_penalty_rate'),
                                DB::raw('COALESCE(cbdd.basic_discount_rate,0) as basic_discount_rate')
                            )->first();
                              
                              $allModes = Helper::billing_quarters();
                              $sdModes = array_keys($allModes);
                              //dd($sdModes);
                              if(isset($getPenalityRateDate->cps_penalty_limitation) && $getPenalityRateDate->cps_penalty_limitation == 1){
                                foreach ($sdModes as $mode) {
                                    $dataToUpdateInMain = [
                                    'last_updated' => date("Y-m-d",strtotime($i.'-01-01'))
                                    ];  
                                    if(!empty($alreadyPaidYears) && (in_array($mode, $alreadyPaidYears) || $alreadyPaidYears[0] == 14)){
                                            $cbdPaidStatus = 1;
                                        }else{
                                            $cbdPaidStatus = 0;
                                        }
                                    $penaltyRate = (isset($getPenalityRateDate->cps_maximum_penalty))?$getPenalityRateDate->cps_maximum_penalty:0;
                                    /* Exception if customer made payments in qtrs */
                                    $exeData = $billObj->checkExceptionForCurrentYear($cloneProp['rp_property_code']);
                                      if(isset($exeData['status']) && $exeData['status'] == true){
                                        if($i == $exeData['lastYear']){
                                            $penaltyRate = (isset($exeData['penaltyRate']))?$exeData['penaltyRate']:$penaltyRate;
                                       }
                                      }
                                    /* Exception if customer made payments in qtrs */
                                    $taxDetails = $propObj->calculatePenaltyFee($cloneProp['id'],$i,$mode,$penaltyRate,true);
                                    
                                    
                                    //dd($i);
                                    $taxDetails['coveredYear'] = $i;
                                    $taxDetails['cbd_is_paid'] = 0;
                                    /* Data In Child Table */
                                    $dataToSaveInDetails = $this->arrangeDataForChildTable((object)$cloneProp,$taxDetails,3,$mode);
                                    $checkRecordAlreadyExist = DB::table('cto_accounts_receivable_details')
                                                                  ->where('sd_mode',$mode)
                                                                  ->where('rp_property_code',$cloneProp['rp_property_code'])
                                                                  ->where('ar_covered_year',$i)->first();
                                    /* Data In Child Table */
                                    DB::beginTransaction();
                                    try {
                                        if($checkRecordAlreadyExist == null && $cbdPaidStatus == 0){
                                        $this->_accountreceivables->updateData($cloneProp['arcId'],$dataToUpdateInMain);
                                        $this->_accountreceivables->addDataInDetails($dataToSaveInDetails);
                                    }else{
                                        if($cbdPaidStatus == 0){
                                            $this->_accountreceivables->updateDataInDetails($checkRecordAlreadyExist->id,$dataToSaveInDetails);
                                        }
                                    }
                                        $this->updateDeliquency((object)$cloneProp);
                                        DB::commit();
                                        
                                    } catch (\Exception $e) {
                                        DB::rollback();
                                        dd($e);
                                    }
                                }

                              }else{
                                /* For Non Mutured Penalty Rates */
                                foreach ($sdModes as $mode) {
                                    $moth = date("n");
                                    $getPenalityRateDate = DB::table('rpt_cto_penalty_tables')->where('cpt_effective_year',$i)->where('cpt_current_year',date("Y
                                        "))->first();
                                        if($getPenalityRateDate != null){
                                            $monthProp = 'cpt_month_'.$moth;
                                            $penalityRate        = $getPenalityRateDate->$monthProp;
                                     }
                                    
                                     if(!empty($alreadyPaidYears) && (in_array($mode, $alreadyPaidYears) || $alreadyPaidYears[0] == 14)){
                                            $cbdPaidStatus = 1;
                                        }else{
                                            $cbdPaidStatus = 0;
                                        }
                                    $penaltyRate = (isset($penalityRate))?$penalityRate:0;   
                                    /* Exception if customer made payments in qtrs */
                                    $exeData = $billObj->checkExceptionForCurrentYear($cloneProp['rp_property_code']);
                                      if(isset($exeData['status']) && $exeData['status'] == true){
                                        if($i == $exeData['lastYear']){
                                        $penaltyRate = (isset($exeData['penaltyRate']))?$exeData['penaltyRate']:$penaltyRate;
                                    }
                                      }
                                    /* Exception if customer made payments in qtrs */ 
                                    $taxDetails = $propObj->calculatePenaltyFee($cloneProp['id'],$i,$mode,$penaltyRate,true);
                                    $taxDetails['coveredYear'] = $i;
                                    $taxDetails['cbd_is_paid'] = 0;
                        
                                    /* Data In Child Table */
                                    $dataToSaveInDetails = $this->arrangeDataForChildTable((object)$cloneProp,$taxDetails,($i < date("Y"))?3:2,$mode);
                                    $checkRecordAlreadyExist = DB::table('cto_accounts_receivable_details')
                                                                  ->where('sd_mode',$mode)
                                                                  ->where('rp_property_code',$cloneProp['rp_property_code'])
                                                                  ->where('ar_covered_year',$i)
                                                                  ->first();
                                    $checkPreviousYearOut = DB::table('cto_accounts_receivable_details')
                                                                  ->where('sd_mode',$mode)
                                                                  ->where('rp_property_code',$cloneProp['rp_property_code'])
                                                                  ->where('ar_covered_year',$i-1)
                                                                  ->where('tax_revenue_year',2)
                                                                  ->where('cbd_is_paid',0)
                                                                  ->first();
                                                                  if($i == '2024' && $cloneProp['rp_property_code'] == 12){
                                                                    //dd($checkPreviousYearOut);
                                                                  };                        
                                    /* Data In Child Table */
                                    DB::beginTransaction();
                                    try {
                                            if(!$cbdPaidStatus){
                                            if($checkPreviousYearOut != null && $cbdPaidStatus == 0){
                                              $this->_accountreceivables->updateDataInDetails($checkPreviousYearOut->id,$dataToSaveInDetails);
                                            }else{
                                                if($checkRecordAlreadyExist == null && $cbdPaidStatus == 0){
                                                  $this->_accountreceivables->addDataInDetails($dataToSaveInDetails);
                                                }else{
                                                    $this->_accountreceivables->updateDataInDetails($checkRecordAlreadyExist->id,$dataToSaveInDetails);
                                                }
                                            }
                                        }
                                            
                                        
                                        $this->updateDeliquency((object)$cloneProp);
                                        DB::commit();
                                        
                                    } catch (\Exception $e) {
                                        DB::rollback();
                                        dd($e);
                                    }
                                }
                                //dd($dataToUpdateOrAdd); 
                              }            
                            }
        
    }
    }

    public function updateDeliquency($prop = ''){
        //dd($prop);
        /* Update With Deliquency */
          $chain = json_decode($prop->rp_code_chain);
          $checkDetailsData = DB::table('cto_accounts_receivable_details')
                                      ->select(
                                        DB::raw('SUM(COALESCE(basic_amount,0)) as basic_amount'),
                                        DB::raw('SUM(COALESCE(basic_discount_amount,0)) as basic_discount_amount'),
                                        DB::raw('SUM(COALESCE(basic_penalty_amount,0)) as basic_penalty_amount'),
                                        DB::raw('SUM(COALESCE(sef_amount,0)) as sef_amount'),
                                        DB::raw('SUM(COALESCE(sef_discount_amount,0)) as sef_discount_amount'),
                                        DB::raw('SUM(COALESCE(sef_penalty_amount,0)) as sef_penalty_amount'),
                                        DB::raw('SUM(COALESCE(sh_amount,0)) as sh_amount'),
                                        DB::raw('SUM(COALESCE(sh_discount_amount,0)) as sh_discount_amount'),
                                        DB::raw('SUM(COALESCE(sh_penalty_amount,0)) as sh_penalty_amount'),
                                      )
                                      //->where('sd_mode',$this->findSdMode(date("n")))
                                      ->where('rp_property_code',(isset($prop->rp_property_code))?$prop->rp_property_code:'')
                                      ->whereIn('rp_code',$chain)
                                      ->whereIn('tax_revenue_year',[3])
                                      ->where('cbd_is_paid',0)
                                      ->first();
                                      //dd($checkDetailsData);
         $outstandDetails = DB::table('cto_accounts_receivable_details')
                                      ->select(
                                        DB::raw('SUM(COALESCE(basic_amount,0)) as basic_amount'),
                                        DB::raw('SUM(COALESCE(basic_discount_amount,0)) as basic_discount_amount'),
                                        DB::raw('SUM(COALESCE(basic_penalty_amount,0)) as basic_penalty_amount'),
                                        DB::raw('SUM(COALESCE(sef_amount,0)) as sef_amount'),
                                        DB::raw('SUM(COALESCE(sef_discount_amount,0)) as sef_discount_amount'),
                                        DB::raw('SUM(COALESCE(sef_penalty_amount,0)) as sef_penalty_amount'),
                                        DB::raw('SUM(COALESCE(sh_amount,0)) as sh_amount'),
                                        DB::raw('SUM(COALESCE(sh_discount_amount,0)) as sh_discount_amount'),
                                        DB::raw('SUM(COALESCE(sh_penalty_amount,0)) as sh_penalty_amount'),
                                      )
                                      //->where('sd_mode',$this->findSdMode(date("n")))
                                      ->where('ar_covered_year',date("Y"))
                                      ->where('rp_property_code',(isset($prop->rp_property_code))?$prop->rp_property_code:'')
                                      ->where('tax_revenue_year',2)
                                      ->where('cbd_is_paid',0)
                                      ->first();                             
            //dd($checkDetailsData);                        
            $dataToUpdate = [
                'delinq_basic_amount' => (isset($checkDetailsData->basic_amount))?$checkDetailsData->basic_amount:0,
                'delinq_basic_interest' => (isset($checkDetailsData->basic_penalty_amount))?$checkDetailsData->basic_penalty_amount:0,
                'delinq_basic_discount' => (isset($checkDetailsData->basic_discount_amount))?$checkDetailsData->basic_discount_amount:0,
                'delinq_sef_amount' => (isset($checkDetailsData->sef_amount))?$checkDetailsData->sef_amount:0,
                'delinq_sef_interest' => (isset($checkDetailsData->sef_penalty_amount))?$checkDetailsData->sef_penalty_amount:0,
                'delinq_sef_discount' => (isset($checkDetailsData->sef_discount_amount))?$checkDetailsData->sef_discount_amount:0,
                'delinq_sht_amount' =>(isset($checkDetailsData->sh_amount))?$checkDetailsData->sh_amount:0,
                'delinq_sht_interest' => (isset($checkDetailsData->sh_penalty_amount))?$checkDetailsData->sh_penalty_amount:0,
                'delinq_sht_discount' => (isset($checkDetailsData->sh_discount_amount))?$checkDetailsData->sh_discount_amount:0,
                'updated_at' => date("Y-m-d H:i:s")
            ];  
            if(\Auth::check()){
                $dataToUpdate['updated_by'] = \Auth::user()->id;
            }
            if($outstandDetails != null){
                $dataToUpdate['outstand_basic_amount']   =  (isset($outstandDetails->basic_amount))?$outstandDetails->basic_amount:0;
                $dataToUpdate['outstand_basic_interest'] = (isset($outstandDetails->basic_penalty_amount))?$outstandDetails->basic_penalty_amount:0;
                $dataToUpdate['outstand_basic_discount'] =  (isset($outstandDetails->basic_discount_amount))?$outstandDetails->basic_discount_amount:0;
                $dataToUpdate['outstand_sef_amount']     =  (isset($outstandDetails->sef_amount))?$outstandDetails->sef_amount:0;
                $dataToUpdate['outstand_sef_interest']   =  (isset($outstandDetails->sef_penalty_amount))?$outstandDetails->sef_penalty_amount:0;
                $dataToUpdate['outstand_sef_discount']   =  (isset($outstandDetails->sef_discount_amount))?$outstandDetails->sef_discount_amount:0;
                $dataToUpdate['outstand_sht_amount']     =  (isset($outstandDetails->sh_amount))?$outstandDetails->sh_amount:0;
                $dataToUpdate['outstand_sht_interest']   =  (isset($outstandDetails->sh_penalty_amount))?$outstandDetails->sh_penalty_amount:0;
                $dataToUpdate['outstand_sht_discount']   =  (isset($outstandDetails->sh_discount_amount))?$outstandDetails->sh_discount_amount:0;
                /* Add Data in cto_receiveables */
                

                /* Add Data in cto_receiveables */
            }
            $outStanding = (isset($outstandDetails->basic_amount))?$outstandDetails->basic_amount:0;
            $deliquent   = (isset($checkDetailsData->basic_amount))?$checkDetailsData->basic_amount:0;
            $checkForStatus = ($outStanding+$deliquent);
            if($checkForStatus > 0){
                $status = 1;
            }else{
                $status = 0;
            }    
            $dataToUpdate['status'] = $status;
            $this->_accountreceivables->updateData((isset($prop->arcId))?$prop->arcId:'',$dataToUpdate);
            if($outstandDetails != null){
            $this->addOrUpdateInCtoReceive($prop->arcId,$prop);    
            }             
                                                                  
                                        /* Update With Deliquency */
    }

    public function addOrUpdateInCtoReceive($receiableId = '',$prop){
        //dd($prop);
        $receiveable = DB::table('cto_accounts_receivables as car')
                           ->select('car.*',DB::raw('SUM(COALESCE(cbd.basic_amount,0)) as basicAmount'),DB::raw('SUM(COALESCE(cbd.sef_amount,0)) as sefAmount'),DB::raw('SUM(COALESCE(cbd.sh_amount,0)) as shAmount'),'cc.cashier_or_date','cc.or_no','top.transaction_no','rp.rpo_code','c.full_name','rp.rp_tax_declaration_no','car.created_by','car.updated_by','cbd.cb_code')
                           ->join('rpt_properties as rp','rp.id','=','car.rp_code')
                           ->join('clients as c','c.id','=','rp.rpo_code')
                           ->leftJoin('cto_cashier as cc','cc.id','=','car.rp_last_cashier_id')
                           ->leftJoin('cto_top_transactions as top','top.id','=','car.top_transaction_id')
                           ->leftJoin('cto_cashier_details as ccd',function($j){
                            $j->on('ccd.cashier_id','=','car.rp_last_cashier_id')
                            ->join('rpt_cto_billing_details as cbd',function($newJ){
                                $newJ->on('cbd.id','=','ccd.cbd_code')->where('cbd.cbd_covered_year',date("Y"));
                            });
                           })
                           ->where('car.id',$receiableId)
                           ->first();
        $allModesData = DB::table('schedule_descriptions')->select('id','sd_mode')->where('is_active',1)->get();
        $sdModeId = $allModesData->where('sd_mode','14')->first();
        $dueDateData = $this->_ctobilling->getPaymentScheduledData(date("Y"),$sdModeId->id);           
        $receiableDetailsSetupBas = $this->_accountreceivables->getSetupDetails($receiveable->pk_id,1);
        $receiableDetailsSetupsef = $this->_accountreceivables->getSetupDetails($receiveable->pk_id,2);
        $receiableDetailsSetupsht = $this->_accountreceivables->getSetupDetails($receiveable->pk_id,3);
        $kindText = ($receiveable->pk_id == 1)?'Building':(($receiveable->pk_id == 2)?'Land':'Machine');
        $propObj = new RptProperty;
        $allDues = $propObj->getBasicRatesData($prop->id);
        $shtMax      = (isset($allDues->assessed_value_max_amount) && $allDues->assessed_value_max_amount > 0)?$allDues->assessed_value_max_amount:0;
        $asseValue   = (isset($prop->rp_assessed_value))?$prop->rp_assessed_value:0;
        $sefAmount    = ($allDues->has_tax_sef == 1)?$allDues->sef_due:0;
        $shAmount     = ($asseValue > $shtMax)?(($allDues->has_tax_sh == 1)?$allDues->sh_due:0):0;
        if($receiableDetailsSetupBas != null){
            $basicDataToSave = [
                'category' => 'rpt',
                'covered_year' => date("Y"),
                'taxpayer_id' => $receiveable->rpo_code, 
                'pk_id' => $receiveable->pk_id,
                'taxpayer_name' => $receiveable->full_name,
                'ars_category' => 1,
                'top_transaction_type_id' => 3,
                'description' => 'RPT:'.$kindText.' TD No. '.$receiveable->rp_tax_declaration_no,
                'amount_type' => 'annual',
                'pcs_id' => 2,
                'application_id' => $receiveable->rp_code,
                'fund_code_id' => $receiableDetailsSetupBas->ars_fund_id,
                'gl_account_id' => $receiableDetailsSetupBas->gl_id,
                'sl_account_id' => $receiableDetailsSetupBas->sl_id,
                'assessed_value' => (isset($prop->rp_assessed_value))?$prop->rp_assessed_value:0,
                'amount_due' => (isset($allDues->basic_due))?$allDues->basic_due:0,
                'amount_pay' => 0,
                'remaining_amount' => (isset($allDues->basic_due))?$allDues->basic_due:0,
                'due_date' => (isset($dueDateData->rcpsched_penalty_due_date))?$dueDateData->rcpsched_penalty_due_date:'',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'created_by' => $receiveable->created_by,
                'updated_by' => $receiveable->updated_by,
            ];
            if($receiveable->rp_code == 61 && $receiveable->ar_year == '2023'){
                                 //dd($receiveable);
                             }
            $checkAlreadyExists = DB::table('cto_receivables')->where('covered_year',date("Y"))->where('application_id',$receiveable->rp_code)->where('category','rpt')->where('ars_category',1)->first();
            if($checkAlreadyExists == null){
                DB::table('cto_receivables')->insert($basicDataToSave);
            }else{
                $remainingAmount = $checkAlreadyExists->amount_due-$receiveable->basicAmount;
                $remainingAmount = number_format((float)$remainingAmount, 2, '.', '');
                $basicDataToUpdate = [
                    'amount_pay' => $receiveable->basicAmount,
                    'cashier_id' => ($receiveable->cb_code != null)?$receiveable->rp_last_cashier_id:'',
                    'or_no' => ($receiveable->cb_code != null)?$receiveable->or_no:'',
                    'or_date' => ($receiveable->cb_code != null)?$receiveable->cashier_or_date:'',
                    'is_paid' => ($remainingAmount == 0 && $receiveable->cb_code != null)?1:0,
                    'remaining_amount' => $remainingAmount,
                    'top_no' => ($receiveable->cb_code != null)?$receiveable->transaction_no:'',
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $receiveable->updated_by,
                ];
                DB::table('cto_receivables')->where('id',$checkAlreadyExists->id)->update($basicDataToUpdate);
            }
        }

        if($receiableDetailsSetupsef != null){

            $sefDataToSave = [
                'category' => 'rpt',
                'covered_year' => date("Y"),
                'taxpayer_id' => $receiveable->rpo_code, 
                'pk_id' => $receiveable->pk_id,
                'taxpayer_name' => $receiveable->full_name,
                'ars_category' => 2,
                'top_transaction_type_id' => 3,
                'description' => 'RPT:'.$kindText.' TD No. '.$receiveable->rp_tax_declaration_no,
                'amount_type' => 'annual',
                'pcs_id' => 2,
                'application_id' => $receiveable->rp_code,
                'fund_code_id' => $receiableDetailsSetupsef->ars_fund_id,
                'gl_account_id' => $receiableDetailsSetupsef->gl_id,
                'sl_account_id' => $receiableDetailsSetupsef->sl_id,
                'assessed_value' => (isset($prop->rp_assessed_value))?$prop->rp_assessed_value:0,
                'amount_due' => (isset($sefAmount))?$sefAmount:0,
                'amount_pay' => 0,
                'remaining_amount' => $sefAmount,
                'due_date' => (isset($dueDateData->rcpsched_penalty_due_date))?$dueDateData->rcpsched_penalty_due_date:'',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'created_by' => $receiveable->created_by,
                'updated_by' => $receiveable->updated_by,
            ];
            $checkAlreadyExists = DB::table('cto_receivables')->where('covered_year',date("Y"))->where('application_id',$receiveable->rp_code)->where('category','rpt')->where('ars_category',2)->first();
            if($checkAlreadyExists == null){
                DB::table('cto_receivables')->insert($sefDataToSave);
            }else{
                $remainingAmount = $checkAlreadyExists->amount_due-$receiveable->sefAmount;
                $remainingAmount = number_format((float)$remainingAmount, 2, '.', '');
                $sefDataToUpdate = [
                    'amount_pay' => $receiveable->sefAmount,
                    'cashier_id' => ($receiveable->cb_code != null)?$receiveable->rp_last_cashier_id:'',
                    'or_no' => ($receiveable->cb_code != null)?$receiveable->or_no:'',
                    'or_date' => ($receiveable->cb_code != null)?$receiveable->cashier_or_date:'',
                    'is_paid' => ($remainingAmount == 0 && $receiveable->cb_code != null)?1:0,
                    'remaining_amount' => $remainingAmount,
                    'top_no' => ($receiveable->cb_code != null)?$receiveable->transaction_no:'',
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $receiveable->updated_by,
                ];
                
                    DB::table('cto_receivables')->where('id',$checkAlreadyExists->id)->update($sefDataToUpdate);
                
                
            }
        }

        if($receiableDetailsSetupsht != null){
            $shDataToSave = [
                'category' => 'rpt',
                'covered_year' => date("Y"),
                'taxpayer_id' => $receiveable->rpo_code, 
                'pk_id' => $receiveable->pk_id,
                'taxpayer_name' => $receiveable->full_name,
                'ars_category' => 3,
                'top_transaction_type_id' => 3,
                'description' => 'RPT:'.$kindText.' TD No. '.$receiveable->rp_tax_declaration_no,
                'amount_type' => 'annual',
                'pcs_id' => 2,
                'application_id' => $receiveable->rp_code,
                'fund_code_id' => $receiableDetailsSetupsht->ars_fund_id,
                'gl_account_id' => $receiableDetailsSetupsht->gl_id,
                'sl_account_id' => $receiableDetailsSetupsht->sl_id,
                'assessed_value' => (isset($prop->rp_assessed_value))?$prop->rp_assessed_value:0,
                'amount_due' => (isset($shAmount))?$shAmount:0,
                'amount_pay' => 0,
                'remaining_amount' => (isset($shAmount))?$shAmount:0,
                'due_date' => (isset($dueDateData->rcpsched_penalty_due_date))?$dueDateData->rcpsched_penalty_due_date:'',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'created_by' => $receiveable->created_by,
                'updated_by' => $receiveable->updated_by,
            ];
            $checkAlreadyExists = DB::table('cto_receivables')->where('covered_year',date("Y"))->where('application_id',$receiveable->rp_code)->where('category','rpt')->where('ars_category',3)->first();
            if($checkAlreadyExists == null){
                if((isset($shAmount)) && $shAmount != 0){
                    DB::table('cto_receivables')->insert($shDataToSave);
                }
                
            }else{
                $remainingAmount = $checkAlreadyExists->amount_due-$receiveable->shAmount;
                $remainingAmount = number_format((float)$remainingAmount, 2, '.', '');
                $shDataToUpdate = [
                    'amount_pay' => $receiveable->shAmount,
                    'cashier_id' => ($receiveable->cb_code != null)?$receiveable->rp_last_cashier_id:'',
                    'or_no' => ($receiveable->cb_code != null)?$receiveable->or_no:'',
                    'or_date' => ($receiveable->cb_code != null)?$receiveable->cashier_or_date:'',
                    'is_paid' => ($remainingAmount == 0 && $receiveable->cb_code != null)?1:0,
                    'remaining_amount' => $remainingAmount,
                    'top_no' => ($receiveable->cb_code != null)?$receiveable->transaction_no:'',
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $receiveable->updated_by,
                ];
              
                DB::table('cto_receivables')->where('id',$checkAlreadyExists->id)->update($shDataToUpdate);
            
            }
        }


        
    }

    public function getSdModesTillCurrentMonth(){
        $dataToSend = [];
        for ($i=1; $i <= date("n"); $i++) { 
            if(!in_array($this->findSdMode($i), $dataToSend)){
                $dataToSend[$this->findSdMode($i)] = $i;
            }
            
        }
        return $dataToSend;
    }

    public function findSdMode($month = ''){
        if(in_array($month,[1,2,3])){
            return 11;
        }if(in_array($month,[4,5,6])){
            return 22;
        }if(in_array($month,[7,8,9])){
            return 33;
        }if(in_array($month,[10,11,12])){
            return 44;
        }
    }
}
