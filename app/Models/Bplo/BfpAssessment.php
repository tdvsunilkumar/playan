<?php
namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use PDF;
use Auth;
use Carbon\Carbon;
use App\Models\CommonModelmaster;

class BfpAssessment extends Model
{
    public function getFundCode(){
         return DB::table('acctg_fund_codes')->select('id','code')->get()->toArray();
    }
    public function getBankList(){
        return DB::table('cto_payment_banks')->select('id','bank_code')->get()->toArray();
    }
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->where('ocr_is_active','1')->orderby('ocr_reason', 'ASC')->get();
    } 
    public function getChequeTypes(){
      return DB::table('check_type_masters')->select('id','ctm_description')->where('is_active','1')->orderby('ctm_description', 'ASC')->get();
    } 
    public function getEditDetails($bus_id=0,$end_id=0,$year=0){
        return DB::table('bfp_application_assessments')->where('busn_id',$bus_id)->where('bend_id',$end_id)->where('bfpas_ops_year',$year)->first();
    }
    public function getApplicationNo($bff_id=0){
        $sql= DB::table('bfp_application_forms')->select('bff_application_no')->where('id',$bff_id);
        return $sql->value('bff_application_no');
    }
    
    public function getAssDetails($id=0){
        return DB::table('bfp_application_assessments')->select('bfpas_document_json')->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('bfp_application_assessments')->where('id',$id)->update($columns);
    }
    public function getPreviousIssueNumber(){
        return DB::table('bfp_application_assessments')->select('bfpas_ops_no')->where('bfpas_ops_year',date("Y"))->orderby('id','DESC')->first();
    }
    public function checkOrUsedOrNot($or_no,$id){
       return DB::table('bfp_application_assessments')->select('bfpas_payment_or_no')->where('id','!=',(int)$id)->where('bfpas_payment_or_no',$or_no)->orderby('id','DESC')->exists();
    }
    public function checkAppNoUsedOrNot($app_no,$id){
       return DB::table('bfp_application_assessments')->select('bff_application_no')->where('id','!=',(int)$id)->where('bff_application_no',$app_no)->orderby('id','DESC')->exists();
    }
    public function getEmployee($user_id){
         return DB::table('hr_employees')->where('user_id',$user_id)->select('id','firstname','middlename','lastname','suffix','fullname','title')->first();
    }
    public function addData($postdata){
        DB::table('bfp_application_assessments')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function checkRecordIsExist($fmaster_id,$bfpas_id){
         return DB::table('bfp_application_assessment_fees')->select('id')->where('bfpas_id','=',$bfpas_id)->where('fmaster_id',$fmaster_id)->get();
    } 
    public function updateAssessmentDetailsData($id,$columns){
        return DB::table('bfp_application_assessment_fees')->where('id',$id)->update($columns);
    }
    public function addAssessmentDetailsData($postdata){
        DB::table('bfp_application_assessment_fees')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getYearDetails(){
        return DB::table('bfp_application_assessments')->select('bfpas_ops_year')->groupBy('bfpas_ops_year')->orderBy('bfpas_ops_year','DESC')->get()->toArray(); 
    }
    public function getFeeList(){
        return DB::table('bfp_fees_masters')->select('fmaster_description','fmaster_code','id')->where('fmaster_status',1)->orderBy('fmaster_code','ASC')->get()->toArray(); 
    }
    public function getFeeDetails($id){
        return DB::table('bfp_fees_masters')->select('fmaster_subdetails_json')->where('id',(int)$id)->first(); 
    }
    public function deleteAssessmentFeeOption($id){
        return DB::table('bfp_application_assessment_fees')->where('id',(int)$id)->delete();
    }
    public function getAssessFeeDetails($id){
        return DB::table('bfp_application_assessment_fees AS af')
            ->join('bfp_fees_masters AS fm', 'fm.id', '=', 'af.fmaster_id') 
            ->select("af.*","fmaster_subdetails_json",'fmaster_description','fmaster_code')
            ->where('bfpas_id',(int)$id)->get()->toArray(); 
    }
    public function getAssessFeeDetailsForPrint($id){
        return DB::table('bfp_fees_masters AS fm')
            ->Leftjoin('bfp_application_assessment_fees AS af', function ($join)use($id) {
                $join->on('fm.id', '=', 'af.fmaster_id');
                $join->where('bfpas_id', '=',(int)$id);
            })
            ->select("af.*","fmaster_subdetails_json",'fmaster_description','fmaster_code')
            ->where('fmaster_status',1)->orderBy('fmaster_code','ASC')->get()->toArray(); 
    }
    public function getBfpType(){
        return DB::table('bfp_application_type')->select('id','btype_name')->where('btype_status',1)->orderBy('btype_name','ASC')->get()->toArray(); 
    }
    public function getBusinessDetails($id){
        return DB::table('bplo_business as bb')
            ->join('clients AS c', 'c.id', '=', 'bb.client_id') 
            ->select(DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name','busn_name','busn_office_main_barangay_id','app_code','bb.client_id')
            ->where('bb.id','=',(int)$id)  
            ->first();
    }
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $year=$request->input('year');
        $endorsement_status=$request->input('endorsement_status');
        $payment_status=$request->input('payment_status');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 =>"busns_id_no",
          2 =>"ownar_name",
          3 =>"busn_name",
          4 =>"app_code",
          5 =>"busn_app_status",
          6 =>"bend_status",
          7 =>"bfpas_total_amount_paid",
          8 =>"as.created_at",
          9 =>"as.payment_status"
        );
        $sql = DB::table('bfp_application_assessments AS as')
            ->join('bplo_business AS bb', 'bb.id', '=', 'as.busn_id')
            ->Leftjoin('clients AS c', 'c.id', '=', 'as.client_id') 
            ->Leftjoin('bplo_business_endorsement AS bbe', 'bbe.id', '=', 'as.bend_id') 
            ->select('as.id','as.bfpas_total_amount_paid','as.bfpas_payment_or_no','as.bfpas_is_fully_paid','as.created_at',DB::raw("CASE 
            WHEN rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
            WHEN rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_custom_last_name,''),', ',COALESCE(suffix,'')))
            WHEN suffix IS NULL THEN TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,'')))
            WHEN rpo_first_name IS NULL AND rpo_middle_name IS NULL AND suffix IS NULL THEN COALESCE(rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(rpo_first_name,''),' ',COALESCE(rpo_middle_name,''),' ',COALESCE(rpo_custom_last_name,''))) END as ownar_name"),'suffix','rpo_first_name','rpo_middle_name','rpo_custom_last_name','busn_name','busns_id_no','busn_app_status','busn_app_method','app_code','bend_status','as.busn_id','as.bend_id','as.bfpas_ops_year','payment_status');
          
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ', COALESCE(rpo_custom_last_name), ', ', suffix)"), 'LIKE', "%{$q}%")    
                    ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(function ($sql) use ($q) {
                          if ($q === 'New' || $q === 'new') {
                              $sql->where('app_code', '=', 1); // Condition for Taxable (option 1)
                          } elseif ($q === 'Renew' || $q === 'renew') {
                              $sql->where('app_code', '=', 2); // Condition for Exempt (option 2)
                          }elseif ($q === 'Retire' || $q === 'retire') {
                              $sql->where('app_code', '=', 3); // Condition for Exempt (option 2)
                          }else {
                              $sql->where('app_code', '=', ''); // Condition to return no results for other search terms
                          }
                    })
					->orWhere(function ($sql) use ($q) {
                          if ($q === 'Not Started' || $q === 'not started') {
                              $sql->where('bend_status', '=', 0); // Condition for Taxable (option 1)
                          } elseif ($q === 'In-Progress' || $q === 'in-progress') {
                              $sql->where('bend_status', '=', 1); // Condition for Exempt (option 2)
                          }elseif ($q === 'Completed' || $q === 'completed') {
                              $sql->where('bend_status', '=', 2); // Condition for Exempt (option 2)
                          }elseif($q === 'Decline' || $q === 'decline') {
                              $sql->where('bend_status', '=', 3); // Condition to return no results for other search terms
                          }
                    })
				  ->orWhere(function ($sql) use ($q) {
                          if ($q === 'Not Completed' || $q === 'not completed') {
                              $sql->where('busn_app_status', '=', 0); // Condition for Taxable (option 1)
                          } elseif ($q === 'For Verification' || $q === 'for verification') {
                              $sql->where('busn_app_status', '=', 1); // Condition for Exempt (option 2)
                          }elseif ($q === 'For Endorsement' || $q === 'for endorsement') {
                              $sql->where('busn_app_status', '=', 2); // Condition for Exempt (option 2)
                          }elseif($q === 'for assessment' || $q === 'for assessment'){
                              $sql->where('busn_app_status', '=', 3); // Condition to return no results for other search terms
                          }elseif($q === 'For Payment' || $q === 'for payment'){
                              $sql->where('busn_app_status', '=', 4); // Condition to return no results for other search terms
                          }elseif($q === 'For Issuance' || $q === 'for issuance'){
                              $sql->where('busn_app_status', '=', 5); // Condition to return no results for other search terms
                          }elseif($q === 'License Issued' || $q === 'license issued'){
                              $sql->where('busn_app_status', '=', 6); // Condition to return no results for other search terms
                          }elseif($q === 'Declined' || $q === 'declined'){
                              $sql->where('busn_app_status', '=', 7); // Condition to return no results for other search terms
                          }elseif($q === 'cancelled Permit' || $q === 'cancelled permit'){
                              $sql->where('busn_app_status', '=', 8); // Condition to return no results for other search terms
                          }
                    })
                ->orWhere(DB::raw('DATE_FORMAT(as.created_at, "%Y-%m-%d %H:%i:%s")'), 'LIKE', "%" . $q . "%")
                ->orWhere(DB::raw('LOWER(as.bfpas_payment_or_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('REPLACE(bfpas_total_amount_paid, ",", "")'),'LIKE','%' . str_replace(',', '', strtolower($q)) . '%')
                ->orWhere(DB::raw('LOWER(busn_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(busns_id_no)'),'like',"%".strtolower($q)."%");
            });
        }
        if(!empty($year)){
            $sql->where('bfpas_ops_year',(int)$year);
        }
        if(!empty($endorsement_status)){
            $sql->where('bend_status',(int)$endorsement_status);
        }
        if($payment_status==''){
            
        }else{
            $sql->where('payment_status',$payment_status);
        }
        // $sql->where('payment_status',$payment_status);
        
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('as.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    // reciept
    public function find($id)
    {
        return DB::table('bfp_application_assessments as bfpas')->Leftjoin('clients AS c', 'c.id', '=', 'bfpas.client_id')->where('bfpas.id',$id)->first();
    }
    public function GetReqiestfees($id)
    {
        return DB::table('bfp_application_assessment_fees AS fees')->join('bfp_fees_masters AS fee_desc', 'fee_desc.id', '=', 'fees.fmaster_id')->select('fees.baaf_amount_fee as tax_amount','fee_desc.fmaster_shortname as fees_description')->where('fees.bfpas_id',$id)->get();
    }
    public function GetPaymentbankdetails($id){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','2')->orderby('id', 'ASC')->get();
    }
    public function printReceipt($data)
    {
		
        PDF::SetTitle('Receipt: '.$data['transacion_no'].'');    
        PDF::SetMargins(0, 0, 0,false);    
        PDF::SetAutoPageBreak(FALSE, 0);
        PDF::AddPage('P', 'DL');
        PDF::SetFont('Helvetica', '', 10);

        $border = 0;
        $topPos = 35;
        $rightPos = 12;
        //writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true) $x and $y if for positioning
        PDF::writeHTMLCell(50, 0, $rightPos + 60,$topPos +5, $data['or_number'], $border);//Or number

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(50, 0, $rightPos + 15, $topPos + 1,Carbon::parse($data['date'])->toFormattedDateString(), $border);//Date

        PDF::writeHTMLCell(50, 0, $rightPos + 25, $topPos + 12 ,config('constants.defaultCityCode.city'), $border);//agency

        PDF::writeHTMLCell(50, 0, $rightPos + 25, $topPos + 21,$data['payor'], $border);//Payor
        
        $htmldynahistory='<table border="'.$border.'">
                            <tr>
                                <td width="158"></td>
                                <td width="40"></td>
                                <td width="83px"></td>
                            </tr>
        ';
        foreach ($data['transactions'] as $key => $value) {
            if ($value->tax_amount != 0) {
                $htmldynahistory .='<tr>
                        <td style="text-align:left;">
                        '.$value->fees_description.'
                        </td>
                        <td></td>
                        <td style="text-align:left;">'.number_format($value->tax_amount,2).'</td>
                    </tr>';
            }
        }
        if (isset($data['surcharge']) && $data['surcharge']) {
            $htmldynahistory .='
            <tr>
                <td style="text-align:left;">
                Surcharge Fee
                </td>
                <td></td>
                <td style="text-align:left;">'.number_format($data['surcharge'],2).'</td>
            </tr>
            ';
        }
        if (isset($data['interest']) && $data['interest']) {
            $htmldynahistory .='
            <tr>
                <td style="text-align:left;">
                Interest Fee
                </td>
                <td></td>
                <td style="text-align:left;">'.number_format($data['interest'],2).'</td>
            </tr>
            ';
        }
        $htmldynahistory .='</table>';
        PDF::writeHTMLCell(90, 0, $rightPos + 6, $topPos + 35,$htmldynahistory, $border); //collection table

        PDF::SetFont('Helvetica', '', 10);
        PDF::writeHTMLCell(35, 0, $rightPos + 75, $topPos + 101,number_format($data['total'],2), $border); //total
	    $CommonModelmaster = new CommonModelmaster();
        $amountinworld = $CommonModelmaster->numberToWord($data['total']);
        PDF::writeHTMLCell(60, 0, $rightPos + 33, $topPos + 108,$amountinworld, $border);//amount in words

        
        // type of payment
            // $checked = url('./assets/images/checkbox-checked.jpeg');
            // PDF::Image(url(''),8, 0, $rightPos + 9,142);
        $checked = '/';
        $unchecked = '';
        $cash = ($data['payment_terms'] =='1')? $checked : $unchecked;
        $check = ($data['payment_terms'] =='3')? $checked : $unchecked;
        $order = ($data['payment_terms'] =='2')? $checked : $unchecked;
        PDF::writeHTMLCell(8, 0, $rightPos + 10, $topPos + 123,$cash, $border);// check cash
        PDF::writeHTMLCell(8, 0, $rightPos + 10, $topPos + 130,$check, $border);// check check
        PDF::writeHTMLCell(8, 0, $rightPos + 10, $topPos + 136,$order, $border);// check money order
        
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
        PDF::writeHTMLCell(65, 0, $rightPos + 31, $topPos + 125,$htmldynahistory, $border);// bank

        PDF::writeHTMLCell(40, 0, $rightPos + 55, $topPos + 157,Auth::user()->hr_employee->fullname, $border,0,0,true,'C'); //collecting officer

        PDF::Output('Receipt: '.$data['transacion_no'].'.pdf');
		
    }
}
