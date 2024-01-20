<?php

namespace App\Models\HealthSafety;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class HealthandSafetyCashering extends Model
{
    public function getTransactions($id){
        $sql = DB::table('cto_top_transactions as ctt')->select('ctt.id','ctt.transaction_no')->where('tfoc_is_applicable',6)->whereIn('ctt.top_transaction_type_id',[23,56,46]);
        if($id<=0){
            $sql->where('is_paid','0');
        }
        return $sql->get();
    }
    public function updateOrRegisterData($id,$columns){
        return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
    }

    public function updateOrAssignmentData($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }

    public function updateHoappData($id,$columns){
        return DB::table('ho_lab_requests')->where('id',$id)->update($columns);
    }
    public function getHoappid($id){
        return DB::table('cto_top_transactions')->select('transaction_ref_no')->where('id',$id)->first();
    }
    
    public function checkOrUsedOrNot($or_no,$cashierid=0){
       return DB::table('cto_cashier')->select('or_no')->where('ortype_id',2)->where('or_no',$or_no)->where('id','!=',(int)$cashierid)->orderby('id','DESC')->exists();
    }
    public function checkORAssignedORNot(){
        return DB::table('cto_payment_or_assignments AS pa')
            ->join('cto_payment_or_type_details AS potd','pa.ortype_id', '=', 'potd.ortype_id')
            ->where('pa.ortype_id',2)->where('potd.pcs_id',1)->where('ora_is_active',1)->orderby('pa.id','ASC')->where('ora_is_completed','0')->exists();
    }
    public function getGetOrrange($id){
        return DB::table('cto_payment_or_assignments')->select('ora_from','ora_to','latestusedor')->where('ortype_id',$id)->where('ora_is_active',1)->orderby('id','ASC')->where('ora_is_completed','0')->first();
    }
    public function getLatestOrNumber($ortype_id){
        $createdBy = \Auth::user()->id;
       return DB::table('cto_cashier')->select('or_no')->whereIN('tfoc_is_applicable',[6,1,3,4])->where('ortype_id',$ortype_id)->where('created_by',$createdBy)->orderby('id','DESC')->first();
    }

    public function getCertificateDetails($id){
        return DB::table('cto_cashier AS cc')
        ->join('citizens AS c', 'c.id', '=', 'cc.client_citizen_id') 
            ->select('cc.*','c.cit_fullname','c.cit_last_name','c.cit_first_name','c.cit_middle_name','c.cit_suffix_name','c.cit_full_address')->where('cc.id',$id)->first();
    }

    public function getPreviousIssueNumber(){
        return DB::table('cto_cashier')->select('cashier_issue_no')->where('cashier_year',date("Y"))->orderby('id','DESC')->first();
    } 
   

    public function GetReqiestfees($id)
    {
        return DB::table('cto_cashier_details AS cc')->join('acctg_account_subsidiary_ledgers AS acctg', 'acctg.id', '=', 'cc.sl_id')->select('cc.*','cc.tfc_amount as tax_amount','acctg.description as fees_description')->where('cc.cashier_id',$id)->get();
    }
    
