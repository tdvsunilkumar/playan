<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use DB;
class RptPropertyOnlineAccess extends Model
{
  public function updateActiveInactive($id,$columns){
    return DB::table('rpt_property_online_accesses')->where('id',$id)->update($columns);
  }  
    public function updateData($id,$columns){
        return DB::table('rpt_property_online_accesses')->where('id',$id)->update($columns);
    }
    public function updateDefaultall($data){
      return DB::table('rpt_property_online_accesses')->update($data);
    }
    public function addData($postdata){
        return DB::table('rpt_property_online_accesses')->insert($postdata);
    }
    public function getRptproperty(){
         return DB::table('rpt_properties AS rpt')
                ->join('clients AS c', 'c.id', '=', 'rpt.rpo_code')
                ->select('rpt.id','c.full_name','rpt.rp_tax_declaration_no')->where('rpt.pk_is_active',1)->get();
    }
    public function getTaxDeclaresionOnlineDetails($id){
          return DB::table('rpt_properties AS rpt')
                ->join('clients AS c', 'c.id', '=', 'rpt.rpo_code')
                ->select('rpt.id','c.full_name','rpt.rp_tax_declaration_no')->where('rpo_code',$id)->where('rpt.pk_is_active',1)->get();
    }
    public function getClientData($id)
    {
        return DB::table('rpt_property_online_accesses as rptOnline')
            ->join('rpt_properties as rp', function ($join) {
                $join->on('rp.rp_property_code', '=', 'rptOnline.rp_property_code')
                    ->on('rp.id', '=', 'rptOnline.rp_code');
            })
            ->leftJoin('clients AS c', 'c.id', '=', 'rp.rpo_code')
            ->leftJoin('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
            ->leftJoin('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
            ->leftJoin('rpt_property_appraisals', 'rpt_property_appraisals.rp_code', '=', 'rp.id')
            ->leftJoin('rpt_property_machine_appraisals as ma', 'ma.rp_code', '=', 'rp.id')
            ->select(
                'rp.rp_tax_declaration_no',
                'rp.pk_is_active',
                'rp.rp_pin_declaration_no',
                'rptOnline.id as rptOnlineid',
                'rp.rp_property_code',
                'rp.rpo_code',
                'bgy.brgy_name',
                'c.full_name',
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                            WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                            WHEN pk.pk_code = 'M' THEN ma.pc_class_code
                            END as propertyClass"),
                DB::raw("(SELECT pc_class_description FROM rpt_property_classes WHERE id = propertyClass) as propertyClass"),
                DB::raw("CASE 
                            WHEN rp.pk_id = 2 THEN rp.rp_cadastral_lot_no 
                            WHEN rp.pk_id = 1 THEN CONCAT(COALESCE(rp.rp_building_cct_no, ''), '; ', COALESCE(rp.rp_building_unit_no, '')) 
                            WHEN rp.pk_id = 3 THEN GROUP_CONCAT(DISTINCT rpma_description SEPARATOR ';') 
                            END as cctUnitNo"),
                DB::raw("CASE 
                            WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                            WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rp.rpb_assessed_value)) 
                            WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpm_assessed_value)) 
                            END as assessedValue")
            )
            ->where('rptOnline.clients_id', $id)
            ->groupBy('rp.rp_property_code') // Group by to avoid duplicate rows
            ->get()
            ->toArray();
    }

    public function getTdDetails($id = ''){
      $data = DB::table('rpt_properties as rp')
                        ->leftJoin('cto_cashier_real_properties as ccrp', function($join) {
                            $join->on('rp.id', '=', 'ccrp.rp_code')->where('ccrp.tfoc_is_applicable',2)
                                ->whereRaw('ccrp.created_at = (select max(created_at) from cto_cashier_real_properties where rp_code = rp.id)')
                                ->join('cto_cashier as cc',function($j){
                                  $j->on('cc.id','=','ccrp.cashier_id')->where('cc.tfoc_is_applicable',2)->where('cc.status',1);
                                });
                         })
                        ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                        ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                        ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
                        ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                        ->leftJoin('rpt_property_machine_appraisals as ma','ma.rp_code','=','rp.id')
                        ->select('cc.or_no','cc.cashier_or_date','rp.rp_pin_declaration_no','rp.rp_property_code','rp.rpo_code','bgy.brgy_name',
                          DB::raw("CASE 
                                WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                                WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                                WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                                WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                                ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END as customername
                                "),
                          DB::raw("CASE WHEN rp.pk_id = 2 THEN rp.rp_cadastral_lot_no WHEN rp.pk_id = 1 THEN CONCAT(COALESCE(rp.rp_building_cct_no,''),'; ',COALESCE(rp.rp_building_unit_no,'')) WHEN rp.pk_id = 3 THEN GROUP_CONCAT(DISTINCT rpma_description SEPARATOR ';') END as cctUnitNo"),
                          DB::raw("CASE 
                     WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                     WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rp.rpb_assessed_value))
                     WHEN pk.pk_code = 'M' THEN SUM(COALESCE(ma.rpm_assessed_value))
                     END as assessedValue"),
                          DB::raw("CASE 
                                    WHEN pk.pk_code = 'L' THEN rpt_property_appraisals.pc_class_code 
                                    WHEN pk.pk_code = 'B' THEN rp.pc_class_code
                                    WHEN pk.pk_code = 'M' THEN ma.pc_class_code
                                    END as propertyClass"),
                          DB::raw("(SELECT pc_class_description FROM rpt_property_classes WHERE id = propertyClass) as propertyClass")
                         )
                        ->where('rp.id',$id)
                        ->first();
        return $data;
    }
    
    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');

        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          0 =>"id",  
          1 => 'rpt.account_no',
          2 => 'rpt.full_name',
          3 => 'rpt.full_address',
          4 => "rpt.p_mobile_no",
          5 => 'rpt.p_email_address',
          6 => 'rpt.is_online',
          9 => 'hr.fullname',
          10 => 'rpt.updated_at',

        );
        

