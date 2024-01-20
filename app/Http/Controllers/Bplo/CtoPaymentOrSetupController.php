<?php

namespace App\Http\Controllers\Bplo;
use App\Http\Controllers\Controller;
use App\Rules\UniqueOrFieldFormForUser;
use App\Models\Bplo\CtoPaymentOrSetup;
use App\Rules\UniqueOrTypeForUser;
use App\Models\CommonModelmaster;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Auth;
use PDF;
use DB;

class CtoPaymentOrSetupController extends Controller
{
     public $data = [];
     public $setupData = [];
     public $dataDtls = [];
     public $postdata = [];
     private $slugs;
     public function __construct(){
        $this->_CtoPaymentOrSetup = new CtoPaymentOrSetup(); 
        $this->_commonmodel = new CommonModelmaster();
        $this->data = array('id'=>'', 'user_id'=>'', 'or_field_form'=>'', 'ortype_id'=>'', 'ors_remarks'=>'', 'is_portrait'=>'');
        // $this->setupData = array("ors_date"=>'OR Date',"ors_fund"=>'Fund',"ors_lgu"=>'LGU Name',"ors_agency"=>'Agency Name',"ors_payor"=>'Payor Name',"ors_business"=>'Business Name',"ors_business_idno"=>'Business Identificaton Number',"ors_business_add"=>'Business Address',"ors_breakdown_fees"=>'Breakdown Of Fees',"ors_remarks_taxyear"=>'Remarks & Tax Year',"ors_payment_method"=>'Payment Method',"ors_amount_words"=>'Amount in Words',"ors_total"=>'Total',"ors_collecting_officer"=>'Collecting Officer',"ors_check_details"=>'Check Details',"ors_cash"=>'Cash Details');
        
        $this->dataDtls = array("ors_date"=>array(),"ors_fund"=>array(),"ors_lgu"=>array(),"ors_agency"=>array(),"ors_payor"=>array(),"ors_business"=>array(),"ors_business_idno"=>array(),"ors_business_add"=>array(),"ors_breakdown_fees"=>array(),"ors_remarks_taxyear"=>array(),"ors_payment_method"=>array(),"ors_amount_words"=>array(),"ors_total"=>array(),"ors_collecting_officer"=>array(),"ors_check_details"=>array(),"ors_cash"=>array());

        $this->slugs = 'bplo-or-setup';
    }

    public function index(Request $request)
    {
        $this->is_permitted($this->slugs, 'read');
        return view('Bplo.or_setup.index');
    }

