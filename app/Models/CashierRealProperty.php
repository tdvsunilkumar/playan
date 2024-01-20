<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Controllers\AccountRreceivablesPropertyController;
use App\Helpers\Helper;

class CashierRealProperty extends Model
{
    use HasFactory;

    public function getTransactions($request){
        $term = $request->term;
        $id = $request->id;
        $query = DB::table('cto_top_transactions as ctt')
               ->join('rpt_cto_billings as rcb','rcb.id', '=', 'ctt.transaction_ref_no')
               ->select('ctt.id','ctt.transaction_no as text')
               ->where('tfoc_is_applicable',2)
               ->orderBy('ctt.transaction_no','desc');
        if($id == ''){
            $query->where('is_paid','0');
        }
        if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(ctt.transaction_no)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;
    }

    public function realPropertyPaidTaxesOrNot($id = '',$year = ''){
       
        $sql =  DB::table('cto_cashier_real_properties as ctorp')
                         ->select('cc.or_no','cc.cashier_or_date','cc.total_paid_amount',
                            DB::raw('MIN(cbd.sd_mode) as fromQtr'),
                            DB::raw('MAX(cbd.sd_mode) as toQtr'),
                            'cbd.cbd_covered_year as yearToPaid'
                          )
                         ->join('cto_cashier as cc',function($j){
                            $j->on('cc.id','=','ctorp.cashier_id')->where('cc.tfoc_is_applicable',2)->where('cc.status',1);
                         })
                         ->join('cto_cashier_details as ccd',function($j){
                            $j->on('ccd.cashier_id','=','cc.id')->where('ccd.tfoc_is_applicable',2);
                         })
                         ->join('rpt_cto_billing_details as cbd',function($j) use ($year){
                            $j->on('cbd.id','=','ccd.cbd_code')->where('cbd.cbd_covered_year','>=',$year);
                         })
                         ->where('ctorp.tfoc_is_applicable',2)
                         ->where('ctorp.rp_code',$id)
                         ->first();
        return $sql;                 
    }
    public function checkExistCreditAmout($propId,$previous_cashier_id,$current_cashier_id){
        //dd($propId);
        $sql =  DB::table('cto_cashier_real_properties as ctorp')
            ->join('cto_cashier as cc','cc.id','=','ctorp.cashier_id')
            ->select('ctorp.id','ctorp.tax_credit_amount','ctorp.or_no','cc.cashier_or_date');
        if($previous_cashier_id>0){
            $sql->where('ctorp.id',$previous_cashier_id);
        }else{
            $sql->where('ctorp.tax_credit_is_useup',0);
            $sql->where('ctorp.id','!=',$current_cashier_id);
        }

        if(!$previous_cashier_id && $current_cashier_id>0){}else{
            $sql->where('ctorp.rp_property_code',(int)$propId)
                ->where('ctorp.tax_credit_amount','>',0)
                ->where('ctorp.tcm_id','>',0)
                ->orderby('ctorp.id','DESC');
            return $sql->first();
        }
    }

    public function updateOrRegisterData($id,$columns){
        return DB::table('cto_payment_or_registers')->where('id',$id)->update($columns);
    }

    public function addDataIncomeDetails($data=''){
        return DB::table('cto_cashier_income')->insert($data);
    }

    public function deleteCashierIncomeData($id = ''){
        return DB::table('cto_cashier_income')->where('cashier_id',$id)->delete();
    }

    public function updateOrAssignmentData($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('id',$id)->update($columns);
    }
    
    public function checkOrUsedOrNot($or_no){
       return DB::table('cto_cashier')->select('or_no')->where('ortype_id',5)->where('or_no',$or_no)->orderby('id','DESC')->exists();
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
       return DB::table('cto_cashier')->select('or_no')->where('tfoc_is_applicable',2)->where('ortype_id',$ortype_id)->where('created_by',$createdBy)->orderby('id','DESC')->first();
    }

    public function getTfocDetails($id='')
    {
        return DB::table('cto_tfocs')->select('fund_id')->where('id',$id)->first();
    }

