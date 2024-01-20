<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptShortCollection extends Model
{
    use HasFactory;

    public $table = '';
    
    public function getEditDetails($id){
        return $this->with(['rptProperty'=>function($q){
            $q->select('id','rpo_code','pk_id','rvy_revision_year_id','brgy_code_id','rp_td_no');

        }])->select('id','rp_code','year')
            ->where('rpt_delinquents.id',(int)$id)->first();
    }

    public function getAllTds($request){
        $term=$request->input('term');
        $query = DB::table('rpt_properties')->select('rpt_properties.id',DB::raw("CONCAT('[',ry.rvy_revision_year,'-',bgy.brgy_code,'-',rpt_properties.rp_td_no,']=>[',CASE 
            WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
            WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END,']') as text"))
                           ->join('barangays AS bgy', 'bgy.id', '=', 'rpt_properties.brgy_code_id')
                           ->join('rpt_revision_year AS ry', 'ry.id', '=', 'rpt_properties.rvy_revision_year_id')
                           ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rpt_properties.pk_id')
                           ->join('clients AS c', 'c.id', '=', 'rpt_properties.rpo_code')
                           /*->whereIn('rpt_properties.id',function($query){
                              $query->from('cto_cashier')
                                    ->join('cto_cashier_real_properties',function($j){
                                        $j->on('cto_cashier_real_properties.cashier_id','=','cto_cashier.id');
                                    })
                                    ->where('cto_cashier.tfoc_is_applicable',2)
                                    ->where('cto_cashier.status',1)
                                    ->select('cto_cashier_real_properties.rp_code');
                           })*/
                           ->whereIn('rpt_properties.rp_property_code',function($query){
                              $query->from('cto_cashier_details as ccd')
                                     ->where('ccd.tfoc_is_applicable',2)
                                     ->join('cto_cashier as cc',function($j){
                                        $j->on('cc.id','=','ccd.cashier_id')
                                          ->where('cc.status',1);
                                     })
                                     ->join('rpt_cto_billing_details as cbd',function($join){
                                        $join->on('cbd.id','=','ccd.cbd_code');
                                                   })
                                     ->select('cbd.rp_property_code');
                           });
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(ry.rvy_revision_year)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_code)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(rpt_properties.rp_td_no)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.suffix)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;                
    }
    public function updateData($id,$columns){
        return DB::table('rpt_delinquents')->where('id',$id)->update($columns);
    }

    public function adData($columns){
        return DB::table('rpt_delinquents')->insert($columns);
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $brgy=$request->input('bgy_id');
        $td_no=$request->input('td_no');
        $q=$request->input('q');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $paidAmont = DB::raw('((COALESCE(ccd.basic_amount,0)+COALESCE(ccd.basic_penalty_amount,0)-COALESCE(ccd.basic_discount_amount,0))+(COALESCE(ccd.sef_amount,0)+COALESCE(ccd.sef_penalty_amount,0)-COALESCE(ccd.sef_discount_amount,0))+(COALESCE(ccd.sh_amount,0)+COALESCE(ccd.sh_penalty_amount,0)-COALESCE(ccd.sh_discount_amount,0))) as paidAmount');
        $newAmont = DB::raw('((COALESCE(ccrp.basic_amount,0)+COALESCE(ccrp.basic_penalty_amount,0)-COALESCE(ccrp.basic_discount_amount,0))+(COALESCE(ccrp.sef_amount,0)+COALESCE(ccrp.sef_penalty_amount,0)-COALESCE(ccrp.sef_discount_amount,0))+(COALESCE(ccrp.sh_amount,0)+COALESCE(ccrp.sh_penalty_amount,0)-COALESCE(ccrp.sh_discount_amount,0))) as newAmount');
        $columns = array( 
      0 =>"cc.id",
      1 =>DB::raw("CONCAT(ry.rvy_revision_year,'-',bgy.brgy_code,'-',rp.rp_td_no)"),
      2 =>DB::raw("CASE 
            WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
            WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
            WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
            ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END
            "),
      3 =>"c.p_email_address",
      4 =>"bgy.brgy_name",  
      5 =>"pk.pk_code",
      6 =>DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpbfv_floor_area))
                WHEN pk.pk_code = 'M' THEN 0
                END"),
      7=>DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpb_assessed_value))
                WHEN pk.pk_code = 'M' THEN SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value))
                END"),
      8=>'top.transaction_no',
      9=>DB::raw("(SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code order by rpt_cto_billings.id DESC LIMIT 1)"),
      10=>DB::raw("(SELECT total_paid_amount FROM cto_cashier WHERE or_no = (SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code order by rpt_cto_billings.id DESC LIMIT 1) AND tfoc_is_applicable = 2)"),
      11=>DB::raw("(SELECT cashier_or_date FROM cto_cashier WHERE or_no = (SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code order by rpt_cto_billings.id DESC LIMIT 1) AND tfoc_is_applicable = 2)"),
      12=>DB::raw('((COALESCE(ccd.basic_amount,0)+COALESCE(ccd.basic_penalty_amount,0)-COALESCE(ccd.basic_discount_amount,0))+(COALESCE(ccd.sef_amount,0)+COALESCE(ccd.sef_penalty_amount,0)-COALESCE(ccd.sef_discount_amount,0))+(COALESCE(ccd.sh_amount,0)+COALESCE(ccd.sh_penalty_amount,0)-COALESCE(ccd.sh_discount_amount,0))-((COALESCE(ccrp.basic_amount,0)+COALESCE(ccrp.basic_penalty_amount,0)-COALESCE(ccrp.basic_discount_amount,0))+(COALESCE(ccrp.sef_amount,0)+COALESCE(ccrp.sef_penalty_amount,0)-COALESCE(ccrp.sef_discount_amount,0))+(COALESCE(ccrp.sh_amount,0)+COALESCE(ccrp.sh_penalty_amount,0)-COALESCE(ccrp.sh_discount_amount,0))))')
    );

        $sql = DB::table('rpt_properties as rp')->select(
                            DB::raw("CONCAT(ry.rvy_revision_year,'-',bgy.brgy_code,'-',rp.rp_td_no) as taxDeclarationNo"),
                            DB::raw("CASE 
                            WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                            WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                            WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                            WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                            ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END as customername
                            "),
                            'rp.id',
                            'rp.rp_property_code',
                            'rp.rp_property_code',
                            'c.p_email_address',
                            'c.rpo_first_name',
                            'c.rpo_custom_last_name',
                            'c.rpo_middle_name',
                            'c.rpo_address_house_lot_no',
                            $paidAmont,
                            $newAmont,
                            'top.transaction_no as txnId',
                            'c.suffix',
                            'pk.pk_code',
                            'bgy.brgy_name',
                            DB::raw("CASE 
                                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) 
                                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpbfv_floor_area))
                                WHEN pk.pk_code = 'M' THEN 0
                                END as area"),
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
                            DB::raw("(SELECT pc_class_code FROM rpt_property_classes WHERE id = propertyClass) as propertyClass"),
                            DB::raw("(SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code AND cb_or_no != '' order by rpt_cto_billings.id DESC LIMIT 1) as lastOrNo"),
                            DB::raw("(SELECT total_paid_amount FROM cto_cashier WHERE or_no = lastOrNo AND tfoc_is_applicable = 2) as lastOrAmount"),
                            DB::raw("(SELECT cashier_or_date FROM cto_cashier WHERE or_no = lastOrNo AND tfoc_is_applicable = 2) as lastOrDate"))
                           ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
                           ->join('rpt_revision_year AS ry', 'ry.id', '=', 'rp.rvy_revision_year_id')
                           ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                           ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                           ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                           ->leftJoin('rpt_building_floor_values','rpt_building_floor_values.rp_code','=','rp.id')
                           ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                           ->join('cto_cashier_real_properties as ccrp',function($j){
                              $j->on('ccrp.rp_code','=','rp.id')
                                ->where('ccrp.tfoc_is_applicable',2)
                                ->where('ccrp.is_short_collection',1)
                                ->join('cto_cashier as cc',function($jo){
                                        $jo->on('cc.id','=','ccrp.cashier_id')
                                          ->where('cc.status',1);
                                     })
                                ->join('cto_cashier_details as ccd',function($join){
                                    $join->on('ccd.cashier_id','=','cc.id')
                                         ->where('ccd.tfoc_is_applicable',2);
                                })
                                ->join('rpt_cto_billing_details as cbd',function($joina){
                                    $joina->on('cbd.id','=','ccd.cbd_code')
                                         ->on('cbd.cbd_covered_year','ccrp.sc_covered_year');
                                });
                           })
                           ->join('cto_top_transactions AS top', 'top.id', '=', 'cc.top_transaction_id')
                           ->groupBy('rp.id');

            
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ry.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rp.rp_td_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pk.pk_code)'),'like',"%".strtolower($q)."%");
            });
        }
        if(!empty($brgy) && isset($brgy)){
            $sql->where(function ($sql) use($brgy) {
                $sql->where('bgy.id',$brgy);
            });
        }

        if(!empty($td_no) && isset($td_no)){
            $sql->where(function ($sql) use($td_no) {
                $sql->where('rp.id',$td_no);
            });
        }
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rp.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function getShortColleDetails($propCode = ''){
        //dd($propCode);
        $data = DB::table('cto_cashier_details as ccd')
                    ->where('ccd.tfoc_is_applicable',2)
                    ->join('cto_cashier as cc',function($j){
                        $j->on('cc.id','=','ccd.cashier_id')
                          ->where('cc.status',1);
                    })
                    ->join('rpt_cto_billing_details as cbd',function($join)use($propCode){
                        $join->on('cbd.id','=','ccd.cbd_code')
                             ->where('cbd.rp_code',$propCode);
                    })
                    ->join('cto_cashier_real_properties as ccrp',function($j){
                        $j->on('ccrp.rp_code','=','cbd.rp_code');

                    })
                    ->select(
                        'cc.or_no',
                        'cc.cashier_or_date',
                        'cbd.cbd_covered_year',
                        'cbd.rp_property_code',
                        'cbd.sd_mode',
                        'ccrp.is_short_collection_paid',
                        DB::raw('(COALESCE(ccd.basic_amount,0)+COALESCE(ccd.basic_penalty_amount,0)-COALESCE(ccd.basic_discount_amount,0)) as basicCollectedTax'),
                        DB::raw('(COALESCE(ccd.sef_amount,0)+COALESCE(ccd.sef_penalty_amount,0)-COALESCE(ccd.sef_discount_amount,0)) as sefCollectedTax'),
                        DB::raw('(COALESCE(ccd.sh_amount,0)+COALESCE(ccd.sh_penalty_amount,0)-COALESCE(ccd.sh_discount_amount,0)) as shCollectedTax'),
                        'ccd.basic_amount',
                        'ccd.basic_discount_amount',
                        'ccd.basic_penalty_amount',
                        'ccd.sef_amount',
                        'ccd.sef_discount_amount',
                        'ccd.sef_penalty_amount',
                        'ccd.sh_amount',
                        'ccd.sh_discount_amount',
                        'ccd.sh_penalty_amount',

                        DB::raw('(COALESCE(ccrp.basic_amount,0)+COALESCE(ccrp.basic_penalty_amount,0)-COALESCE(ccrp.basic_discount_amount,0)) as basicActualTax'),
                        DB::raw('(COALESCE(ccrp.sef_amount,0)+COALESCE(ccrp.sef_penalty_amount,0)-COALESCE(ccrp.sef_discount_amount,0)) as sefActualTax'),
                        DB::raw('(COALESCE(ccrp.sh_amount,0)+COALESCE(ccrp.sh_penalty_amount,0)-COALESCE(ccrp.sh_discount_amount,0)) as shActualTax'),

                        'ccrp.basic_amount as new_basic_amount',
                        'ccrp.basic_discount_amount as new_basic_discount_amount',
                        'ccrp.basic_penalty_amount as new_basic_penalty_amount',
                        'ccrp.sef_amount as new_sef_amount',
                        'ccrp.sef_discount_amount as new_sef_discount_amount',
                        'ccrp.sef_penalty_amount as new_sef_penalty_amount',
                        'ccrp.sh_amount as new_sh_amount',
                        'ccrp.sh_discount_amount as new_sh_discount_amount',
                        'ccrp.sh_penalty_amount as new_sh_penalty_amount',
                    )
                    ->groupBy('cbd.id')
                    ->get();
                    //dd($data);
        return $data;            
    }
}
