<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptPaymentFile extends Model
{
    use HasFactory;

    public $table = '';
    
    public function getEditDetails($id){
        return $this->with(['rptProperty'=>function($q){
            $q->select('id','rpo_code','pk_id','rvy_revision_year_id','brgy_code_id','rp_td_no');

        }])->select('id','rp_code','year')
            ->where('rpt_delinquents.id',(int)$id)->first();
    }

    public function addPaymentFileReference($data=''){
        DB::table('rpt_payment_attachments')->insert($data);
    }

    public function loadPaymentFiles($request=''){
        $data = DB::table('rpt_payment_attachments')->where('rp_code',$request->id)->get();
        return $data;
    }

    public function getAllTds($request){
        $term=$request->input('term');
        $query = DB::table('rpt_properties')->select('rpt_properties.id',
            DB::raw("CONCAT('[',rpt_properties.rp_tax_declaration_no,'=>',c.full_name,']') as text"))
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

    public function maketdremoteselectlist($request=''){
        $term = $request->input('term');
        $idNotToDisplay = $request->input('idnottodiplay');
        $propDetails = DB::table('rpt_properties')->where('id',$idNotToDisplay)->first();
        $query = DB::table('rpt_payment_attachments as rpa')
                ->join('rpt_properties as rp','rp.id','=','rpa.rp_code')
                ->select('rp.id',
                 DB::raw("CONCAT('[',rp.rp_tax_declaration_no,']','=>','[',c.full_name,']','=>','[',(CASE WHEN rp.pk_is_active = '1' THEN 'Active' WHEN rp.pk_is_active = '0' THEN 'Cancelled' END),']') as text"))
                           ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
                           ->groupBy('rp.rp_tax_declaration_no');
                           if($propDetails != null){
                             $query->where('rp.pk_id',$propDetails->pk_id);
                           }
                           
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(rp.rp_td_no)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.suffix)'),'like',"%".strtolower($term)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($term)."%");
            });
        }  
        if(!empty($idNotToDisplay) && isset($idNotToDisplay)){
            $query->where(function ($sql) use($idNotToDisplay) {   
                $sql->where('rp.id','!=',$idNotToDisplay);
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
        $kind=$request->input('kind');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
      0 =>"cc.id",
      1 =>DB::raw("CONCAT(ry.rvy_revision_year,'-',bgy.brgy_code,'-',rp.rp_td_no)"),
      2 =>'c.full_name',
      3 =>"c.p_email_address",
      4 =>"bgy.brgy_name",  
      5 =>"pk.pk_code",
      6 =>DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpbfv_floor_area))
                WHEN pk.pk_code = 'M' THEN 0
                END"),
      7=>'rp.rp_assessed_value',
      8=>DB::raw("(SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code order by rpt_cto_billings.id DESC LIMIT 1)"),
      9=>DB::raw("(SELECT net_tax_due_amount FROM cto_cashier WHERE or_no = (SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code order by rpt_cto_billings.id DESC LIMIT 1) AND tfoc_is_applicable = 2 LIMIT 1)"),
      10=>DB::raw("(SELECT cashier_or_date FROM cto_cashier WHERE or_no = (SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code order by rpt_cto_billings.id DESC LIMIT 1) AND tfoc_is_applicable = 2 LIMIT 1)"),
    );
        $sql = DB::table('rpt_properties as rp')->select(
            'rp.id',
            'rp.rp_tax_declaration_no',
            'rp.rp_property_code',
            'rp.rp_property_code',
            'rp.rp_assessed_value',
            'c.p_email_address',
			'c.full_name',
            'c.rpo_first_name',
            'c.rpo_custom_last_name',
            'c.rpo_middle_name',
            'c.rpo_address_house_lot_no',
            'c.suffix',
            'pk.pk_code',
            'bgy.brgy_name',
            DB::raw("CASE 
                WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_total_land_area)) 
                WHEN pk.pk_code = 'B' THEN SUM(COALESCE(rpt_building_floor_values.rpbfv_floor_area))
                WHEN pk.pk_code = 'M' THEN 0
                END as area"),
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
            DB::raw("(SELECT pc_class_code FROM rpt_property_classes WHERE id = propertyClass LIMIT 1) as propertyClass"),
            DB::raw("(SELECT cb_or_no FROM rpt_cto_billings WHERE rp_property_code = rp.rp_property_code AND cb_or_no != '' order by rpt_cto_billings.id DESC LIMIT 1) as lastOrNo"),
            DB::raw("(SELECT net_tax_due_amount FROM cto_cashier WHERE or_no = lastOrNo AND tfoc_is_applicable = 2 LIMIT 1) as lastOrAmount"),
            DB::raw("(SELECT cashier_or_date FROM cto_cashier WHERE or_no = lastOrNo AND tfoc_is_applicable = 2 LIMIT 1) as lastOrDate")

        )
			   ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
			   ->join('rpt_revision_year AS ry', 'ry.id', '=', 'rp.rvy_revision_year_id')
			   ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
			   ->join('clients AS c', 'c.id', '=', 'rp.rpo_code')
			   ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
			   ->leftJoin('rpt_building_floor_values','rpt_building_floor_values.rp_code','=','rp.id')
			   ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
			   /*->whereIn('rp.rp_property_code',function($query){
				  $query->from('cto_cashier')
						->join('cto_cashier_real_properties',function($j){
							$j->on('cto_cashier_real_properties.cashier_id','=','cto_cashier.id');
						})
						->where('cto_cashier.tfoc_is_applicable',2)
						->where('cto_cashier.status',1)
						->select('cto_cashier_real_properties.rp_property_code');
			   })*/
			   ->whereIn('rp.rp_property_code',function($query){
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
			   })
               ->where('rp.pk_is_active',1)
               ->where('rp.is_deleted',0)
			   ->groupBy('rp.id');
            
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
                $sql->where(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(ry.rvy_revision_year)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_code)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rp.rp_td_no)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(bgy.brgy_name)'),'like',"%".strtolower($q)."%")
					->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
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

        if(!empty($kind) && isset($kind)){
            $sql->where(function ($sql) use($kind) {
                $sql->where('rp.pk_id',$kind);
            });
        }
        
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('rp.id','DESC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->get()->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
    }

    public function loadHistory($id){
        $data = [];
        $rptProperty = DB::table('rpt_properties')->select('rp_property_code')->where('id',$id)->first();
        if($rptProperty != null){
            $accountReceiavbles = DB::table('cto_accounts_receivables')->select('rp_code_chain')->where('rp_property_code',$rptProperty->rp_property_code)->first();
            if($accountReceiavbles != null){
                $chain = json_decode($accountReceiavbles->rp_code_chain);
                if (($key = array_search($id, $chain)) !== false) {
                   unset($chain[$key]);
                }
                $data = DB::table('cto_cashier_real_properties as ccrp')->whereIn('ccrp.rp_code',$chain)
                                ->join('rpt_properties as rp','rp.id','=','ccrp.rp_code')
                                ->join('cto_cashier as cc',function($j){
                                     $j->on('cc.id','=','ccrp.cashier_id')->where('cc.status',1);
                                })
                                ->join('users AS u', 'u.id', '=', 'cc.created_by')
                                ->join('cto_cashier_details as ccd',function($j){
                                     $j->on('ccd.cashier_id','=','cc.id');
                                })
                                ->join('rpt_cto_billing_details as cbd',function($j){
                                     $j->on('cbd.id','=','ccd.cbd_code');
                                })
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
                                ->select('rp.rp_tax_declaration_no','cc.or_no','cc.cashier_or_date','rp.pk_is_active','u.name as cashier',
                                    DB::raw('MIN(cbd.cbd_covered_year) as fromYear'),
                                    DB::raw('MAX(cbd.cbd_covered_year) as toYear'),
                                    DB::raw('MIN(cbd.sd_mode) as fromQtr'),
                                    DB::raw('MAX(cbd.sd_mode) as toQtr'),
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
                                ->groupBy('cc.or_no')
                                ->get();
                //dd($data);
            }
        }
        return $data;
        
    }

    public function getCashierDetails($propCode = ''){
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
                     ->join('users AS u', 'u.id', '=', 'cc.created_by')
                    ->select(
                        'cc.or_no',
                        'cc.cashier_or_date',
                        'cbd.cbd_covered_year',
                        'cbd.rp_property_code',
                        'cbd.sd_mode',
                        'u.name as cashier',
                        DB::raw('COALESCE(cbd.basic_amount,0) as basic_amount'),
                        DB::raw('COALESCE(cbdd.basic_discount_amount,0) as basic_discount_amount'),
                        DB::raw('COALESCE(cbdp.basic_penalty_amount,0) as basic_penalty_amount'),

                        DB::raw('COALESCE(cbd.sef_amount,0) as sef_amount'),
                        DB::raw('COALESCE(cbdd.sef_discount_amount,0) as sef_discount_amount'),
                        DB::raw('COALESCE(cbdp.sef_penalty_amount,0) as sef_penalty_amount'),

                        DB::raw('COALESCE(cbd.sh_amount,0) as sh_amount'),
                        DB::raw('COALESCE(cbdd.sh_discount_amount,0) as sh_discount_amount'),
                        DB::raw('COALESCE(cbdp.sh_penalty_amount,0) as sh_penalty_amount'),
                    )
                    ->groupBy('cbd.id')
                    ->get();
        return $data;            
    }
}