    public function addDataInCasheringIncome($detailId = ''){
        //->where('cc.status',1)
        $cashierDetails = DB::table('cto_cashier_details as ccd')
                              ->select('ccd.id','ccd.cashier_id','ccd.tfoc_is_applicable','cc.or_no','cc.cashier_or_date','c.full_name','cc.coa_no','or_register_id','ccd.basic_tfoc_id','ccd.basic_gl_id','ccd.basic_sl_id','ccd.basic_amount','ccd.basic_penalty_tfoc_id','ccd.basic_penalty_gl_id','ccd.basic_penalty_sl_id','ccd.basic_penalty_amount','ccd.basic_discount_tfoc_id','ccd.basic_discount_gl_id','ccd.basic_discount_sl_id','ccd.basic_discount_amount','ccd.sef_tfoc_id','ccd.sef_gl_id','ccd.sef_sl_id','ccd.sef_amount','ccd.sef_penalty_tfoc_id','ccd.sef_penalty_gl_id','ccd.sef_penalty_sl_id','ccd.sef_penalty_amount','ccd.sef_discount_tfoc_id','ccd.sef_discount_gl_id','ccd.sef_discount_sl_id','ccd.sef_discount_amount','ccd.sh_tfoc_id','ccd.sh_gl_id','ccd.sh_sl_id','ccd.sh_amount','ccd.sh_discount_tfoc_id','ccd.sh_discount_gl_id','ccd.sh_discount_sl_id','ccd.sh_discount_amount','ccd.sh_penalty_tfoc_id','ccd.sh_penalty_gl_id','ccd.sh_penalty_sl_id','ccd.sh_penalty_amount','cc.or_register_id','or.ora_from','or.ora_to','or.cpor_series as form_code','rp.brgy_code_id')
                              ->join('rpt_cto_billing_details as cbd','cbd.id','=','ccd.cbd_code')
                              ->join('rpt_properties as rp','rp.id','=','cbd.rp_code')
                              ->join('cto_cashier as cc',function($j){
                                $j->on('cc.id','=','ccd.cashier_id')->where('cc.tfoc_is_applicable',2)->where('cc.status',1);
                              })
                              ->join('clients as c','c.id','=','cc.client_citizen_id')
                              ->join('cto_payment_or_registers as or','or.id','=','cc.or_register_id')
                              ->where('ccd.id',$detailId)->first();
        if($cashierDetails != null){
            $dataToSaveInIncomeTable = [];
            $commonIncomeData        = [
                'cashier_id' => $cashierDetails->cashier_id,
                'cashier_details_id' => $cashierDetails->id,
                'tfoc_is_applicable' => $cashierDetails->tfoc_is_applicable,
                'taxpayer_name' => $cashierDetails->full_name,
                'cashier_or_date' => $cashierDetails->cashier_or_date,
                'barangay_id' => $cashierDetails->brgy_code_id,
                'or_no' => $cashierDetails->or_no,
                'coa_no' => $cashierDetails->coa_no,
                'or_from' => $cashierDetails->ora_from,
                'or_to' => $cashierDetails->ora_to,
                'form_code' => $cashierDetails->form_code,
                'or_register_id' => $cashierDetails->or_register_id,
                'created_by' => \Auth::user()->id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];
            /* Basic Data */
            $tdocDetails = $this->getTfocDetails($cashierDetails->basic_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0; 
            $commonIncomeData['tfoc_id'] = $cashierDetails->basic_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->basic_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->basic_sl_id;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->basic_amount, 2, '.', '');
            $commonIncomeData['is_discount'] = 0;
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            /* Basic Data */

            /* Basic Penality Data */
            if($cashierDetails->basic_penalty_amount > 0){
                $tdocDetails = $this->getTfocDetails($cashierDetails->basic_penalty_tfoc_id);
                $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0; 
                $commonIncomeData['tfoc_id'] = $cashierDetails->basic_penalty_tfoc_id; 
                $commonIncomeData['gl_account_id'] = $cashierDetails->basic_penalty_gl_id;
                $commonIncomeData['sl_account_id'] = $cashierDetails->basic_penalty_sl_id;
                $commonIncomeData['amount'] = number_format((float)$cashierDetails->basic_penalty_amount, 2, '.', '');
                $commonIncomeData['is_discount'] = 0;
                $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* Basic Penality Data */

            /* Basic Discount Data */
            if($cashierDetails->basic_discount_amount > 0){
                $tdocDetails = $this->getTfocDetails($cashierDetails->basic_discount_tfoc_id);
                $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0; 
                $commonIncomeData['tfoc_id'] = $cashierDetails->basic_discount_tfoc_id; 
                $commonIncomeData['gl_account_id'] = $cashierDetails->basic_discount_gl_id;
                $commonIncomeData['sl_account_id'] = $cashierDetails->basic_discount_sl_id;
                $commonIncomeData['amount'] = number_format((float)$cashierDetails->basic_discount_amount, 2, '.', '');
                $commonIncomeData['is_discount'] = 1;
                $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* Basic Discount Data */

            /* SEF BASIC DATA */
            if($cashierDetails->sef_amount > 0){
            $tdocDetails = $this->getTfocDetails($cashierDetails->sef_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0;
            $commonIncomeData['tfoc_id'] = $cashierDetails->sef_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->sef_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->sef_sl_id;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->sef_amount, 2, '.', '');
            $commonIncomeData['is_discount'] = 0;
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* SEF BASIC DATA */

            /* SEF PNALITY DATA */
            if($cashierDetails->sef_penalty_amount > 0){
            $tdocDetails = $this->getTfocDetails($cashierDetails->sef_penalty_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0;    
            $commonIncomeData['tfoc_id'] = $cashierDetails->sef_penalty_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->sef_penalty_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->sef_penalty_sl_id;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->sef_penalty_amount, 2, '.', '');
            $commonIncomeData['is_discount'] = 0;
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* SEF PNALITY DATA */

            /* SEF DISCOUNT DATA */
            if($cashierDetails->sef_discount_amount > 0){
            $tdocDetails = $this->getTfocDetails($cashierDetails->sef_discount_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0;
            $commonIncomeData['tfoc_id'] = $cashierDetails->sef_discount_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->sef_discount_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->sef_discount_sl_id;
            $commonIncomeData['is_discount'] = 1;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->sef_discount_amount, 2, '.', '');
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* SEF DISCOUNT DATA */

            /* SH BASIC DATA */
            if($cashierDetails->sh_amount > 0){
            $tdocDetails = $this->getTfocDetails($cashierDetails->sh_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0;
            $commonIncomeData['tfoc_id'] = $cashierDetails->sh_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->sh_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->sh_sl_id;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->sh_amount, 2, '.', '');
            $commonIncomeData['is_discount'] = 0;
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* SH BASIC DATA */

            /* SH PENALITY DATA */
            if($cashierDetails->sh_penalty_amount > 0){
            $tdocDetails = $this->getTfocDetails($cashierDetails->sh_penalty_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0;
            $commonIncomeData['tfoc_id'] = $cashierDetails->sh_penalty_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->sh_penalty_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->sh_penalty_sl_id;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->sh_penalty_amount, 2, '.', '');
            $commonIncomeData['is_discount'] = 0;
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* SH PENALITY DATA */

            /* SH DISCOUNT DATA */
            if($cashierDetails->sh_discount_amount > 0){
            $tdocDetails = $this->getTfocDetails($cashierDetails->sh_discount_tfoc_id);
            $commonIncomeData['fund_id'] = (isset($tdocDetails->fund_id))?$tdocDetails->fund_id:0;
            $commonIncomeData['tfoc_id'] = $cashierDetails->sh_discount_tfoc_id; 
            $commonIncomeData['gl_account_id'] = $cashierDetails->sh_discount_gl_id;
            $commonIncomeData['sl_account_id'] = $cashierDetails->sh_discount_sl_id;
            $commonIncomeData['is_discount'] = 1;
            $commonIncomeData['amount'] = number_format((float)$cashierDetails->sh_discount_amount, 2, '.', '');
            $dataToSaveInIncomeTable[] = $commonIncomeData;
            }
            /* SH DISCOUNT DATA */
        //dd($dataToSaveInIncomeTable);
            /* Finally Insert Data in income table */
            foreach ($dataToSaveInIncomeTable as $finalData) {
                $this->addDataIncomeDetails($finalData);
            }
            /* Finally Insert Data in income table */
        }
    }

    
    public function checkCreditFacilityExist($pKind){
        $data =  DB::table('cto_tax_credit_management')
                  ->select('id','tcm_gl_id','tcm_sl_id')
                  ->where('pcs_id',2);
                  if($pKind != 0){
                   $data->where('rpt_category',$pKind);
                  }
        $newData = $data->where('tcm_status',1)->orderby('id','DESC')->first();
        return $newData;
    }
    public function getPreviousIssueNumber(){
        return DB::table('cto_cashier')->select('cashier_issue_no')->where('tfoc_is_applicable',1)->where('cashier_year',date("Y"))->orderby('id','DESC')->first();
    }
    public function updateOrUsed($id,$columns){
        return DB::table('cto_payment_or_assignments')->where('ortype_id',$id)->where('ora_is_completed','0')->orderby('id','ASC')->where('ora_is_active',1)->limit(1)->update($columns);
    }
    public function getTopTransAssessmentIds($id){
        return DB::table('cto_top_transactions as ctt')->join('cto_top_bplo as ctb','ctb.id', '=', 'ctt.transaction_ref_no')->select('ctb.final_assessment_ids')->where('ctt.id','=',(int)$id)->first();
    }
    public function getFundCode(){
         return DB::table('acctg_fund_codes')->select('id','code')->get()->toArray();
    }
    public function getBankList(){
        return DB::table('cto_payment_banks')->select('id','bank_code')->get()->toArray();
    }
    public function getTfocDtls($id){
        return DB::table('cto_tfocs')->select('gl_account_id','sl_id')->where('id',$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }
    public function updateTopTransaction($id,$columns){
        return DB::table('cto_top_transactions')->where('id',$id)->update($columns);
    }
    public function updateFinalAssessment($ids,$columns){
        return DB::table('cto_bplo_final_assessment_details')->whereIn('id',$ids)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function checkRecordIsExist($tfocid,$Cashierid){
         return DB::table('cto_cashier_details')->select('id')->where('tfoc_id','=',$tfocid)->where('cashier_id',$Cashierid)->get();
    }
    public function checkCBBillingRecordIsExist($cbcode,$Cashierid){
         return DB::table('cto_cashier_real_properties')->select('id')->where('cb_code','=',$cbcode)->where('cashier_id',$Cashierid)->get();
    } 

    public function checkCBBillingDetailsRecordIsExist($cbcode,$Cashierid){
         return DB::table('cto_cashier_details')->select('id')->where('cbd_code','=',$cbcode)->where('cashier_id',$Cashierid)->get();
    }
    public function updateCashierDetailsData($id,$columns){
        return DB::table('cto_cashier_details')->where('id',$id)->update($columns);
    }
    public function addCashierDetailsData($postdata){
        //for Cash Reciepts tresury
        /*$cashier =DB::table('cto_cashier')->where('id', $postdata['cashier_id'])->first();

        if ($postdata['basic_discount_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['basic_discount_tfoc_id'], $postdata['basic_discount_amount'], $cashier->or_no, $cashier->cashier_particulars, 0);//basic discount
        }
        if ($postdata['basic_penalty_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['basic_penalty_tfoc_id'], $postdata['basic_penalty_amount'], $cashier->or_no, $cashier->cashier_particulars);//basic penalty
        }
        CtoCashReciept::insertCashReceipt($postdata['basic_tfoc_id'], $postdata['basic_amount'], $cashier->or_no, $cashier->cashier_particulars);//basic

        
        if ($postdata['sef_discount_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['sef_discount_tfoc_id'], $postdata['sef_discount_amount'], $cashier->or_no, $cashier->cashier_particulars, 0);//sef discount
        }
        if ($postdata['sef_penalty_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['sef_penalty_tfoc_id'], $postdata['sef_penalty_amount'], $cashier->or_no, $cashier->cashier_particulars);//sef penalty
        }
        if ($postdata['sef_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['sef_tfoc_id'], $postdata['sef_amount'], $cashier->or_no, $cashier->cashier_particulars);//sef
        }

        
        if ($postdata['sh_discount_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['sh_discount_tfoc_id'], $postdata['sh_discount_amount'], $cashier->or_no, $cashier->cashier_particulars, 0);//sh discount
        }
        if ($postdata['sh_penalty_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['sh_penalty_tfoc_id'], $postdata['sh_penalty_amount'], $cashier->or_no, $cashier->cashier_particulars);//sh penalty
        }
        if ($postdata['sh_amount'] > 0) {
            CtoCashReciept::insertCashReceipt($postdata['sh_tfoc_id'], $postdata['sh_amount'], $cashier->or_no, $cashier->cashier_particulars);//sh
        }*/
        // end
        DB::table('cto_cashier_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function updateCashierDetailsRealPropertyData($id,$columns){
        return DB::table('cto_cashier_real_properties')->where('id',$id)->update($columns);
    }
    public function addCashierDetailsRealPropertyData($postdata){
        DB::table('cto_cashier_real_properties')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function updateCashierPaymentData($id,$columns){
        return DB::table('cto_cashier_other_payments')->where('id',$id)->update($columns);
    }
    public function updateBusinessStatus($id,$columns){
        return DB::table('bplo_business')->where('id',$id)->update($columns);
    }
    public function addCashierPaymentData($postdata){
        DB::table('cto_cashier_other_payments')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    public function getSubClassDtls($id){
        return DB::table('psic_subclasses')->select('section_id','division_id','group_id','class_id')->where('id',$id)->first();
    }
    public function getCancelReason(){
      return DB::table('cto_payment_or_cancel_reasons')->select('id','ocr_reason')->where('ocr_is_active','1')->orderby('ocr_reason', 'ASC')->get();
    } 
    public function getChequeTypes(){
      return DB::table('check_type_masters')->select('id','ctm_description')->where('is_active','1')->orderby('ctm_description', 'ASC')->get();
    } 
    public function getEditDetails($id){
        return DB::table('cto_cashier')
                   ->select('cto_cashier.*','t2.tax_credit_amount as creditApplied','t2.or_no as previousOr','cto_cashier.cashier_or_date as previousOrDate','ctt.transaction_no')
                   ->leftJoin('cto_cashier_real_properties as cdrp','cdrp.cashier_id','=','cto_cashier.id')
                   ->leftJoin('cto_cashier_real_properties as t2','t2.id','=','cdrp.previous_cashier_id')
                   ->join('cto_top_transactions as ctt','ctt.id','=','cto_cashier.top_transaction_id')
                   ->where('cto_cashier.id',$id)
                   //->where('cdrp.tax_credit_is_useup',1)
                   ->first();
    }
    public function getCasheirDetails($id){
        return DB::table('cto_cashier_details')->select('cashier_year','tfoc_id','tfc_amount','ctc_taxable_amount')->where('cashier_id',$id)->orderby('id', 'ASC')->get();
    }
    public function getPaymentModeDetails($id,$p_type){
        return DB::table('cto_cashier_other_payments as ccop')
        ->select('ccop.*','afc.code as fundText','cpb.bank_code as bankText','ctm.ctm_description as chequeText')
        ->where('ccop.cashier_id',$id)
        ->leftJoin('acctg_fund_codes as afc','afc.id','=','ccop.fund_id')
        ->leftJoin('cto_payment_banks as cpb','cpb.id','=','ccop.bank_id')
        ->leftJoin('check_type_masters as ctm','ctm.id','=','ccop.check_type_id')
        ->where('ccop.payment_terms',(int)$p_type)
        ->orderby('ccop.id', 'ASC')->get();
    }
    public function deleteOtherPaymentMode($p_type,$Cashierid){
        DB::table('cto_cashier_other_payments')
        ->where('payment_terms','!=',(int)$p_type)
        ->where('cashier_id',(int)$Cashierid)->delete();
    }

    public function getCertificateDetails($id){
        $query = DB::table('cto_cashier AS cc')
        ->join('users AS u', 'u.id', '=', 'cc.created_by') 
        ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
            ->select('cc.*','c.rpo_first_name','c.rpo_custom_last_name','c.p_tin_no','c.icr_no','c.weight','c.birth_place','c.country','c.gender','c.dateofbirth','c.full_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.rpo_address_subdivision','u.name as username')->where('cc.id',$id)->first();
        return $query;
    }
    public function GetPaymentbankdetails($id){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','2')->orderby('id', 'ASC')->get();
    }

    public function GetPaymentcheckdetails($id){
        return DB::table('cto_cashier_other_payments')->select('*')->where('cashier_id',$id)->where('payment_terms','3')->orderby('id', 'ASC')->get();
    }

    public function GetReqiestfees($id)
    {
        return DB::table('cto_cashier_details AS cc')->join('acctg_account_subsidiary_ledgers AS acctg', 'acctg.id', '=', 'cc.sl_id')->select('cc.*','cc.tfc_amount as tax_amount','acctg.description as fees_description')->where('cc.cashier_id',$id)->get();
    }

    public function GetOrtypeid($id){
        return DB::table('cto_payment_or_type_details')->select('ortype_id')->where('pcs_id',$id)->first();
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
        $barangy = $request->input('barangay');
        $request->session()->put('cashierRealPropertySelectedBrgy',$barangy);
        $columns = array( 
          1 =>"full_name",
          2 =>"top_no",
          3 =>"cc.or_no",
          4 =>"cc.total_paid_amount",
          5 =>"cc.tax_credit_amount",
          6 =>"cc.created_at",
          7 =>"username",
          8 =>"cc.payment_terms",
          9 =>"cc.status",
        );
        $sql = DB::table('cto_cashier AS cc')
            ->leftJoin('cto_cashier_real_properties AS ccrp', function($join){
                $join->on('ccrp.cashier_id', '=', 'cc.id')->where('ccrp.tax_credit_amount','>',0);
            })
            ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id') 
            ->join('users AS u', 'u.id', '=', 'cc.created_by') 
            ->join('cto_top_transactions AS top', 'top.id', '=', 'cc.top_transaction_id') 
            ->select('cc.id','cc.payment_terms','cc.total_paid_amount','cc.cashier_or_date','cc.or_no','cc.status','cc.created_at','c.full_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'top.transaction_no as top_no','ccrp.tax_credit_amount','u.name as username'); 
            if ($status == '3') {
            } else {
                   $sql->where('cc.status', '=', (int)$status);
            }
        $sql->where('cc.tfoc_is_applicable','=','2');     
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")   
                ->orWhere('cc.or_no',$q)
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
                ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(total_paid_amount)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cc.tax_credit_amount)'),'like',"%".strtolower($q)."%");
            });
        }
        if(!empty($startdate) && isset($startdate)){
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cc.created_at','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
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


    public function getTaxCollectionList($request,$useForAnotherFunction = false){
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
        $barangy = $request->input('barangay');
        $request->session()->put('cashierRealPropertySelectedBrgy',$barangy);

        $allPenalty = 'SUM(COALESCE(cbdp.basic_penalty_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0))';

        $advanceYearBasicTaxes = 'CASE 
        WHEN cbd.cbd_covered_year > YEAR(CURDATE()) THEN (SUM(COALESCE(cbd.basic_amount,0))+SUM(COALESCE(cbd.sef_amount,0))+SUM(COALESCE(cbd.sh_amount,0))) ELSE 0 
        END';

        $advanceYearPenalties = 'CASE 
        WHEN cbd.cbd_covered_year > YEAR(CURDATE()) THEN (SUM(COALESCE(cbdp.basic_penalty_amount,0))+SUM(COALESCE(cbdp.sef_penalty_amount,0))+SUM(COALESCE(cbdp.sh_penalty_amount,0))) ELSE 0 
        END';

        $advanceYearDiscounts = 'CASE 
        WHEN cbd.cbd_covered_year > YEAR(CURDATE()) THEN (SUM(COALESCE(cbdd.basic_discount_amount,0))+SUM(COALESCE(cbdd.sef_discount_amount,0))+SUM(COALESCE(cbdd.sh_discount_amount,0))) ELSE 0 
        END';

        $currentYearBasicTaxes = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE()) THEN SUM(COALESCE(cbd.basic_amount,0)) ELSE 0 
        END';
        $currentYearSefTaxes = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE()) THEN SUM(COALESCE(cbd.sef_amount,0)) ELSE 0 
        END';
        $currentYearShtTaxes = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE()) THEN SUM(COALESCE(cbd.sh_amount,0)) ELSE 0 
        END';

        $currentYearBasicDiscount = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE()) THEN SUM(COALESCE(cbdd.basic_discount_amount,0)) ELSE 0 
        END';
        $currentYearSefDiscount = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE()) THEN SUM(COALESCE(cbdd.sef_discount_amount,0)) ELSE 0 
        END';
        $currentYearShtDiscount = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE()) THEN SUM(COALESCE(cbdd.sh_discount_amount,0)) ELSE 0 
        END';

        $previousYearBasicTaxes = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE())-1 THEN SUM(COALESCE(cbd.basic_amount,0)) ELSE 0 
        END';
        $previousYearSefTaxes = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE())-1 THEN SUM(COALESCE(cbd.sef_amount,0)) ELSE 0 
        END';
        $previousYearShtTaxes = 'CASE 
        WHEN cbd.cbd_covered_year = YEAR(CURDATE())-1 THEN SUM(COALESCE(cbd.sh_amount,0)) ELSE 0 
        END';

        $priorYearBasicTaxes = 'CASE 
        WHEN cbd.cbd_covered_year < YEAR(CURDATE())-1 THEN SUM(COALESCE(cbd.basic_amount,0)) ELSE 0 
        END';
        $priorYearSefTaxes = 'CASE 
        WHEN cbd.cbd_covered_year < YEAR(CURDATE())-1 THEN SUM(COALESCE(cbd.sef_amount,0)) ELSE 0 
        END';
        $priorYearShtTaxes = 'CASE 
        WHEN cbd.cbd_covered_year < YEAR(CURDATE())-1 THEN SUM(COALESCE(cbd.sh_amount,0)) ELSE 0 
        END';

        $taxCredit = 'SUM(COALESCE(ccrp.tax_credit_amount,0))';
        $columns = array( 
          1 => "cc.cashier_or_date",
          2 => "cc.or_no",
          3 => "c.full_name",
          4 => "cbd.cbd_assessed_value",
          5 => DB::raw($currentYearBasicTaxes.'-'.$currentYearBasicDiscount),
          6 => DB::raw($previousYearBasicTaxes),
          7 => DB::raw($priorYearBasicTaxes),
          8 => DB::raw($currentYearSefTaxes.'-'.$currentYearSefDiscount),
          9 => DB::raw($previousYearSefTaxes),
          10 => DB::raw($priorYearSefTaxes),
          11 => DB::raw($currentYearShtTaxes.'-'.$currentYearShtDiscount),
          12 => DB::raw($previousYearShtTaxes),
          13 => DB::raw($priorYearShtTaxes),
          14 => DB::raw($allPenalty),
          15 => DB::raw($taxCredit),
          16 =>  DB::raw($advanceYearBasicTaxes.'+'.$advanceYearPenalties.'-'.$advanceYearDiscounts),
          17 => 'cc.total_paid_amount'
        );
        $sql = DB::table('cto_cashier AS cc')
            ->leftJoin('cto_cashier_real_properties AS ccrp', function($join){
                $join->on('ccrp.cashier_id', '=', 'cc.id');
            })
            ->join('clients as c','c.id','=','cc.client_citizen_id')
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
                'cc.id',
                'cc.total_paid_amount',
                'cc.cashier_or_date',
                'cc.or_no',
                'cc.status',
                'cc.created_at',
                'c.full_name',
                'ccrp.tax_credit_amount',
                'cbd.cbd_assessed_value',
                DB::raw($currentYearBasicTaxes.'-'.$currentYearBasicDiscount.' as currentYearBasicTax'),
                DB::raw($currentYearSefTaxes.'-'.$currentYearSefDiscount.' as currentYearSefTax'),
                DB::raw($currentYearShtTaxes.'-'.$currentYearShtDiscount.' as currentYearShtTax'),

                DB::raw($previousYearBasicTaxes.' as previousYearBasicTax'),
                DB::raw($previousYearSefTaxes.' as previousYearSefTax'),
                DB::raw($previousYearShtTaxes.' as previousYearShtTax'),

                DB::raw($priorYearBasicTaxes.' as priorYearBasicTaxes'),
                DB::raw($priorYearSefTaxes.' as priorYearSefTaxes'),
                DB::raw($priorYearShtTaxes.' as priorYearShtTaxes'),

                DB::raw($allPenalty.' as penalty'),

                DB::raw($advanceYearBasicTaxes.'+'.$advanceYearPenalties.'-'.$advanceYearDiscounts.' as advancePayment'),

                DB::raw($taxCredit.' as taxCredit')
            )
            ->where('cc.tfoc_is_applicable',2)
            ->where('cc.status',1)
            ->groupBy('cc.id'); 

        /*if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw("CONCAT(rpo_first_name, ' ', COALESCE(rpo_middle_name, ''), ' ',rpo_custom_last_name)"), 'LIKE', "%{$q}%")   
                ->orWhere('cc.or_no',$q)
                ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(total_paid_amount)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cc.tax_credit_amount)'),'like',"%".strtolower($q)."%");
            });
        }*/
        if(!empty($startdate) && isset($startdate)){
            $startdate = date('Y-m-d',strtotime($startdate)); 
            $sql->whereDate('cc.cashier_or_date','>=',trim($startdate));  
        }
        if(!empty($enddate) && isset($enddate)){
            $enddate = date('Y-m-d',strtotime($enddate)); 
            $sql->whereDate('cc.cashier_or_date','<=',trim($enddate));  
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('cc.created_at','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=count($sql->get());
        //dd($data_cnt);
        /*  #######  Set Offset & Limit  ###### */
        if(!$useForAnotherFunction){
            $sql->offset((int)$params['start'])->limit((int)$params['length']);
        }else{
            $sql->limit($data_cnt);
        }
        
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getDataTodownloadExcel($value='')
    {
        // code...
    }

    public function getTopTransactionDtls($id){
        return DB::table('cto_top_transactions as ctt')
            ->join('rpt_cto_billings as ctb','ctb.id', '=', 'ctt.transaction_ref_no')
            /*->join('bplo_business AS bb', 'bb.id', '=', 'ctb.busn_id')*/ 
            ->join('clients AS c', 'c.id', '=', 'ctb.rpo_code') 
            ->select('ctt.id','ctt.transaction_no','c.full_name',DB::raw("CONCAT(rpo_first_name,' ',rpo_middle_name,' ',rpo_custom_last_name) as ownar_name"),'c.id as clientId')
            ->where('ctt.id','=',(int)$id)  
            ->first();
    }
    public function getAssessmentDetails($bus_id,$final_assessment_ids){
        return DB::table('cto_bplo_final_assessment_details')
            ->select(DB::raw("GROUP_CONCAT(assessment_details_ids) AS assessment_details_ids"))
            ->whereIn('id',$final_assessment_ids)
            ->first()->assessment_details_ids;
    }
    public function getFinalAssessmentDetails($assessment_details_ids){
        return DB::table('cto_bplo_assessment_details AS ass')
            ->Join('acctg_account_subsidiary_ledgers AS sl', 'ass.sl_id', '=', 'sl.id')
            ->select('ass.assess_year',DB::raw('SUM(ass.tfoc_amount) AS tfoc_amount'),DB::raw('SUM(surcharge_fee) AS surcharge_fee') ,DB::raw('SUM(interest_fee) AS interest_fee'),'sl.description','payment_mode','assessment_period','surcharge_sl_id','interest_sl_id','subclass_id','tfoc_id','agl_account_id')
            ->whereIn('ass.id',$assessment_details_ids)
            ->groupBy('ass.assess_year','ass.cb_assesment_id')
            ->orderBy('ass.id','ASC')->get()->toArray();
    }

    public function updateAccountReceibables($id = '',$paid = true){
        $propObj = new RptProperty;
        $receiveableObj = new AccountRreceivablesPropertyController;

        $billingDetails = DB::table('cto_cashier_details as ccd')
                             ->select('ccd.cbd_code','cc.top_transaction_id','ccd.cb_code','cc.ortype_id','cc.or_assignment_id','cc.or_register_id','cc.coa_no','cc.or_no','cbd.sd_mode','cbd.cbd_covered_year','ctt.transaction_no','cbd.rp_code as id','cbd.rp_property_code','cbd.tax_revenue_year','ccd.pk_id')
                             ->join('cto_cashier as cc','cc.id','=','ccd.cashier_id')
                             ->join('rpt_cto_billing_details as cbd','cbd.id','=','ccd.cbd_code')
                             ->join('cto_top_transactions as ctt','ctt.id','=','cc.top_transaction_id')
                          ->where('ccd.cashier_id',$id)->get();
                           //dd($billingDetails);
        //dd($billingDetails);
        foreach ($billingDetails as $key => $value) {
            $receiableDetailsSetupBas = $receiveableObj->_accountreceivables->getSetupDetails($value->pk_id,1);
            $receiableDetailsSetupsef = $receiveableObj->_accountreceivables->getSetupDetails($value->pk_id,2);
            $receiableDetailsSetupsht = $receiveableObj->_accountreceivables->getSetupDetails($value->pk_id,3);
            $dataToUpdateInMain = [
                'top_transaction_id' => ($paid)?$value->top_transaction_id:0,
                'rp_last_cashier_id' => ($paid)?$id:0
            ];
            //dd($dataToUpdateInMain);
            DB::table('cto_accounts_receivables')->where('rp_code',$value->id)->update($dataToUpdateInMain);
            $receiveAbleData = DB::table('cto_accounts_receivables')->where('rp_code',$value->id)->first();
            if($receiveAbleData != null){
            if($value->tax_revenue_year != 1){
            $dataToUpdateInChildTable = [
                'top_transaction_id' => ($paid)?$value->top_transaction_id:0,
                'rp_billing_id' => ($paid)?$value->cb_code:0,
                'transaction_id' => ($paid)?$value->top_transaction_id:0,
                'transaction_no' => ($paid)?$value->transaction_no:0,
                'cbd_is_paid' => ($paid)?1:0,
                'cashier_id' => ($paid)?$id:0,
                'ortype_id' => ($paid)?$value->ortype_id:0,
                'or_assignment_id' => ($paid)?$value->or_assignment_id:0,
                'or_register_id' => ($paid)?$value->or_register_id:0,
                'coa_no' => ($paid)?$value->coa_no:0,
                'or_no' => ($paid)?$value->or_no:0,
                'basic_ar_gl_id' => (isset($receiableDetailsSetupBas->gl_id))?$receiableDetailsSetupBas->gl_id:0,
                'basic_ar_sl_id' => (isset($receiableDetailsSetupBas->sl_id))?$receiableDetailsSetupBas->sl_id:0,
                'sef_ar_gl_id' => (isset($receiableDetailsSetupsef->gl_id))?$receiableDetailsSetupsef->gl_id:0,
                'sef_ar_sl_id' => (isset($receiableDetailsSetupsef->sl_id))?$receiableDetailsSetupsef->sl_id:0,
                'sh_ar_gl_id' => (isset($receiableDetailsSetupsht->gl_id))?$receiableDetailsSetupsht->gl_id:0,
                'sh_ar_sl_id' => (isset($receiableDetailsSetupsht->sl_id))?$receiableDetailsSetupsht->sl_id:0,
            ];
            if($value->sd_mode == 14){
                DB::table('cto_accounts_receivable_details')
                        ->where('rp_property_code',$value->rp_property_code)
                        ->where('ar_id',$receiveAbleData->id)
                        ->where('ar_covered_year',$value->cbd_covered_year)
                        ->update($dataToUpdateInChildTable);
            }else{
                $dataToUpdateInChildTable['cbd_is_paid'] = 2;
                DB::table('cto_accounts_receivable_details')
                        ->where('rp_property_code',$value->rp_property_code)
                        ->where('ar_id',$receiveAbleData->id)
                        ->where('ar_covered_year',$value->cbd_covered_year)
                        ->where('sd_mode',$value->sd_mode)
                        ->update($dataToUpdateInChildTable);

            }
            $prop = DB::table('cto_accounts_receivables')->select('rp_code as id','id as arcId','rp_property_code','rp_code_chain')->where('rp_code',$value->id)->first();
            $receiveableObj->updateDeliquency($prop);
        }
        }else{
            $year = date("Y")+1;
            if($paid){
                $allModes = Helper::billing_quarters();
                $sdModes = array_keys($allModes);
                $prop = DB::table('cto_accounts_receivables as acr')
                ->select('rp.rp_app_effective_year','rp.id','acr.id as arcId','acr.last_updated','acr.top_transaction_id','acr.payee_type','acr.taxpayer_id','acr.pcs_id','acr.rp_property_code','acr.rp_code','acr.pk_id','acr.rvy_revision_year_id','acr.brgy_code_id','acr.rp_assessed_value','acr.rp_last_cashier_id','acr.rp_code_chain','acr.created_by','acr.updated_by')
                ->where('acr.rp_property_code',$value->rp_property_code)
                ->join('rpt_properties as rp','rp.id','=','acr.rp_code')
                ->first();
                if($prop != null){
                foreach ($sdModes as $key => $mode) {
                    if($value->sd_mode == 14 || $value->sd_mode == $mode){
                 $receiveableObj = new AccountRreceivablesPropertyController;
                 $taxDetails = $propObj->calculatePenaltyFee($prop->id,$year,$mode);
                 $taxDetails['coveredYear'] = $year;
                 $taxDetails['cbd_is_paid'] = 1;
                 $checkRecordExistsOrNot = DB::table('cto_accounts_receivable_details')->where('rp_property_code',$value->rp_property_code)->where('cbd_is_paid',1)->where('ar_covered_year',$year)->where('sd_mode',$mode)->where('tax_revenue_year',1)->first();
                 if($checkRecordExistsOrNot == null){
                    $dataToSaveInDetails = $receiveableObj->arrangeDataForChildTable($prop,$taxDetails,1,$mode);     
                    $dataToSaveInDetails['top_transaction_id'] = $value->top_transaction_id;
                    $dataToSaveInDetails['rp_billing_id'] = $value->cb_code;
                    $dataToSaveInDetails['transaction_id'] = $value->top_transaction_id;
                    $dataToSaveInDetails['transaction_no'] = $value->transaction_no;
                    $dataToSaveInDetails['cbd_is_paid'] = 1;
                    $dataToSaveInDetails['cashier_id'] = $id;
                    $dataToSaveInDetails['ortype_id'] = $value->ortype_id;
                    $dataToSaveInDetails['or_assignment_id'] = $value->or_assignment_id;
                    $dataToSaveInDetails['or_register_id'] = $value->or_register_id;
                    $dataToSaveInDetails['coa_no'] = $value->coa_no;
                    $dataToSaveInDetails['or_no'] = $value->or_no;
                    $receiveableObj->_accountreceivables->addDataInDetails($dataToSaveInDetails);
                 } 
             }
            }

            $receiveableObj->updateDeliquency($prop);
        }
            }else{
                 DB::table('cto_accounts_receivable_details')->where('rp_property_code',$value->rp_property_code)->where('cbd_is_paid',1)->where('ar_covered_year',$year)->delete();
            }

        }
        }


        
    }
}