    public function getList(Request $request){
        $this->is_permitted($this->slugs, 'read');
        $data=$this->_CtoPaymentOrSetup->getList($request);
        $arr=array();
        $i="0";    
        $sr_no=(int)$request->input('start')-1; 
        $sr_no=$sr_no>0? $sr_no+1:0;
        foreach ($data['data'] as $row){
            $sr_no=$sr_no+1;
            $actions = '';
            if ($this->is_permitted($this->slugs, 'update', 1) > 0) {
                $actions .= '<div class="action-btn bg-warning ms-2">
                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="'.url('/CtoPaymentOrSetup/store?id='.$row->id).'" data-ajax-popup="true"  data-size="xxll" data-bs-toggle="tooltip" title="Edit"  data-title="Manage Printing Setup">
                        <i class="ti-pencil text-white"></i>
                    </a>
                </div>';
            }
            if ($this->is_permitted($this->slugs, 'delete', 1) > 0) {
                $actions .=($row->ors_is_active == 1) ? '<div class="action-btn bg-danger ms-2"><a href="#" class="mx-3 btn btn-sm activeinactive ti-trash text-white text-white" name="stp_print" value="0" id='.$row->id.'></a>' : 
                    '<div class="action-btn bg-info ms-2"><a href="#" class="mx-3 btn btn-sm  align-items-center activeinactive ti-reload text-white"  name="stp_print" value="1" id='.$row->id.'></a>';  
            }
            $arr[$i]['srno']=$sr_no;
            $arr[$i]['type_name']=$row->or_field_form;
            $arr[$i]['ors_remarks']=$row->ors_remarks;
            $arr[$i]['is_active']=($row->ors_is_active==1?'<span class="btn btn-success" style="padding: 0.1rem 0.5rem !important;">Active</span>':'<span class="btn btn-warning" style="padding: 0.1rem 0.5rem !important;">InActive</span>');
            $arr[$i]['created_at']=date('Y-m-d', strtotime($row->created_at));
            $arr[$i]['updated_at']=date('Y-m-d', strtotime($row->updated_at));
            $arr[$i]['action']=$actions;
           
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
    
    public function saveDetails(Request $request){
        $this->is_permitted($this->slugs, 'update');
        $h_id=$request->input('h_id');
        $getData = $this->_CtoPaymentOrSetup->getEditDetails($request->input('id'));
        if(isset($getData->setup_details)){
            $this->dataDtls = json_decode($getData->setup_details,false);
            $this->dataDtls->$h_id = $request->input('data');
        }else{
            $form_details = $this->_CtoPaymentOrSetup->getFormFieldsDetailsById($request->id);
            $details = [];
            foreach ($form_details as $key => $value) {
                $details[$value->or_field] = [];
            }
            $this->dataDtls = $details;
            $this->dataDtls[$h_id] = $request->input('data');
        }
        $data['setup_details'] = json_encode($this->dataDtls);

        $this->_CtoPaymentOrSetup->updateData($request->input('id'),$data);

        // Log Details
        $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated OR Setup '"; 
        $logDetails['module_id'] =$request->input('id');
        $this->_commonmodel->updateLog($logDetails);
    }

    public function ActiveInactive(Request $request){
        $this->is_permitted($this->slugs, 'delete');
        $id = $request->input('id');
        $is_activeinactive = $request->input('is_activeinactive');
        $data=array('ors_is_active' => $is_activeinactive);
        $this->_CtoPaymentOrSetup->updateActiveInactive($id,$data);

    
        // Log Details Start
        $action = $is_activeinactive==1?'Restored':'Soft Deleted';
        $logDetails['module_id'] =$id;
        $logDetails['log_content'] = "User '".\Auth::user()->name."' OR Setup ".$action; 
        $this->_commonmodel->updateLog($logDetails);
        // Log Details End
    }

    public function samplePrint($id){
         $getdata = $this->_CtoPaymentOrSetup->getEditDetails($id);
        //$sample_file = $this->_CtoPaymentOrSetup->setPrintSample($data);
      if($getdata->or_field_form !='Accountable Form No. 51-C'){
           $data = $this->_CtoPaymentOrSetup->getEditDetails($id);
           if(isset($data)){
                $sample_file = $this->_CtoPaymentOrSetup->setPrintSample($data);
           }
           
           // $params = $this->_CtoPaymentOrSetup->getEditDetailsforsample($id);
           // $setup_details = json_decode($params->setup_details,false);
           // $setup_details = (array)$setup_details;
           // echo "<pre>"; print_r($setup_details); echo $setup_details['rpt_or_no']->rpt_or_no_name; exit;
           // $defaultFeesarr = array();

           //   $arrPaymentbankDetails =  (object)[];
           //   $data = [
           //          'transacion_no' => $setup_details['rpt_or_no']->af51c_or_no_name,
           //          'date' => $setup_details['rpt_or_date']->rpt_or_date_name,
           //          'municipality' => $setup_details['rpt_municipality']->rpt_municipality_name,
           //          'or_number' => $setup_details['rpt_or_no']->rpt_or_no_name,
           //          'payor' => $setup_details['rpt_payer']->rpt_payer_name,
           //          'total' => $setup_details['rpt_total']->rpt_total_name,
           //          'inword' => $setup_details['rpt_amount_words']->rpt_amount_words_name,
           //          'transactions' => $setup_details['rpt_treasurer']->rpt_treasurer_name,
           //          'payment_terms' =>  '1',
           //          'cash_details' => $arrPaymentbankDetails,
           //          'officername' => $setup_details['af51c_collecting_officer']->af51c_collecting_officer_name
           //      ];
           //   $sample_file =   $this->setPrintSamplerpt($id,$data);

      }

      if($getdata->or_field_form =='Accountable Form No. 51-C'){
            $params = $this->_CtoPaymentOrSetup->getEditDetailsforsample($id);
            if(isset($params)){
                $setup_details = json_decode($params->setup_details,false);
                $setup_details = (array)$setup_details;
                //echo "<pre>"; print_r($setup_details); echo $setup_details['af51c_or_no']->af51c_or_no_name; exit;
                $defaultFeesarr = array(
                                0 => array(
                                    'id'=>'1',
                                    'fees_description' => $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_name,
                                    'tax_amount' => $setup_details['af51c_bfees_amount']->af51c_bfees_amount_name,
                                )
                            );


                 $arrPaymentbankDetails =  (object)[];
                 $data = [
                        'transacion_no' => $setup_details['af51c_or_no']->af51c_or_no_name,
                        'date' => $setup_details['af51c_or_date']->af51c_or_date_name,
                        'agency' => $setup_details['af51c_agency']->af51c_agency_name,
                        'fund' => $setup_details['af51c_fund']->af51c_fund_name,
                        'or_number' => $setup_details['af51c_or_no']->af51c_or_no_name,
                        'payor' => $setup_details['af51c_payor']->af51c_payor_name,
                        'transactions' => $defaultFeesarr,
                        'accountcode' => $setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_name,
                        'total' => $setup_details['af51c_total']->af51c_total_name,
                        'inword' => $setup_details['af51c_amount_words']->af51c_amount_words_name,
                        'payment_terms' =>  '1',
                        'cash_details' => $arrPaymentbankDetails,
                        'officername' => $setup_details['af51c_collecting_officer']->af51c_collecting_officer_name
                    ];
                 $sample_file =   $this->setPrintSample($id,$data);
            }
        }
     }

    public function setPrintSample($id,$data){

        $paymnet_or_setups = $this->_CtoPaymentOrSetup->getEditDetails($id);
        if(!$paymnet_or_setups){
            return "OR SETUP Not Found...";
        }
        $setup_details = json_decode($paymnet_or_setups->setup_details);
        $setup_details = (array)$setup_details;
        //echo "<pre>"; print_r($setup_details); exit;
        $border = 0;
        $pdfwidth = $paymnet_or_setups->width != null ? $paymnet_or_setups->width : 0;
        $pdfheight = $paymnet_or_setups->height != null ? $paymnet_or_setups->height : 0;
        $orientation = "L";
        if($paymnet_or_setups->is_portrait == 1){
            $orientation = "P";
        }
        $resolution  = array($pdfheight,$pdfwidth);
        $width = 0; $height = 0;
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        PDF::SetMargins(20, 10, 3,10);    
        PDF::SetAutoPageBreak(FALSE, 0);
        // PDF::AddPage($orientation, 'cm', array(10, 20), true, 'UTF-8', false);
        PDF::AddPage($orientation, $resolution);
        PDF::SetFont('Helvetica', '', 10);

        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        $ln=0; $fill=0; $reset=""; $align='L';

        // Positioning For Or No
        $or_is_bold = "";
        $or_is_visible = 0;
        if(isset($setup_details['af51c_or_no'])){
            if($setup_details['af51c_or_no']){
                $or_top = $setup_details['af51c_or_no']->af51c_or_no_position_top;
                $or_left = $setup_details['af51c_or_no']->af51c_or_no_position_left;
                $or_font = $setup_details['af51c_or_no']->af51c_or_no_font_size;
                if(isset($setup_details['af51c_or_no']->af51c_or_no_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_font_is_bold)){
                    $or_is_bold = "B";
                };
                if(isset($setup_details['af51c_or_no']->af51c_or_no_is_visible)){
                    $or_is_visible = 1;
                };
            }
            if($or_is_visible == 1){
                PDF::SetFont('Helvetica', $or_is_bold, $or_font);
                PDF::writeHTMLCell($width, $height, $or_left,$or_top, $data['or_number'], $border,$ln,$fill,$reset,$align);    
            }
        }
        
        // Positioning For Or Date
        $or_date_is_bold = "";
        $or_date_is_visible = 0; $border =0; $align='L';
        if(isset($setup_details['af51c_or_date'])){
            if($setup_details['af51c_or_date']){
                $or_date_top = $setup_details['af51c_or_date']->af51c_or_date_position_top;
                $or_date_left = $setup_details['af51c_or_date']->af51c_or_date_position_left;
                $or_date_font = $setup_details['af51c_or_date']->af51c_or_date_font_size;
                if(isset($setup_details['af51c_or_date']->af51c_or_date_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_font_is_bold)){
                    $or_date_is_bold = "B";
                };
                if(isset($setup_details['af51c_or_date']->af51c_or_date_is_visible)){
                    $or_date_is_visible = 1;
                };
            }
            if($or_date_is_visible == 1){
                PDF::SetFont('Helvetica', $or_date_is_bold, $or_date_font);
                PDF::writeHTMLCell($width, $height, $or_date_left,$or_date_top, $data['date'], $border,$ln,$fill,$reset,$align);    
            }
        }
        $or_agency_is_visible = 0; $border =0; $align='L';
        $or_agency_is_bold = "";
        if(isset($setup_details['af51c_agency'])){
            if($setup_details['af51c_agency']){
                $or_agency_top = $setup_details['af51c_agency']->af51c_agency_position_top;
                $or_agency_left = $setup_details['af51c_agency']->af51c_agency_position_left;
                $or_agency_font = $setup_details['af51c_agency']->af51c_agency_font_size;
                if(isset($setup_details['af51c_agency']->af51c_agency_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_font_is_bold)){
                    $or_agency_is_bold = "B";
                };
                if(isset($setup_details['af51c_agency']->af51c_agency_is_visible)){
                    $or_agency_is_visible = 1;
                };
            }
            if($or_agency_is_visible == 1){
                PDF::SetFont('Helvetica', $or_agency_is_bold, $or_agency_font);
                PDF::writeHTMLCell($width, $height, $or_agency_left ,$or_agency_top,$data['agency'], $border,$ln,$fill,$reset,$align);    
            }
        }

        $fund_is_visible = 0; $border =0; $align='L';
        $fund_is_bold = "";
        if(isset($setup_details['af51c_fund'])){
            if($setup_details['af51c_fund']){
                $fund_top = $setup_details['af51c_fund']->af51c_fund_position_top;
                $fund_left = $setup_details['af51c_fund']->af51c_fund_position_left;
                $fund_font = $setup_details['af51c_fund']->af51c_fund_font_size;
                if(isset($setup_details['af51c_fund']->af51c_fund_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_fund']->af51c_fund_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_fund']->af51c_fund_font_is_bold)){
                    $fund_is_bold = "B";
                };
                if(isset($setup_details['af51c_fund']->af51c_fund_is_visible)){
                    $fund_is_visible = 1;
                };
            }
            if($fund_is_visible == 1){
                PDF::SetFont('Helvetica', $fund_is_bold, $fund_font);
                PDF::writeHTMLCell($width, $height, $fund_left ,$fund_top,$data['fund'], $border,$ln,$fill,$reset,$align);    
            }
        }
        // Positioning For Constant City
       //PDF::writeHTMLCell(50, 0, 20,52,config('constants.defaultCityCode.city'), $border);//agency

        // Positioning For Payor
        $payor_is_bold = ""; $border =0; $align='L';
        $payor_is_visible = 0;
        if(isset($setup_details['af51c_payor'])){
            if($setup_details['af51c_payor']){
                $payor_top = $setup_details['af51c_payor']->af51c_payor_position_top;
                $payor_left = $setup_details['af51c_payor']->af51c_payor_position_left;
                $payor_font = $setup_details['af51c_payor']->af51c_payor_font_size;
                if(isset($setup_details['af51c_payor']->af51c_payor_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_font_is_bold)){
                    $payor_is_bold = "B";
                };
                if(isset($setup_details['af51c_payor']->af51c_payor_is_visible)){
                    $payor_is_visible = 1;
                };
            }
            if($payor_is_visible == 1){
                PDF::SetFont('Helvetica', $payor_is_bold, $payor_font);
                PDF::writeHTMLCell($width, $height, $payor_left , $payor_top, $data['payor'], $border,$ln,$fill,$reset,$align);    
            }
        }
        $aligntext ="left";  $alignamt ="left"; 
         if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_right_justify)){
                    $aligntext='right';
                };
          if(isset($setup_details['af51c_bfees_amount']->af51c_bfees_amount_right_justify)){
                    $alignamt='right';
                };       
        
