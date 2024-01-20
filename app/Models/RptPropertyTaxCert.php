<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptPropertyTaxCert extends Model
{
    use HasFactory;

    public function addData($postdata, $updateCode = ''){
        DB::table('rpt_property_tax_certs')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function taxCertDetails(){
        return $this->hasMany(RptPropertyTaxCertDetail::class,'rptc_code');
    }

     public function updateData($id,$columns, $updateCode = ''){
        return DB::table('rpt_property_tax_certs')->where('id',$id)->update($columns);
        
    }

    public function addCertDetailsData($postdata, $updateCode = ''){
        DB::table('rpt_property_tax_cert_details')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

    public function owner($value=''){
        return $this->belongsTo(RptPropertyOwner::class,'rptc_owner_code');
    }

    public function requester($value=''){
        return $this->belongsTo(RptPropertyOwner::class,'rptc_requestor_code');
    }

    public function prepareBy($value=''){
        return $this->belongsTo(HrEmployee::class,'rptc_prepared_by');
    }

    public function checkedBy($value=''){
        return $this->belongsTo(HrEmployee::class,'rptc_checked_by');
    }

     public function updateCertDetailsData($id,$columns, $updateCode = ''){
        return DB::table('rpt_property_tax_cert_details')->where('id',$id)->update($columns);
        
    }
    
    public function clientData($id){  
        return DB::table('clients AS c')
               ->Leftjoin('barangays AS b', 'b.id', '=', 'c.p_barangay_id_no')
               ->Leftjoin('profile_regions AS pr', 'pr.id', '=', 'b.reg_no')
               ->Leftjoin('profile_provinces AS pp', 'pp.id', '=', 'b.prov_no')
               ->Leftjoin('profile_municipalities AS pm', 'pm.id', '=', 'b.mun_no')
               ->select('c.id','c.suffix','b.brgy_code','b.brgy_name','pm.mun_desc','pp.prov_desc','pr.reg_region','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.rpo_address_house_lot_no','c.rpo_address_street_name','c.p_tin_no')->where('c.id','=',$id)->get();

    }
    public function getPropertyClientName($id){  
        return DB::table('rpt_properties AS rpt')
               ->Leftjoin('clients AS c', 'c.id', '=', 'rpt.rpo_code')
               ->select('rpt.id','c.suffix','c.full_name','c.rpo_custom_last_name','c.rpo_first_name','c.rpo_middle_name','c.p_tin_no','rpt.rp_tax_declaration_no')->where('rpt.id',$id)->first();

    }
    public function getTaxDeclaresionNOBuildingDetails($id){
          return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')->where('rpo_code',$id)->where('rp_app_taxability',1)->where('pk_is_active',1)->where('is_deleted',0)->get();
    }

    public function getTaxDeclaresionNODetailsAll(){
          return DB::table('rpt_properties')
                    ->select('id','rp_td_no','rp_tax_declaration_no')
                    ->where('pk_is_active',1)
                    ->where('rp_app_taxability',1)
                    ->where('is_deleted',0)->get();
    }

    public function getOrNoForOwner($id = []){
        return DB::table('cto_cashier_details as ccd')
                    ->select('ccd.id','cc.or_no','cc.total_paid_amount','cc.cashier_or_date','cc.id as cashier_id')
                    ->join('cto_cashier as cc',function($j){
                        $j->on('cc.id','=','ccd.cashier_id')->where('cc.status',1)->where('cc.ocr_id',0);
                    })
                    /*->join('cto_forms_miscellaneous_payments as misc',function($j){
                        $j->on('misc.tfoc_id','=','ccd.tfoc_id')->where('misc.fpayment_module_name','rpt_tax_clearance');
                    })*/
                    ->whereIn('ccd.client_citizen_id',$id)
                    ->where('ccd.payee_type','1')
                    ->groupBy('cc.or_no')
                    ->get();
    }

    public function getOrNoForOwnerRemoteSelectList($request){
        $mergeData = [];
            $id = $request->citizen_id;
            $requester = $request->requester;
            if($id > 0){
                $mergeData[] = $id;
            }if($requester > 0){
                $mergeData[] = $requester;
            }
        $term=$request->input('term');
        $query = DB::table('cto_cashier_details as ccd')
                    ->select('ccd.id as ccdid','cc.or_no as id','cc.or_no as text','cc.total_paid_amount','cc.cashier_or_date','cc.id as cashier_id')
                    ->join('cto_cashier as cc',function($j){
                        $j->on('cc.id','=','ccd.cashier_id')->where('cc.status',1)->where('cc.ocr_id',0);
                    })
                    /*->join('cto_forms_miscellaneous_payments as misc',function($j){
                        $j->on('misc.tfoc_id','=','ccd.tfoc_id')->where('misc.fpayment_module_name','rpt_tax_clearance');
                    })*/
                    ->whereIn('ccd.client_citizen_id',$mergeData)
                    ->where('ccd.payee_type','1')
                    ->groupBy('cc.or_no');
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(cc.or_no)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data;            
    }
    // public function getEmployee(){
    //      return DB::table('hr_employees')->select('id','firstname','middlename','lastname','suffix','fullname','title')->get();
    // }
    public function getEmployee($request){
        $term=$request->input('term');
        $query = DB::table('rpt_appraisers AS ra')
                        ->join('hr_employees AS h', 'h.id', '=', 'ra.ra_appraiser_id')
                        ->select('ra.id','h.fullname as text','ra.ra_appraiser_position AS description')
                        ->where('ra.ra_is_active',1);
            if(!empty($term) && isset($term)){
            $query->where(function ($sql) use($term) {   
                $sql->orWhere(DB::raw('LOWER(h.fullname)'),'like',"%".strtolower($term)."%");
            });

        }  
        $data = $query->simplePaginate(20);             
        return $data; 
    }
    public function getAppraisersPositionDetails($id){
        return DB::table('rpt_appraisers AS ra')
        ->join('hr_employees AS h', 'h.id', '=', 'ra.ra_appraiser_id')
        ->select('ra.id','h.firstname','h.middlename','h.lastname','h.fullname','h.suffix','h.title','ra.ra_appraiser_position AS description')->where('ra.id',(int)$id)->first();
    }
    public function searchByTdNo($rpTdNo,$brngy){
        return RptProperty::with(['landAppraisals','propertyOwner','propertyKindDetails','floorValues','machineAppraisals'])
                                                ->where('id',$rpTdNo)
                                                //->where('brgy_code_id',$brngy)
                                                ->where('is_deleted',0)
                                                ->where('pk_is_active',1)
                                                ->first();
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $startdate =$request->input('fromdate');
        $enddate =$request->input('todate');

        $barangy = $request->input('barangay');
        $request->session()->put('taxClearanceSelectedBrgy',$barangy);
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 => "tc.rptc_control_no",
          2 => "c.full_name",
          3 => "tc.rptc_owner_tin_no",
          4 => "request.full_name",
          5 => "tc.rptc_purpose",
          6 => "tc.rptc_or_no",
          7 => "tc.rptc_or_amount",
          8 => DB::raw('COUNT(tcd.rptc_code)'),
          9 => "tc.rptc_or_date"
         );
           $sql = DB::table('rpt_property_tax_certs as tc')->select(
            //'tc.*',
            DB::raw('COUNT(tcd.rptc_code) as rp_code'),
            'tc.rptc_control_no',
            'tc.rptc_owner_tin_no',
            'tc.rptc_purpose',
            'tc.rptc_or_no',
            'tc.rptc_or_amount',
            'tc.rptc_or_date',
            'tc.id','c.full_name as customername','request.full_name as requesterName'
            
        )
           ->join('rpt_property_tax_cert_details as tcd','tcd.rptc_code','=','tc.id')
           ->join('rpt_properties as rp','rp.id','=','tcd.rp_code')
           ->groupBy('tc.rptc_control_no')
           ->join('clients as c','c.id','=','tc.rptc_owner_code')
           ->join('clients as request','request.id','=','tc.rptc_requestor_code');
		   
        if(!empty($q) && isset($q)){
                    $sql->orWhere(DB::raw('LOWER(tc.rptc_control_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('DATE_FORMAT(tc.rptc_date,"%d/%m/%Y")'),'like',"%".strtolower($q)."%")
						->orWhere(DB::raw('LOWER(c.full_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(c.rpo_first_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(c.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(c.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(c.suffix)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(tc.rptc_or_no)'),'like',"%".strtolower($q)."%");
                }
        if(!empty($barangy) && isset($barangy)){
            $sql->where(function ($sql) use($barangy) {
                $sql->where('rp.brgy_code_id',$barangy);

            });
        }
         if(!empty($startdate) && isset($startdate)){
             $sql->whereDate('tc.rptc_date','>=',$startdate);  
         }
         if(!empty($enddate) && isset($enddate)){
             $sql->whereDate('tc.rptc_date','<=',$enddate);  
         }
		 
        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
		}else{
          $sql->orderBy('tc.id','ASC');
		}

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->get()->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function paymentDetailsStandardQuery(){
          $latestRecords = DB::table('cto_cashier_real_properties')
                               ->select('cto_cashier_real_properties.rp_code', DB::raw('MAX(cto_cashier_real_properties.created_at) as latest_created_at'))
                               ->groupBy('cto_cashier_real_properties.rp_code');
          return DB::table('cto_cashier_real_properties as ccrp')
                              ->joinSub($latestRecords, 'latest_records', function ($join) {
                                    $join->on('ccrp.rp_code', '=', 'latest_records.rp_code')
                                         ->on('ccrp.created_at', '=', 'latest_records.latest_created_at');
                               })
                              ->select(
                                'ccrp.rp_code',
                                'ccrp.id as ccrpId',
                                'rp.rp_pin_declaration_no',
                                'rp.pr_tax_arp_no',
                                'rp.loc_group_brgy_no',
                                DB::raw('MAX(ccrp.id) as latest_order_date'),
                                DB::raw('(SUM(COALESCE(ccd.basic_amount,0))+SUM(COALESCE(ccd.basic_penalty_amount,0))-SUM(COALESCE(ccd.basic_discount_amount,0))) as baiscAmount'),
                                DB::raw('(SUM(COALESCE(ccd.sef_amount,0))+SUM(COALESCE(ccd.sef_penalty_amount,0))-SUM(COALESCE(ccd.sef_discount_amount,0))) as sefAmount'),
                                DB::raw('(SUM(COALESCE(ccd.sh_amount,0))+SUM(COALESCE(ccd.sh_penalty_amount,0))-SUM(COALESCE(ccd.sh_discount_amount,0))) as shAmount'),
                                'rp.id as propertyId',
                                'cc.or_no',
                                'cc.total_paid_amount',
                                'cc.cashier_or_date',
                                'cc.status',
                                'bgy.brgy_name',
                                'rp.rp_suffix',
                                'cc.net_tax_due_amount',
                                DB::raw('MIN(cbd.cbd_covered_year) as startYear'),
                                DB::raw('MAX(cbd.cbd_covered_year) as endYear'),
                                DB::raw('MIN(cbd.sd_mode) as startQtr'),
                                DB::raw('MAX(cbd.sd_mode) as endQtr'),
                                DB::raw("CASE 
                                    WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                                    WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                                    WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                                    WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                                    ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END as customername
                                    "),
                                'rp.rp_tax_declaration_no',
                                DB::raw("CASE 
                                    WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value,0)) 
                                    WHEN pk.pk_code = 'B' THEN COALESCE(rp.rpb_assessed_value,0)
                                    WHEN pk.pk_code = 'M' THEN SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value,0))
                                    END as assessedValue"),
                                DB::raw("CASE 
                                    WHEN pk.pk_code = 'L' THEN rp.rp_cadastral_lot_no 
                                    WHEN pk.pk_code = 'B' THEN CONCAT(rp.rp_building_cct_no,'|',rp.rp_building_unit_no)
                                    WHEN pk.pk_code = 'M' THEN GROUP_CONCAT(DISTINCT rpt_property_machine_appraisals.rpma_description SEPARATOR '; ')
                                    END as lotNo")
                              )
                              ->join('cto_cashier AS cc',function($j){
                                $j->on('cc.id', '=', 'ccrp.cashier_id')->where('cc.status',1)->where('cc.tfoc_is_applicable',2);

                              })
                              ->leftJoin('cto_cashier_details as ccd',function($j){
                                $j->on('ccd.cashier_id','=','cc.id')
                                  ->join('rpt_cto_billing_details as cbd','cbd.id','=','ccd.cbd_code');
                              })
                              ->join('clients AS c', 'c.id', '=', 'cc.client_citizen_id')
                              ->join('rpt_properties AS rp', 'rp.id', '=', 'ccrp.rp_code')
                              ->join('rpt_property_kinds AS pk', 'pk.id', '=', 'rp.pk_id')
                              ->join('barangays AS bgy', 'bgy.id', '=', 'rp.brgy_code_id')
                              ->leftJoin('rpt_property_appraisals','rpt_property_appraisals.rp_code','=','rp.id')
                              ->leftJoin('rpt_property_machine_appraisals','rpt_property_machine_appraisals.rp_code','=','rp.id')
                              ->groupby('ccrp.rp_code');
      }
      public function getPaymentList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $id=$request->input('id');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }
        $columns = array( 
          1 => DB::raw("CASE 
                                    WHEN c.rpo_first_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                                    WHEN c.rpo_middle_name IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,'')))
                                    WHEN c.suffix IS NULL THEN TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,'')))
                                    WHEN c.rpo_first_name IS NULL AND c.rpo_middle_name IS NULL AND c.suffix IS NULL THEN COALESCE(c.rpo_custom_last_name,'')
                                    ELSE TRIM(CONCAT(COALESCE(c.rpo_first_name,''),' ',COALESCE(c.rpo_middle_name,''),' ',COALESCE(c.rpo_custom_last_name,''),', ',COALESCE(c.suffix,''))) END
                                    "),
          2 => "rp.rp_tax_declaration_no",
          3 => DB::raw('MIN(cbd.cbd_covered_year)'),
          4 => DB::raw("CASE 
                                    WHEN pk.pk_code = 'L' THEN SUM(COALESCE(rpt_property_appraisals.rpa_assessed_value,0)) 
                                    WHEN pk.pk_code = 'B' THEN COALESCE(rp.rpb_assessed_value,0)
                                    WHEN pk.pk_code = 'M' THEN SUM(COALESCE(rpt_property_machine_appraisals.rpm_assessed_value,0))
                                    END"),
          5 => "cc.or_no",
          6 => "cc.total_paid_amount",
          7 => "cc.cashier_or_date"
         );
          $sql = $this->paymentDetailsStandardQuery()->where('ccrp.rp_code',$id);
        /*if(!empty($q) && isset($q)){
                    $sql->orWhere(DB::raw('LOWER(rpt_property_tax_certs.rptc_control_no)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('DATE_FORMAT(rpt_property_tax_certs.rptc_date,"%d/%m/%Y")'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(clients.rpo_first_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(clients.rpo_custom_last_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(clients.rpo_middle_name)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(clients.suffix)'),'like',"%".strtolower($q)."%")
                        ->orWhere(DB::raw('LOWER(rpt_property_tax_certs.rptc_or_no)'),'like',"%".strtolower($q)."%");
                }*/

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column'])){
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
      }else{
          $sql->orderBy('ccrp.id','DESC');
      }
	  
	  
        /*  #######  Get count without limit  ###### */
        $data_cnt=count($sql->get());
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        //dd($data);
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }

      public function getTdsForAjaxSelectList($request=''){
       $term=$request->input('term');
       $rpocode=$request->input('rpo_code');
       $query = DB::table('rpt_properties as rp')->select('rp.id','rp.rp_tax_declaration_no as text')->where('rp.is_deleted',0)->whereIn('rp.pk_is_active',[1]);
        if(!empty($term) && isset($term)){
        $query->where(function ($sql) use($term) {   
            $sql->orWhere(DB::raw('LOWER(rp.rp_tax_declaration_no)'),'like',"%".strtolower($term)."%");
        });

        } 
        if(isset($rpocode) && $rpocode > 0){
            $query->where(function ($sql) use($rpocode) {   
                $sql->where('rp.rpo_code',$rpocode);
            });

        }
        $data = $query->simplePaginate(20);             
        return $data;
   }
}
