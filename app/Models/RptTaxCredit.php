<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptTaxCredit extends Model
{ 
    use HasFactory;
    public function updateData($id,$columns){
        return DB::table('cto_cashier')->where('id',$id)->update($columns);
    }

    public function standardQuery($id = 0){
        $completeSqlQuery = DB::table('cto_cashier AS cc')
          ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
          ->join('cto_top_transactions AS top', 'top.id', '=', 'cc.top_transaction_id')
          ->join('cto_cashier_real_properties as ccrp','ccrp.cashier_id','=','cc.id')
          ->leftJoin('rpt_properties AS rp', 'rp.id', '=', 'ccrp.rp_code')
          ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
          ->join('rpt_revision_year AS ry', 'ry.id', '=', 'rp.rvy_revision_year_id')
          ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
          ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
          ->leftJoin('rpt_building_floor_values','rpt_building_floor_values.rp_code','=','rp.id')
          ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
          ->leftjoin('users as u','cc.created_by','=','u.id')
          ->leftjoin('cto_payment_or_types as ort','ort.id','=','cc.ortype_id')
          ->select(
            'rp.rp_assessed_value',
            'pk.pk_code',
            'cc.id',
            'cc.payment_terms',
            'cc.previous_cashier_id',
            'cc.cashier_or_date',
            'cc.tfoc_is_applicable',
            'cc.or_no',
            'cc.total_amount',
			'cc.total_paid_amount',
            'cc.created_at',
            'ccrp.tax_credit_is_useup',
            db::raw('COALESCE(ccrp.tax_credit_amount,0) as tax_credit_amount_new'),
            db::raw('COALESCE(ccrp.additional_credit_amount,0) as additional_credit_amount'), 
            DB::raw('(COALESCE(ccrp.tax_credit_amount,0)+COALESCE(ccrp.additional_credit_amount,0)) as tax_credit_amount'),
			'c.full_name',
            'c.rpo_first_name',
            'c.rpo_custom_last_name',
            'c.rpo_middle_name',
            'c.rpo_address_house_lot_no',
            'ccrp.tax_credit_gl_id',
            'ccrp.tax_credit_sl_id',
            'rp.rp_td_no',
            'bgy.brgy_code',
            'bgy.brgy_name',
            'top.transaction_no',
            'top.id as txnId',
            'ry.rvy_revision_year',
            'u.name as cashier',
            DB::raw("CONCAT(ry.rvy_revision_year,'-',bgy.brgy_code,'-',rp.rp_td_no) as taxDeclarationNo"),
            DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpb_assessed_value))
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
                            'ccrp.id as ccrpid',
                            'cc.cashier_or_date',
                            'cc.tfoc_is_applicable',
                            'cc.or_no',
                            'cc.total_amount',
							'cc.total_paid_amount',
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
        //dd($id);
      return DB::table('cto_cashier_real_properties AS ccrp')
        ->join('cto_cashier as cc','cc.id','=','ccrp.cashier_id')
        ->leftjoin('users as u','cc.created_by','=','u.id')
        ->select('cc.or_no','u.name as cashier','cc.tax_credit_amount','cc.cashier_or_date','cc.total_amount','cc.tax_credit_gl_id','cc.tax_credit_sl_id')
        ->where('ccrp.previous_cashier_id',$id)
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

    if(!isset($params['start']) && !isset($params['length'])){
      $params['start']="0";
      $params['length']="10";
    }

    $columns = array( 
      0 =>"cc.id",
      1 =>DB::raw("CONCAT(ry.rvy_revision_year,'-',bgy.brgy_code,'-',rp.rp_td_no)"),
      2 =>"c.full_name",
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
      7=>'top.transaction_no',
      8=>'cc.or_no',
      9=>'cc.total_paid_amount',
      10=>'cc.cashier_or_date',
      11=>'ccrp.tax_credit_amount',
      12=>'',
      13=>DB::raw("CASE 
                WHEN cc.payment_terms = '1' THEN 'Cash' 
                WHEN cc.payment_terms = '2' THEN 'Bank'
                WHEN cc.payment_terms = '3' THEN 'Check'
                WHEN cc.payment_terms = '4' THEN 'Credit Card'
                WHEN cc.payment_terms = '5' THEN 'Online Payment'
                END"),
      14=>'cc.tax_credit_is_useup'
    );

    $sql = $this->standardQuery();
    $sql->groupBy('cc.id');
    if($businessid){
      $sql->where('rp.brgy_code_id','=',$businessid); 
    }
     $sql->where('cc.tfoc_is_applicable','=','2'); 
    
	 $sql->where(function ($sql){
			$sql->where('ccrp.tax_credit_amount','>','0');					
			$sql->orWhere('ccrp.additional_credit_amount','>','0');   
     });   
     if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(cc.total_paid_amount)'),'like',"%".strtolower($q)."%")
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
        /*  #######  Set Order By  ###### */
    if(isset($params['order'][0]['column']))
      $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
    else
      $sql->orderBy('cc.id','DESC');

    /*  #######  Get count without limit  ###### */
    $data_cnt=$sql->count();
    /*  #######  Set Offset & Limit  ###### */
    $sql->offset((int)$params['start'])->limit((int)$params['length']);
    $data=$sql->get();
    return array("data_cnt"=>$data_cnt,"data"=>$data);
    }
}