    public function updateOrUsed($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('ortype_id',$id)->where('ora_is_completed','0')->orderby('id','ASC')->where('ora_is_active',1)->limit(1)->update($columns);
    }
    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
    }
   
    public function getFundCode(){
         return DB::table('acctg_fund_codes')->select('id','code')->get()->toArray();
    }
    public function getBankList(){
        return DB::table('cto_payment_banks')->select('id','bank_code')->get()->toArray();
    }
    public function getTfocDtls($id){
        return DB::table('cto_tfocs')->select('gl_account_id','sl_id','fund_id')->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function checkRecordIsExist($tfocid,$Cashierid){
         return DB::table('cto_cashier_details')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->get();
    } 
    public function updateCashierDetailsData($id,$columns){
        return DB::table('cto_cashier_details')->where('id',$id)->update($columns);
    }
    public function addCashierDetailsData($postdata){
        DB::table('cto_cashier_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateCashierPaymentData($id,$columns){
        return DB::table('cto_cashier_other_payments')->where('id',$id)->update($columns);
    }

    public function updateTopTransaction($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }

    public function GetToptransaction($id){
         return DB::table('cto_top_transactions')->select('transaction_no')->where('id',$id)->first();
    }
   
    public function addCashierPaymentData($postdata){
        DB::table('cto_cashier_other_payments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->where('ocr_is_active','1')->orderby('ocr_reason', 'ASC')->get();
    } 
    public function getChequeTypes(){
      return DB::table('check_type_masters')->select('id','ctm_description')->where('is_active','1')->orderby('ctm_description', 'ASC')->get();
    } 
    public function getEditDetails($id){
        return DB::table('cto_cashier')->where('id',$id)->first();
    }
    public function getCasheirDetails($id){
        return DB::table('cto_cashier_details')->select('cashier_year','tfoc_id','tfc_amount','ctc_taxable_amount')->where('cashier_id',$id)->orderby('id', 'ASC')->get();
    }
    public function getPaymentModeDetails($id,$p_type){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)
        ->where('payment_terms',(int)$p_type)
        ->orderby('id', 'ASC')->get();
    }
    
    public function getUserDetails($payee_type,$user_id){
        if($payee_type==1){
            return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix','p_barangay_id_no','rpo_address_house_lot_no','rpo_address_street_name','rpo_address_subdivision')->where('id',$user_id)->first();
        }else{
            return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','cit_full_address')->where('id',$user_id)->first();
        }
    }
    public function GetReqiestfeesdefault($id){
        return DB::table('ho_lab_fees')->select('id','hlf_service_name','hlf_fee','tfoc_id')->where('lab_req_id',$id)->orderby('id','ASC')->get();
    }
    public function getUserDetailsbytopid($topid){
        return DB::table('cto_top_transactions as ctt')->leftjoin('ho_lab_requests as hlr','ctt.transaction_ref_no','=','hlr.id')->leftjoin('citizens as ct','ct.id','=','hlr.payor_id')->select('hlr.id as hlrid','lab_req_amount','hlr.payor_id as cit_id','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','cit_full_address')->where('ctt.id',$topid)->first();
    }
    public function getappdatataxpayer($id){
        return DB::table('ho_lab_requests as app')->leftjoin('clients as c','c.id','=','app.payor_id')->select('c.p_mobile_no','c.full_name')->where('app.id',$id)->first();
    }
    public function getappdatacitizen($id){
          return DB::table('ho_lab_requests as app')->leftjoin('citizens as c','c.id','=','app.payor_id')->select('c.cit_mobile_no as p_mobile_no','c.cit_fullname as full_name')->where('app.id',$id)->first();
    }
    public function getTaxpayers($id=0){
        return DB::table('clients')->select('id','full_name','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_active',1)->orWhere('id',$id)->get()->toArray();
    }
    public function getCitizens($id=0){
        return DB::table('citizens')->select('id','cit_fullname','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name')->where('cit_is_active',1)->orWhere('id',$id)->get()->toArray();
    }
    public function getTaxFees($id=0){
        $sql= DB::table('cto_tfocs AS ctot')
           ->join('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')
           ->select('ctot.id','aas.description','tfoc_status')->where('tfoc_is_applicable','6');
        if($id==0){
            $sql->where('tfoc_status',1);
        }
        return $sql->get()->toArray();
    }
    public function getTaxFeesDetails($id=0){
        return DB::table('cto_tfocs AS ctot')
          ->join('acctg_account_subsidiary_ledgers AS aas', 'aas.id', '=', 'ctot.sl_id')->select('ctot.id','tfoc_amount')
          ->where('tfoc_is_applicable','6')->where('ctot.id',$id)->first();
    }
    public function getNatureFeeDetails($id=0){
        return DB::table('cto_cashier_details')->select('id','tfoc_id','ctc_taxable_amount','tfc_amount')->where('cashier_id',$id)->get()->toArray();
    }
    public function deleteCashieringDetails($id){
        return DB::table('cto_cashier_details')->where('id',(int)$id)->delete();
    }
    public function getCasheirDtls($id=0){
      return DB::table('cto_cashier')->select('document_json')->where('id',$id)->first();
    }

    public function Getorregisterid($id,$ornumber){
       return DB::table('cto_payment_or_assignments')->select('id as assignid','cpor_id as id','ora_to','or_count')->where('ortype_id',$id)->where('ora_is_completed',0)->where('ora_from','<=',$ornumber)->orderby('id', 'ASC')->first();
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $status=$request->input('status');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');

        if(!isset($params['start']) && !isset($params['length'])){
            $params['start']="0";
            $params['length']="10";
        }
        $columns = array( 
            1 =>"cc.cashier_year",
            2 =>"cc.payee_type",
            3 =>"c.full_name",
            4 =>"c.full_address",
            5 =>"cc.or_no",
            6 =>"cc.total_paid_amount",
            7 =>"cc.created_at", 
            8 =>"cc.payment_terms", 
            9 =>"cc.status",   
        );

        $sql = DB::table('cto_cashier AS cc')
            ->Leftjoin('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
            ->Leftjoin('citizens AS ct', 'ct.id', '=', 'cc.client_citizen_id')
			->leftjoin('hr_employees as he','he.user_id','=','cc.created_by') 
            ->select('cc.*','he.fullname','ct.cit_fullname','c.full_name','c.rpo_first_name','cc.cashier_particulars','cc.top_transaction_id','c.rpo_custom_last_name','c.rpo_middle_name','c.suffix','cit_last_name','cit_first_name','cit_middle_name','cit_suffix_name','c.full_address');
        $sql->where('tfoc_is_applicable','=','6');  
        if ($status == '3') {
            } else {
                   $sql->where('cc.status', '=', (int)$status);
        }
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(cit_full_address)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")   
                ->orWhere(DB::raw("CONCAT(cit_first_name, ' ', COALESCE(cit_middle_name, ''), ' ',cit_last_name)"), 'LIKE', "%{$q}%")   
                ->orWhere(DB::raw("CONCAT(rpo_address_house_lot_no, ',', COALESCE(rpo_address_street_name, ''), ',',rpo_address_subdivision)"), 'LIKE', "%{$q}%")   
                ->orWhere(function ($sql) use ($q) {
                          if ($q === 'Cash' || $q === 'cash') {
                              $sql->where('cc.payment_terms', '=', 1);
                          } elseif ($q === 'Bank' || $q === 'bank') {
                              $sql->where('cc.payment_terms', '=', 2);
                          }elseif ($q === 'Cheque' || $q === 'cheque') {
                              $sql->where('cc.payment_terms', '=', 3);
                          }elseif ($q === 'Credit Card' || $q === 'credit card') {
                              $sql->where('cc.payment_terms', '=', 4); 
                          }elseif ($q === 'Online Payment' || $q === 'online payment' || $q === 'online' || $q === 'Online') {
                              $sql->where('cc.payment_terms', '=', 5); 
                          }else {
                              $sql->where('cc.payment_terms', '=', '');
                          }
                    })
                ->orWhere(DB::raw("CONCAT(cit_house_lot_no, ' ', COALESCE(cit_street_name, ''), ' ',cit_subdivision)"), 'LIKE', "%{$q}%")   
				->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%");
            });
        }
    
        if(!empty($startdate) && isset($startdate)){
            $sdate = explode('-', $startdate);
            $startdate = $sdate[2]."-".$sdate[1]."-".$sdate[0]; 
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cc.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $edate = explode('-', $enddate);
            $enddate = $edate[2]."-".$edate[1]."-".$edate[0]; 
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cc.created_at','<=',trim($enddate));  
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cc.created_at','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
