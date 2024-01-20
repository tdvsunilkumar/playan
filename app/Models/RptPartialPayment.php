<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptPartialPayment extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }

    public function standardQuery($id = 0){
        $completeSqlQuery = DB::table('cto_cashier AS cc')
          ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->leftjoin('cto_cashier_details as ccd',function($join){
                                    $join->on('ccd.cashier_id','=','cc.id')
                                         ->where('ccd.tfoc_is_applicable',2);
                                })
          ->leftjoin('rpt_cto_billing_details as cbd',function($joina){
                                    $joina->on('cbd.id','=','ccd.cbd_code')->where('cbd.sd_mode','!=',14);
                                })
          ->leftJoin('rpt_properties AS rp', 'rp.id', '=', 'cbd.rp_code')
          ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
          ->join('rpt_revision_year AS ry', 'ry.id', '=', 'rp.rvy_revision_year_id')
          ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
          ->leftJoin('rpt_building_floor_values','rpt_building_floor_values.rp_code','=','rp.id')
          ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
          ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
          ->select(
            'rp.rp_tax_declaration_no',
            'rp.id as propertyId',
            'pk.pk_code',
            'cc.id',
            'cc.payment_terms',
            'cc.previous_cashier_id',
            'cc.cashier_or_date',
            'cc.tfoc_is_applicable',
            'cc.or_no',
            'cc.total_amount',
            'cc.created_at',
            'cbd.id',
            'cbd.cb_code',
            'cc.total_paid_amount',
            DB::raw('MIN(cbd.cbd_covered_year) as startYear'),
            DB::raw('MAX(cbd.cbd_covered_year) as endYear'),
            DB::raw('MIN(cbd.sd_mode) as startQtr'),
            DB::raw('MAX(cbd.sd_mode) as endQtr'),
            'c.full_name',
           /* 'c.rpo_custom_last_name',
            'c.rpo_middle_name',
            'c.rpo_address_house_lot_no',*/
            'rp.rp_td_no',
            'bgy.brgy_code',
            'bgy.brgy_name',
            'ry.rvy_revision_year',
            DB::raw("CASE 
            WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
            WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END as customername
            "),
            DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rp.rpb_assessed_value))
                WHEN pk.pk_code = 'M' THEN SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value))
                END as assessedValue"),
            DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.lav_unit_measure 
                WHEN pk.pk_code = 'B' THEN ''
                WHEN pk.pk_code = 'M' THEN ''
                END as unitMeasure"),
            DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                END as propertyClass"),
            DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpbfv_floor_area))
                WHEN pk.pk_code = 'M' THEN 0
                END as area"),
            DB::raw("(SELECT pc_class_code FROM rpt_property_classes WHERE id = propertyClass) as propertyClass")
        );
          if($id > 0){
            $completeSqlQuery->where('cc.id',$id);
          }
          return $completeSqlQuery;
    }
    public function addData($postdata){
        DB::table('cto_cashier')->insert($postdata);
         return DB::getPdo()->lastInsertId();
    }
    public function getdetails($id){
            $query = DB::table('cto_cashier AS cc')
                         ->select(
                            'cc.id',
                            'cc.payment_terms',
                            'cc.previous_cashier_id',
                            'cc.cashier_or_date',
                            'cc.tfoc_is_applicable',
                            'cc.or_no',
                            'cc.total_amount',
                            'cc.created_at',
                            'ccrp.tax_credit_is_useup',
                            db::raw('COALESCE(ccrp.tax_credit_amount,0) as tax_credit_amount_new'),
                            db::raw('COALESCE(ccrp.additional_credit_amount,0) as additional_credit_amount'),
                            DB::raw('(COALESCE(ccrp.tax_credit_amount,0)+COALESCE(ccrp.additional_credit_amount,0)) as tax_credit_amount'),
                            'ccrp.tax_credit_gl_id',
                            'ccrp.tax_credit_sl_id',
                            'top.id as txnId',
                            'u.name as cashier',
                         )
                         ->join('cto_cashier_real_properties as ccrp',function($j)use($id){
                            $j->on('ccrp.cashier_id','=','cc.id')
                              ->where('ccrp.tax_credit_amount','>',0)
                              ->orWhere('ccrp.additional_credit_amount','>',0)
                              ->where('ccrp.cashier_id',$id);
                         })
                         ->join('cto_top_transactions AS top', 'top.id', '=', 'cc.top_transaction_id')
                         ->leftjoin('users as u','cc.created_by','=','u.id')
                         ->where('cc.id',$id)
                         ->first();
            return $query;             
    }

    public function getdetailsutilized($id){
      return DB::table('cto_cashier AS cc')
        ->leftjoin('users as u','cc.created_by','=','u.id')
          ->select('cc.or_no','u.name as cashier','cc.tax_credit_amount','cc.cashier_or_date','cc.total_amount','cc.tax_credit_gl_id','cc.tax_credit_sl_id')
          ->where('cc.previous_cashier_id',$id)
          ->first();
    }

    public function GetBussinessids(){
      return DB::table('bplo_business')->select('id','busns_id_no','busn_name')->where('busns_id_no','<>',NULL)->get();
    }

    public function getDetailsrows($id){
       return DB::table('cto_cashier_details AS cc')
          ->select('cc.tfc_amount','cc.sl_id','cc.agl_account_id','cc.surcharge_fee','cc.interest_fee')->where('cc.cashier_id',$id)->get();
    }

    public function getDetailofEngDefault($id){
         return DB::table('cto_cashier_details_eng_occupancy')
          ->select('fees_description','tfc_amount')->where('cashier_id',$id)->get();
    }

    public function getAccountGeneralLeaderbyid($id,$glid){
        return DB::table('acctg_account_subsidiary_ledgers  AS aasl')
                     ->join('acctg_account_general_ledgers as aagl', 'aasl.gl_account_id', '=', 'aagl.id')
                     ->select(
                        'aasl.id',
                        'aagl.code',
                        'aagl.description as gldescription',
                        'aasl.prefix',
                        'aasl.description'
                        )
                     ->where('aagl.is_active',1)
                     ->where('aasl.is_parent',0)
                     ->where('aasl.is_hidden',0)
                     ->where('aasl.is_active',1)
                     ->where('aasl.id',$id)
                     ->where('aasl.gl_account_id',$glid)
                     ->first();
    }

    public function getList($request){
    $params = $columns = $totalRecords = $data = array();
    $params = $_REQUEST;
    $q=$request->input('q');
    $startdate =$request->input('fromdate');
    $enddate = $request->input('todate');
    $businessid = $request->input('businessid');
    $taxDecNo   = $request->input('tax_dec_no');

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>'rp.rp_tax_declaration_no',
      2 =>DB::raw("CASE 
            WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
            WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END
            "),
      3 =>"bgy.brgy_name",
      4 =>"pk.pk_code",   
      5 =>DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpbfv_floor_area))
                WHEN pk.pk_code = 'M' THEN 0
                END"),
      6=>DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpb_assessed_value))
                WHEN pk.pk_code = 'M' THEN SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value))
                END"),
      7=>'cc.total_amount',
      8=>DB::raw('MAX(cbd.cbd_covered_year)'),
      9=>'cc.or_no',
      10=>'cc.cashier_or_date',
      11=>'cc.net_tax_due_amount',
      12=>'ccrp.tax_credit_amount',
    );

    $sql = $this->standardQuery();
    $sql->groupBy('rp.id');
    if($businessid){
      $sql->where('rp.brgy_code_id','=',$businessid); 
    }
    if($taxDecNo){
      $sql->where('rp.id','=',$taxDecNo); 
    }
    
     $sql->where('cc.tfoc_is_applicable','=','2');  
       
     if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ry.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rp.rp_td_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pk.pk_code)'),'like',"%".strtolower($q)."%")
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
        $sql->where('cbd.sd_mode','<>',14);
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('cc.id','ASC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public  function getTaxdecwithName(){
      $query = DB::table('rpt_properties')->leftjoin('clients as c','rpt_properties.rpo_code','=','c.id')
        ->where('is_deleted',0)
        ->where('pk_is_active',1)
                 ->select('rpt_properties.id','rpt_properties.rp_tax_declaration_no','rpo_custom_last_name','rpo_first_name','rpo_middle_name')
                 ->get();            
        return $query;                
   }   
}
