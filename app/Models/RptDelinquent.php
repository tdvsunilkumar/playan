<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptDelinquent extends Model
{
    use HasFactory;

    public $table = 'rpt_delinquents';

    public function rptProperty(){
        return $this->belongsTo(RptProperty::class,'rp_code');
    }
    
    public function getEditDetails($id){
        return $this->with(['rptProperty'=>function($q){
            $q->select('id','rpo_code','pk_id','rvy_revision_year_id','brgy_code_id','rp_td_no');

        }])->select('id','rp_code','year')
            ->where('rpt_delinquents.id',(int)$id)->first();
    }
    public function updateData($id,$columns){
        return DB::table('cto_accounts_receivables')->where('id',$id)->update($columns);
    }

    public function adData($columns){
        return DB::table('rpt_delinquents')->insert($columns);
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
        $addtrss = "CASE 
            WHEN c.rpo_address_house_lot_no IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_street_name,''),', ',COALESCE(c.rpo_address_subdivision,''),', ',COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            WHEN c.rpo_address_street_name IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_house_lot_no,''),', ',COALESCE(c.rpo_address_subdivision,''),', ',COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            WHEN c.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_house_lot_no,''),', ',COALESCE(c.rpo_address_street_name,''))),',','')
            WHEN c.rpo_address_house_lot_no IS NULL AND c.rpo_address_street_name IS NULL AND c.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            ELSE REPLACE(TRIM(CONCAT(COALESCE(c.rpo_address_house_lot_no,''),', ',COALESCE(c.rpo_address_street_name,''),', ',COALESCE(c.rpo_address_subdivision,''),', ',COALESCE(bgy.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','') END";  
        $deliBasic = '(COALESCE(car.delinq_basic_amount,0)+COALESCE(car.delinq_basic_interest,0)-COALESCE(car.delinq_basic_discount,0))';
        $delSef    = '(COALESCE(car.delinq_sef_amount,0)+COALESCE(car.delinq_sef_interest,0)-COALESCE(car.delinq_sef_discount,0))';
        $delSht    = '(COALESCE(car.delinq_sht_amount,0)+COALESCE(car.delinq_sht_interest,0)-COALESCE(car.delinq_sht_discount,0))';
        $columns = array( 
          0 => "id",
          1 => "rp.rp_tax_declaration_no",  
          2 => 'c.full_name',
          3 => DB::raw($addtrss),
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
          14 => DB::raw($deliBasic),
          15 => DB::raw($delSef),
          16 => DB::raw($delSht),
          17 => DB::raw($deliBasic.'+'.$delSef.'+'.$delSht),
          18 => DB::raw($deliBasic.'+'.$delSef.'+'.$delSht),
          19 => DB::raw($deliBasic.'+'.$delSef.'+'.$delSht)
         );
          $sql = DB::table('cto_accounts_receivables AS car')
                 ->join('rpt_properties AS rp', 'car.rp_code', '=', 'rp.id')
                 ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'car.pk_id')
                 ->join('barangays AS bgy', 'bgy.id', '=', 'car.brgy_code_id')
                 ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                 //->join('cto_accounts_receivable_details as card','card.ar_id','=','car.id')
                 ->join('profile_regions AS pr', 'pr.id', '=', 'bgy.reg_no')
                 ->join('profile_provinces AS pp', 'pp.id', '=', 'bgy.prov_no')
                 ->join('profile_municipalities AS pm', 'pm.id', '=', 'bgy.mun_no')
                 ->join('rpt_update_codes AS uc', 'uc.id', '=', 'rp.uc_code')
                 ->leftJoin('cto_cashier as cc',function($j){
                    $j->on('cc.id','=',DB::raw('(SELECT cashier_id FROM cto_cashier_real_properties WHERE rp_property_code = rp.rp_property_code ORDER BY id DESC LIMIT 1)'));
                 })
                 ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                 ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                 ->select(
                  'car.id as carId',
                  'rp.id',
                  'rp.rp_tax_declaration_no',
                  'rp.rp_lot_cct_unit_desc',
                  'rp.pk_is_active',
                  'rp.rp_pin_declaration_no',
                  'car.status',
                  'car.rp_assessed_value',
                  'car.effectivity_year',
                  'pk.pk_description',
                  'bgy.brgy_code',
                  'bgy.brgy_name',
                  'car.rp_assessed_value',
                  'cc.or_no',
                  'cc.cashier_or_date',
                  'cc.total_paid_amount',
                  'uc.uc_code',
                  'uc.uc_description',
                  'c.full_name',
                  'c.p_email_address',
                  'car.acknowledged_date',
                  'car.is_approved',
                   DB::raw($addtrss.' as address'),
                 DB::raw("CASE 
                         WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                         WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                         WHEN pk.pk_code = 'M' THEN rpt_property_machine_appraisals.pc_class_code
                         END as propertyClass"),
                 /*DB::raw('MIN(card.ar_covered_year) as fromYear'),
                 DB::raw('MAX(card.ar_covered_year) as toYear'),*/
                  DB::raw("(SELECT pc_class_code FROM rpt_property_classes WHERE id = propertyClass) as propertyClassNew"),
                  DB::raw($deliBasic.' as deliquentBasic'),
                  DB::raw($delSef.' as deliquentSEF'),
                  DB::raw($delSht.' as eliquentSht'),
                  DB::raw($deliBasic.'+'.$delSef.'+'.$delSht.' as totalDue')
                )
                 ->where('car.is_active',1)
                 ->where('car.status',1)
                 ->where('car.delinq_basic_amount','>',0)
                 ->groupBy('car.rp_code');
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q,$deliBasic,$delSef,$delSht) {
                $sql->where(DB::raw('LOWER(rp.rp_td_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(c.p_email_address)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rp.rp_lot_cct_unit_desc)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(rp.rp_tax_declaration_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(car.rp_assessed_value)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cc.total_paid_amount)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw('LOWER(cc.cashier_or_date)'),'like',"%".strtolower($q)."%")
                ->orWhere(DB::raw($deliBasic.'+'.$delSef.'+'.$delSht),'like',"%".strtolower($q)."%"); 
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
          $sql->orderBy('rp.id','ASC');

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
