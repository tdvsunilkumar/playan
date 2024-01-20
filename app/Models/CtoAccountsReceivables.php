<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoAccountsReceivables extends Model
{

    public function updateData($id,$columns, $updateCode = ''){
        return DB::table('cto_accounts_receivables')->where('id',$id)->update($columns);
    }

    public function addDataInDetails($columns){
        return DB::table('cto_accounts_receivable_details')->insert($columns);
    }

    public function updateDataInDetails($id,$columns){
        return DB::table('cto_accounts_receivable_details')->where('id',$id)->update($columns);
    }
    
    public function getMuncipality(){
      return DB::table('rpt_locality')->select('mun_no')->where('department','5')->first();
    }
    public function getBarangay($munid){
         return DB::table('barangays')
         ->select('id','brgy_code','brgy_name')->where('is_active',1)->where('mun_no',$munid)->get();
    }
    public function getTaxpayer(){
      return DB::table('clients')->select('id','rpo_custom_last_name','rpo_first_name','rpo_middle_name','suffix')->where('is_rpt',1)->get();
    }
    public function getTaxDecration(){
      return DB::table('rpt_properties AS rpt')
      ->join('clients AS c', 'c.id', '=', 'rpt.rpo_code')
      ->select('rpt.id','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.suffix','rpt.rp_tax_declaration_no')->where('c.is_rpt',1)->where('rpt.pk_is_active',1)->get();
    }
    public function getSetupDetails($pkId='', $arsCategory = ''){
        return DB::table('cto_accounts_receivable_setups')
        ->select('gl_id','sl_id','ars_fund_id')
        ->where('pk_id',$pkId)
        ->where('ars_category',$arsCategory)
        ->first();
    }
    public function getList($request){
    	  $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $barangay=$request->input('barangay');
        $taxpayer=$request->input('taxpayer');
        $year=$request->input('year');
        $tax=$request->input('tax');
        $kind=$request->input('kind');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $customerName = "CASE 
                 WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                 WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                 WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                 ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END";
        // $addtrss = "CASE 
        //     WHEN c.rpo_address_house_lot_no IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_street_name,''),', ',COALESCE(c.rpo_address_subdivision,''),', ',COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
        //     WHEN c.rpo_address_street_name IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_house_lot_no,''),', ',COALESCE(c.rpo_address_subdivision,''),', ',COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
        //     WHEN c.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_house_lot_no,''),', ',COALESCE(c.rpo_address_street_name,''))),',','')
        //     WHEN c.rpo_address_house_lot_no IS NULL AND c.rpo_address_street_name IS NULL AND c.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
        //     ELSE REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_house_lot_no,''),', ',COALESCE(c.rpo_address_street_name,''),', ',COALESCE(c.rpo_address_subdivision,''),', ',COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','') END";  
        $outBasic = '(COALESCE(car.outstand_basic_amount,0)+COALESCE(car.outstand_basic_interest,0)-COALESCE(car.outstand_basic_discount,0))';
        $outSef   = '(COALESCE(car.outstand_sef_amount,0)+COALESCE(car.outstand_sef_interest,0)-COALESCE(car.outstand_sef_discount,0))';           
        $outSh    = '(COALESCE(car.outstand_sht_amount,0)+COALESCE(car.outstand_sht_interest,0)-COALESCE(car.outstand_sht_discount,0))';
        $deliBasic = '(COALESCE(car.delinq_basic_amount,0)+COALESCE(car.delinq_basic_interest,0)-COALESCE(car.delinq_basic_discount,0))';
        $delSef    = '(COALESCE(car.delinq_sef_amount,0)+COALESCE(car.delinq_sef_interest,0)-COALESCE(car.delinq_sef_discount,0))';
        $delSht    = '(COALESCE(car.delinq_sht_amount,0)+COALESCE(car.delinq_sht_interest,0)-COALESCE(car.delinq_sht_discount,0))';
        $columns = array( 
          0 => "rp.rp_tax_declaration_no",
          1 => "rp.rp_tax_declaration_no",  
          2 => DB::raw($customerName),
          3 => "c.full_address",
          4 => "bgy.brgy_name",
          5 => "rp.rp_pin_declaration_no",
          6 => "rp.rp_lot_cct_unit_desc",
          7 => DB::raw("(SELECT pc_class_code FROM rpt_property_classes WHERE id = propertyClass)"),
          8 => 'uc.uc_description',
          9 => 'car.effectivity_year',
          10 => 'car.rp_assessed_value',
          11 => 'cc.or_no',
          12 => 'cc.cashier_or_date',
          13 => 'cc.total_paid_amount',
          14 => DB::raw($outBasic),
          15 => DB::raw($outSef),
          16 => DB::raw($outSh),
          17 => DB::raw($outBasic.'+'.$outSef.'+'.$outSh),
          18 => DB::raw($deliBasic),
          19 => DB::raw($delSef),
          20 => DB::raw($delSht),
          21 => DB::raw($deliBasic.'+'.$delSef.'+'.$delSht),
          22 => DB::raw($outBasic.'+'.$outSef.'+'.$outSh.'+'.$deliBasic.'+'.$delSef.'+'.$delSht),
         );
          $sql = DB::table('cto_accounts_receivables AS car')
          		 ->join('rpt_properties AS rp', 'car.rp_code', '=', 'rp.id')
                 ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'car.pk_id')
                 ->join('barangays AS bgy', 'bgy.id', '=', 'car.brgy_code_id')
                 ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                 ->join('profile_regions AS pr', 'pr.id', '=', 'bgy.reg_no')
                 ->join('profile_provinces AS pp', 'pp.id', '=', 'bgy.prov_no')
                 ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgy.mun_no')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rp.uc_code')
                 ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                 ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                 ->leftJoin('cto_cashier as cc','cc.id','=','car.rp_last_cashier_id')
                 ->select(
                  'car.id as carId',
                  'rp.id',
                  'rp.rp_tax_declaration_no',
                  'rp.rp_lot_cct_unit_desc',
                  'rp.pk_is_active',
                  'rp.rp_pin_declaration_no',
                  'car.status',
                  'c.p_email_address',
                  'c.full_address',
                  'car.rp_assessed_value',
                  'car.effectivity_year',
                  'pk.pk_description',
                  'bgy.brgy_code',
                  'bgy.brgy_name',
                  'car.rp_assessed_value',
                  'uc.uc_code',
                  'uc.uc_description',
                  'cc.or_no',
                  'cc.cashier_or_date',
                  'cc.total_paid_amount',
                 DB::raw($customerName.' as customername'),
                 DB::raw("CASE 
                         WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                         WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                         WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                         END as propertyClass"),
                  DB::raw("(SELECT pc_class_code FROM rpt_property_classes WHERE id = propertyClass) as propertyClassNew"),
                  DB::raw($outBasic.' as outStandingBasic'),
                  DB::raw($outSef.' as outStandingSef'),
                  DB::raw($outSh.' as outStandingSht'),
                  DB::raw($deliBasic.' as deliquentBasic'),
                  DB::raw($delSef.' as deliquentSEF'),
                  DB::raw($delSht.' as eliquentSht'),
                )
                 ->where('car.is_active',1)
                 ->where('car.status',1)
                 ->groupBy('car.rp_code');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q,$customerName,$addtrss,$outBasic,$outSef,$outSh,$deliBasic,$delSef,$delSht) {
                $sql->where(DB::raw('LOWER(rp.rp_td_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($customerName),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.full_address)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rp.rp_pin_declaration_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rp.rp_tax_declaration_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(uc.uc_description)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(car.effectivity_year)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(car.rp_assessed_value)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($outBasic),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($outSef),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($outSh),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($outBasic.'+'.$outSef.'+'.$outSh),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($deliBasic),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($delSef),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($delSht),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($deliBasic.'+'.$delSef.'+'.$delSht),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($outBasic.'+'.$outSef.'+'.$outSh.'+'.$deliBasic.'+'.$delSef.'+'.$delSht),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rp.rp_lot_cct_unit_desc)'),'like',"%".strtolower($q)."%"); 
            });
        }

        if(!empty($barangay) && isset($barangay)){
            $sql->where(function ($sql) use($barangay) {
                $sql->where('car.brgy_code_id',$barangay);

            });
          }
        if(!empty($taxpayer) && isset($taxpayer)){
            $sql->where(function ($sql) use($taxpayer) {
                $sql->where('car.taxpayer_id',$taxpayer);

            });
          }
        if(!empty($tax) && isset($tax)){
            $sql->where(function ($sql) use($tax) {
                $sql->where('car.rp_code',$tax);

            });
          }
        if(!empty($year) && isset($year)){
            $sql->where(function ($sql) use($year) {
                $sql->where('car.effectivity_year',$year);

            });
          }  
          if(!empty($kind) && isset($kind)){
            $sql->where(function ($sql) use($kind) {
                $sql->where('rp.pk_id',$kind);

            });
          }    

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rp.rp_tax_declaration_no','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=count($sql->get());
        /*$sdsad = $sql->getCountForPagination();
        dd($sdsad);*/
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