        $sql = DB::table('clients AS rpt')
              ->leftjoin('barangays AS b', 'b.id', '=', 'rpt.p_barangay_id_no')
              ->leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
              ->leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
              ->leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
              ->leftjoin('hr_employees AS hr', 'hr.id', '=', 'rpt.updated_by')
              ->select('rpt.id','rpt.suffix','rpt.p_email_address','rpt.is_active','hr.fullname','rpt.full_name','rpt.full_address','rpt.updated_at','rpt.is_online','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','rpt.rpo_custom_last_name','rpt.rpo_first_name','rpt.rpo_middle_name','rpt.rpo_address_house_lot_no','rpt.rpo_address_street_name','rpt.rpo_address_subdivision','rpt.p_mobile_no','rpt.is_active','rpt.account_no',
			DB::raw("CASE 
            WHEN rpt.rpo_address_house_lot_no IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            WHEN rpt.rpo_address_street_name IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            WHEN rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''))),',','')
            WHEN rpt.rpo_address_house_lot_no IS NULL AND rpt.rpo_address_street_name IS NULL AND rpt.rpo_address_subdivision IS NULL THEN REPLACE(TRIM(CONCAT(COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','')
            ELSE REPLACE(TRIM(CONCAT(COALESCE(rpt.rpo_address_house_lot_no,''),', ',COALESCE(rpt.rpo_address_street_name,''),', ',COALESCE(rpt.rpo_address_subdivision,''),', ',COALESCE(b.brgy_name,''),', ',COALESCE(pm.mun_desc,''),', ',COALESCE(pp.prov_desc,''),', ',COALESCE(pr.reg_region,''))),',','') END as address
            ")
			)
              ->where('rpt.is_rpt',1)->where('rpt.is_online',1);
              //->where('rpt.id',2);
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->orWhere(DB::raw('LOWER(b.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(b.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.full_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(rpt.full_address)'),'like',"%".strtolower($q)."%") 
					->orWhere(DB::raw('LOWER(rpt.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_house_lot_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_address_street_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.p_tin_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rpt.account_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere('rpt.p_mobile_no','like',"%".$q."%");
                  
            });
        }
        /*if(!empty($alphabet) && isset($alphabet)){
             $sql->havingRaw('customername LIKE %'.$alphabet);
        }*/

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rpt.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function updateDataDuringCashBill($billingId = '',$for = ''){
        $amrketValue = "CASE 
                                        WHEN pk.pk_code = 'L' THEN (SELECT SUM(COALESCE(rpt_property_appraisals.rpa_adjusted_market_value,0)) FROM rpt_property_appraisals WHERE rpt_property_appraisals.rp_code = rp.id)
                                        WHEN pk.pk_code = 'B' THEN rp.rpb_accum_deprec_market_value
                                        WHEN pk.pk_code = 'M' THEN (SELECT SUM(COALESCE(rpt_property_machine_appraisals.rpma_market_value,0)) FROM rpt_property_machine_appraisals WHERE rpt_property_appraisals.rp_code = rp.id) END";
        $billingDetails = DB::table('rpt_cto_billing_details as cbd')
                             ->join('rpt_properties as rp','rp.id','=','cbd.rp_code')
                             ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                             ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                             ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
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
                                'cbd.cbd_covered_year','cbd.sd_mode','cbd.cbd_assessed_value','rp.rp_tax_declaration_no','rp.rp_pin_declaration_no',
                                DB::raw($amrketValue.' as marketValue'),
                                DB::raw('COALESCE(cbd.basic_amount,0) as basicAmount'),
                                DB::raw('COALESCE(cbdd.basic_discount_amount,0) as basicDiscount'),
                                DB::raw('COALESCE(cbdp.basic_penalty_amount,0) as basicPenalty'),

                                DB::raw('COALESCE(cbd.sef_amount,0) as sefAmount'),
                                DB::raw('COALESCE(cbdd.sef_discount_amount,0) as sefDiscount'),
                                DB::raw('COALESCE(cbdp.sef_penalty_amount,0) as sefPenalty'),

                                DB::raw('COALESCE(cbd.sh_amount,0) as shAmount'),
                                DB::raw('COALESCE(cbdd.sh_discount_amount,0) as shDiscount'),
                                DB::raw('COALESCE(cbdp.sh_penalty_amount,0) as shPenalty'),

                                DB::raw('((COALESCE(cbd.basic_amount,0)+COALESCE(cbd.sef_amount,0)+COALESCE(cbd.sh_amount,0))+(COALESCE(cbdp.basic_penalty_amount,0)+COALESCE(cbdp.sef_penalty_amount,0)+COALESCE(cbdp.sh_penalty_amount,0))-(COALESCE(cbdd.basic_discount_amount,0)+COALESCE(cbdd.sef_discount_amount,0)+COALESCE(cbdd.sh_discount_amount,0))) as totalDue')
                               )
                             ->where('cbd.cb_code',$billingId)
                             ->first();     
                            // if($billingDetails);                                                           
        $onlineData = DB::table('rpt_property_online_accesses')->where('rp_property_code',$rpPropertyCode)->where('rp_code',$rpCode)->first();
        if($onlineData != null){
            if($for == 'bill'){
                
                             if($billingDetails != null){
                                /*$dataToUpdate = [
                                    'tax_declaration_no' => ,
                                    'property_index_no' => ,
                                    ''
                                ];*/

                             }
                             dd($billingDetails);

            }else{

            }
        }
        
        }
}