        $htmldynahistory='<table border="'.$border.'">';
        foreach ($data['transactions'] as $key => $value) {
            if ($value['tax_amount'] != 0) {
                $htmldynahistory .='<tr>
                        <td width="60%" style="text-align:'.$aligntext.';font-size:10px;">
                        '.$value['fees_description'].'
                        </td>
                        <td width="40%" style="text-align:'.$alignamt.';font-size:10px;">'.$value['tax_amount'].'</td>
                    </tr>';
            }
        }
        $htmldynahistory .='</table>';
        //echo $htmldynahistory; exit;
        // Positioning For transactions, tax_amount
        $bfees_nature_col_is_bold = ""; $border =0; $align='L';
        $bfees_nature_col_is_visible = 0;
        if(isset($setup_details['af51c_bfees_nature_col'])){
            if($setup_details['af51c_bfees_nature_col']){
                $bfees_nature_col_top = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_position_top;
                $bfees_nature_col_left = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_position_left;
                $bfees_nature_col_font = $setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_font_size;
                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_is_show_border)){
                    $border = 1;
                };

                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_font_is_bold)){
                    $bfees_nature_col_is_bold = "B";
                };
                if(isset($setup_details['af51c_bfees_nature_col']->af51c_bfees_nature_col_is_visible)){
                    $bfees_nature_col_is_visible = 1;
                };
            }
            if($bfees_nature_col_is_visible == 1){
                PDF::SetFont('Helvetica', $bfees_nature_col_is_bold, $bfees_nature_col_font);
                PDF::writeHTMLCell($width, $height, $bfees_nature_col_left ,$bfees_nature_col_top, $htmldynahistory, $border);    
            }
        }
        
        $accpunt_is_bold = ""; $border =0; $align='L';
        $account_is_visible = 0;
        if(isset($setup_details['af51c_bfees_account_code'])){
            if($setup_details['af51c_bfees_account_code']){
                $accountcode_top = $setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_position_top;
                $accountcode_left = $setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_position_left;
                $total_font = $setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_font_size;
                if(isset($setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_font_is_bold)){
                    $accpunt_is_bold = "B";
                };
                if(isset($setup_details['af51c_bfees_account_code']->af51c_bfees_account_code_is_visible)){
                    $account_is_visible = 1;
                };
            }
            if($account_is_visible == 1){
                PDF::SetFont('Helvetica', $accpunt_is_bold, $total_font);
                PDF::writeHTMLCell($width, $height, $accountcode_left , $accountcode_top, $data['accountcode'], $border,$ln,$fill,$reset,$align);
            }
        }
        // Positioning For Total
        $total_is_bold = ""; $border =0; $align='L';
        $total_is_visible = 0;
        if(isset($setup_details['af51c_total'])){
            if($setup_details['af51c_total']){
                $total_top = $setup_details['af51c_total']->af51c_total_position_top;
                $total_left = $setup_details['af51c_total']->af51c_total_position_left;
                $total_font = $setup_details['af51c_total']->af51c_total_font_size;
                if(isset($setup_details['af51c_total']->af51c_total_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_total']->af51c_total_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_total']->af51c_total_font_is_bold)){
                    $total_is_bold = "B";
                };
                if(isset($setup_details['af51c_total']->af51c_total_is_visible)){
                    $total_is_visible = 1;
                };
            }
            if($total_is_visible == 1){
                PDF::SetFont('Helvetica', $total_is_bold, $total_font);
                PDF::writeHTMLCell($width, $height, $total_left, $total_top, $data['total'], $border,$ln,$fill,$reset,$align);
            }
        }
        
        
        // Positioning for Amount In Words
        $amount_words_is_bold = ""; $border =0; $align='L';
        $amount_words_is_visible = 0;
        if(isset($setup_details['af51c_amount_words'])){
            if($setup_details['af51c_amount_words']){
                $amount_words_top = $setup_details['af51c_amount_words']->af51c_amount_words_position_top;
                $amount_words_left = $setup_details['af51c_amount_words']->af51c_amount_words_position_left;
                $amount_words_font = $setup_details['af51c_amount_words']->af51c_amount_words_font_size;
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_font_is_bold)){
                    $amount_words_is_bold = "B";
                };
                if(isset($setup_details['af51c_amount_words']->af51c_amount_words_is_visible)){
                    $amount_words_is_visible = 1;
                };
            }
            $alignwordtext ="left"; 
            if(isset($setup_details['af51c_amount_words']->af51c_amount_words_right_justify)){
                    $align='R';
                };

            if($amount_words_is_visible == 1){
                $amountinworld =  $data['inword']; 
                // PDF::writeHTMLCell(55, 0, 33,129,$amountinworld, $border);//amount in words
                PDF::SetFont('Helvetica', $amount_words_is_bold, $amount_words_font);
                PDF::writeHTMLCell($width, $height, $amount_words_left , $amount_words_top, $amountinworld, $border);
            }
        }
        
        
        // type of payment
            // $checked = url('./assets/images/checkbox-checked.jpeg');
            // PDF::Image(url(''),8, 0, 9,142);
        $checked = '/';
        $unchecked = '';
        $cash = ($data['payment_terms'] =='1')? $checked : $unchecked;
        $check = ($data['payment_terms'] =='3')? $checked : $unchecked;
        $order = ($data['payment_terms'] =='2')? $checked : $unchecked;
        PDF::writeHTMLCell(8, 0, 9,142,$cash, $border);// check cash
        PDF::writeHTMLCell(8, 0, 9,148,$check, $border);// check check
        PDF::writeHTMLCell(8, 0, 9,154,$order, $border);// check money order
        
        // Positioning for Amount In Words
        $htmldynahistory='<table border="'.$border.'" style="text-align:center">';
        foreach ($data['cash_details'] as $key => $value) {
            // dd($value);
                $htmldynahistory .='<tr>
                        <td>'.$this->bank($value->bank_id)->bank_code.'</td>
                        <td>'.$value->opayment_check_no.'</td>
                        <td>'.Carbon::parse($value->opayment_date)->format('m/d/y').'</td>
                    </tr>';
        }
        $htmldynahistory .='</table>';

        
        $cashier_details_is_bold = ""; $border =0; $align='L';
        $cashier_details_is_visible = 0;
        if(isset($setup_details['af51c_cashier_details'])){
            if($setup_details['af51c_cashier_details']){
                $cashier_details_top = $setup_details['af51c_cashier_details']->af51c_cashier_details_position_top;
                $cashier_details_left = $setup_details['af51c_cashier_details']->af51c_cashier_details_position_left;
                $cashier_details_font = $setup_details['af51c_cashier_details']->af51c_cashier_details_font_size;
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_right_justify)){
                    $align='R';
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_font_is_bold)){
                    $cashier_details_is_bold = "B";
                };
                if(isset($setup_details['af51c_cashier_details']->af51c_cashier_details_is_visible)){
                    $cashier_details_is_visible = 1;
                };
            }
            if($cashier_details_is_visible == 1){
                PDF::SetFont('Helvetica', $cashier_details_is_bold, $cashier_details_font);
                PDF::writeHTMLCell($width, $height, $cashier_details_left , + $cashier_details_top, $htmldynahistory, $border,$ln,$fill,$reset,$align);
            }
        }
        // Positioning For Collecting Officer
        $collecting_officer_is_bold = ""; $border =0; $align='L';
        $collecting_officer_is_visible = 0;
        if(isset($setup_details['af51c_collecting_officer'])){
            if($setup_details['af51c_collecting_officer']){
                $collecting_officer_top = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_position_top;
                $collecting_officer_left = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_position_left;
                $collecting_officer_font = $setup_details['af51c_collecting_officer']->af51c_collecting_officer_font_size;
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_font_is_bold)){
                    $collecting_officer_is_bold = "B";
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_is_visible)){
                    $collecting_officer_is_visible = 1;
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_is_show_border)){
                    $border = 1;
                };
                if(isset($setup_details['af51c_collecting_officer']->af51c_collecting_officer_right_justify)){
                    $align='R';
                };
            }
            if($collecting_officer_is_visible == 1){
                PDF::SetFont('Helvetica', $collecting_officer_is_bold, $collecting_officer_font);
                PDF::writeHTMLCell($width, $height, $collecting_officer_left , $collecting_officer_top, $data['officername'], $border,$ln,$fill,$reset,$align);
            }
         }

         PDF::Output('Receipt.pdf');
    }
       
    public function store(Request $request){
        if($request->input('id')>0){
            $this->is_permitted($this->slugs, 'update');
        }else{
            $this->is_permitted($this->slugs, 'create');
        }
		
        $data = (object)$this->data;
        $system_or_types = $this->_CtoPaymentOrSetup->getSystemORType();
        $user_or_types = $this->_CtoPaymentOrSetup->getUserORType();

        $system_arr_type = array(""=>"Please Select");
        foreach ($system_or_types as $val) {
            $system_arr_type[$val->or_field_form]=$val->or_field_form;
        }

        $user_arr_type = array(""=>"Please Select");
        foreach ($user_or_types as $val) {
            $user_arr_type[$val->id]=$val->id.'-'.$val->ortype_name;
        }
        
        $copy_user_arr = [];
        if($request->input('id')>0 && $request->input('submit')==""){
            // $data = $this->_CtoPaymentOrSetup->getEditDetails($request->id);
            // return $sample_file = $this->_CtoPaymentOrSetup->setPrintSample($data);
            $data = $this->_CtoPaymentOrSetup->getEditDetails($request->input('id'));
            foreach ($this->_CtoPaymentOrSetup->getORTypeForFieldName($data->or_field_form) as $key => $fields) {
                $this->setupData[$fields->or_field] = $fields->or_field_name;
            }
            if(isset($data->setup_details)){
                $data->setup_details = json_decode($data->setup_details,false);
            }
            $copy_user_arr[''] = 'Select';
            foreach ($this->_CtoPaymentOrSetup->getUserDetails($data) as $key => $value) {
                $copy_user_arr[$value->user_id] =  $value->fullname;
            }
            $data->copy_user_id = '';
        }
        
        $setupData = $this->setupData;
       
        if($request->input('submit')!=""){
            foreach((array)$this->data as $key=>$val){
                $this->data[$key] = $request->input($key);
            }
            $this->data['or_field_form']=$request->or_field_form;
            $form_details = $this->_CtoPaymentOrSetup->getFormDetails($request->or_field_form);
            if($form_details){
                $this->data['form_id']=$form_details->id;
                $this->data['width']=$form_details->width;
                $this->data['height']=$form_details->height;
                $this->data['department']=$form_details->department;
                $this->data['is_portrait']=isset($request->is_portrait) ? 1 : 0;
            }
            $this->data['updated_by']=\Auth::user()->creatorId();
            $this->data['updated_at'] = date('Y-m-d H:i:s');
            if($request->input('id')>0){
                $this->_CtoPaymentOrSetup->updateData($request->input('id'),$this->data);
                $success_msg = 'Updated successfully.';
                $logDetails['log_content'] =  "User '".\Auth::user()->name."' Updated OR Setup '".$this->data['ortype_id']."'"; 
            }else{
                $this->data['created_by']=\Auth::user()->creatorId();
                $this->data['created_at'] = date('Y-m-d H:i:s');
                $this->data['ors_is_active'] = 1;
                $request->id = $this->_CtoPaymentOrSetup->addData($this->data);
                $success_msg = 'Added successfully.';
                $logDetails['log_content'] = "User '".\Auth::user()->name."' Added OR Setup '".$this->data['ortype_id']."'"; 
            }
            $logDetails['module_id'] =$request->id;
            $this->_commonmodel->updateLog($logDetails);
            return redirect()->route('orSetup.index')->with('success', __($success_msg));
        }
        return view('Bplo.or_setup.create',compact('data','setupData','system_arr_type', 'user_arr_type', 'copy_user_arr'));
    }

    public function copyOrsetup(Request $request){
        $cto_payment_or_setups = $this->_CtoPaymentOrSetup->getORSetups($request);
        $setups = [];
        if($cto_payment_or_setups->setup_details != null){
            $cto_payment_or_setups->setup_details = json_decode($cto_payment_or_setups->setup_details);
            foreach ($cto_payment_or_setups->setup_details as $key => $value) {
                $setups[] = [
                    "key" => $key,
                    "values" => $value
                ];
            }
        }
        return response()->json(['data' => $cto_payment_or_setups,'setups' => $setups, 'message' => 'Data Found', 'status' => 200]);
    }
    
    public function formValidation(Request $request){
        $validator = \Validator::make(
            $request->all(), [
                'ortype_id' => [
                    'required',
                    new UniqueOrTypeForUser(Auth::user()->id),
                ],
                'or_field_form' => [
                    'required',
                    new UniqueOrFieldFormForUser(Auth::user()->id),
                ]
            ]
        );
        $arr=array('ESTATUS'=>0);
        if($validator->fails() && $request->input('id') == null){
            $messages = $validator->getMessageBag();
            $arr['field_name'] = $messages->keys()[0];
            $arr['error'] = $messages->all()[0];
            $arr['ESTATUS'] = 1;
        }
        echo json_encode($arr);exit;
    }
   
}
