<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptCtoTaxRevenue extends Model
{
    use HasFactory;

    public function addData($postdata){
        DB::table('rpt_cto_tax_revenues')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }

     public function updateData($id,$columns){
        return DB::table('rpt_cto_tax_revenues')->where('id',$id)->update($columns);
    }

    public function basicTaxFeeDetails($value=''){
        return $this->belongsTo(Engneering\CtoTfoc::class,'basic_tfoc_id');
    }

    public function sefTaxFeeDetails($value=''){
        return $this->belongsTo(Engneering\CtoTfoc::class,'sef_tfoc_id');
    }

    public function shTaxFeeDetails($value=''){
        return $this->belongsTo(Engneering\CtoTfoc::class,'sh_tfoc_id');
    }

    // public function getTaxFeeAndOtherCharges($value = ''){
    //     $sql = DB::table('cto_tfocs')
    //     //->join('acctg_fund_codes', 'acctg_fund_codes.id', '=', 'cto_tfocs.fund_id')
    //     ->join('cto_charge_types', 'cto_charge_types.id', '=', 'cto_tfocs.ctype_id')
    //     ->join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', '=', 'cto_tfocs.gl_account_id')
    //     ->join('acctg_account_subsidiary_ledgers', 'acctg_account_subsidiary_ledgers.id', '=', 'cto_tfocs.sl_id')
    //     ->select('cto_tfocs.*','cto_charge_types.ctype_desc','acctg_account_general_ledgers.prefix','acctg_account_general_ledgers.code as glcode','acctg_account_general_ledgers.description as gldesc','acctg_account_subsidiary_ledgers.prefix as subsidarycode','acctg_account_subsidiary_ledgers.description as subsidarydesc')
    //     ->where('cto_tfocs.tfoc_is_applicable',2)
    //     ->where('cto_tfocs.tfoc_status',1)
    //     ->where('acctg_account_subsidiary_ledgers.is_parent',0)
    //     ->where('acctg_account_subsidiary_ledgers.is_hidden',0)
    //     ->where('cto_tfocs.tfoc_usage_real_property',1);
    //     if($value != ""){
    //         $sql->where('cto_tfocs.id',$value);
    //         $data = $sql->first();
    //     }else{
    //        $data = $sql->get();
    //    }
        
    //     return $data;
    // }
    public function getTaxFeeAndOtherCharges($value = ''){
        $sql = DB::table('cto_tfocs')
        //->join('acctg_fund_codes', 'acctg_fund_codes.id', '=', 'cto_tfocs.fund_id')
        // ->join('cto_charge_types', 'cto_charge_types.id', '=', 'cto_tfocs.ctype_id')
        ->join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', '=', 'cto_tfocs.gl_account_id')
        ->join('acctg_account_subsidiary_ledgers', 'acctg_account_subsidiary_ledgers.id', '=', 'cto_tfocs.sl_id')
        ->select('cto_tfocs.*','acctg_account_general_ledgers.prefix','acctg_account_general_ledgers.code as glcode','acctg_account_general_ledgers.description as gldesc','acctg_account_subsidiary_ledgers.prefix as subsidarycode','acctg_account_subsidiary_ledgers.description as subsidarydesc')
        ->where('cto_tfocs.tfoc_is_applicable',2)
        ->where('cto_tfocs.tfoc_status',1)
        ->where('acctg_account_subsidiary_ledgers.is_parent',0)
        ->where('acctg_account_subsidiary_ledgers.is_hidden',0)
        ->where('cto_tfocs.tfoc_usage_real_property',1);
        if($value != ""){
            $sql->where('cto_tfocs.id',$value);
            $data = $sql->first();
        }else{
           $data = $sql->get();
       }
        
        return $data;
    }
    

     public function getCreditFeeAndOtherCharges($value = ''){
        $sql = DB::table('cto_tax_credit_management')
        ->join('acctg_account_general_ledgers', 'acctg_account_general_ledgers.id', '=', 'cto_tax_credit_management.tcm_gl_id')
        ->join('acctg_account_subsidiary_ledgers', 'acctg_account_subsidiary_ledgers.id', '=', 'cto_tax_credit_management.tcm_sl_id')
        ->select('cto_tax_credit_management.*','acctg_account_general_ledgers.prefix','acctg_account_general_ledgers.code as glcode','acctg_account_general_ledgers.description as gldesc','acctg_account_subsidiary_ledgers.prefix as subsidarycode','acctg_account_subsidiary_ledgers.description as subsidarydesc')
        //->where('cto_tax_credit_management.tfoc_is_applicable',2)
        ->where('cto_tax_credit_management.tcm_status',1)
        ->where('acctg_account_subsidiary_ledgers.is_hidden',0);
        if($value != ""){
            $sql->where('cto_tax_credit_management.id',$value);
            $data = $sql->first();
        }else{
           $data = $sql->get();
       }
        
        return $data;
    }

    public function getList($request){
        $params = $columns = $totalRecords = $data = array();
        $params = $_REQUEST;
        $q=$request->input('q');
        $pk_code=$request->input('pk_code');
        if(!isset($params['start']) && !isset($params['length'])){
          $params['start']="0";
          $params['length']="10";
        }

        $columns = array( 
          0 =>"mun_no",  
          1 =>"pk_description",  
          2 =>"trev_name",
          3 =>"trev_description",
          4 =>"tax_what_year",
          5 =>"pk_description",  
          6 =>"basic_tfoc_id",
          7 =>"basic_discount_tfoc_id",
          8 =>"basic_penalty_tfoc_id",
          9 =>"sef_tfoc_id",
          10 =>"sef_discount_tfoc_id",
          11=>"sef_penalty_tfoc_id",
          12=>"sh_tfoc_id",
          13=>"sh_discount_tfoc_id",
          14=>"sh_penalty_tfoc_id"
           
        );
        $sql = DB::table('rpt_cto_tax_revenues AS tr')
        ->join('rpt_cto_tax_revenue_names AS rn', 'rn.id', '=', 'tr.trev_id')
        ->leftJoin('rpt_property_kinds AS pk', 'pk.id', '=', 'tr.pk_id')
        ->select('tr.*','rn.trev_name','rn.trev_description','pk.pk_description');
       
        if(!empty($q) && isset($q)){
            $sql->where(function ($sql) use($q) {
               $sql->where(DB::raw('LOWER(rn.trev_name)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(rn.trev_description)'),'like',"%".strtolower($q)."%")
                    ->orWhere(DB::raw('LOWER(pk.pk_description)'),'like',"%".strtolower($q)."%");
                    //->orWhere(DB::raw('LOWER(dist.dist_name)'),'like',"%".strtolower($q)."%");
                  
            });
        }

        

        if(!empty($pk_code) && isset($pk_code)){
            $sql->where(function ($sql) use($pk_code) {
               $sql->where('tr.pk_id',$pk_code);
                  
            });
        }

        /*  #######  Set Order By  ###### */
        if(isset($params['order'][0]['column']))
          $sql->orderBy($columns[$params['order'][0]['column']],$params['order'][0]['dir']);
        else
          $sql->orderBy('tr.id','ASC');

        /*  #######  Get count without limit  ###### */
        $data_cnt=$sql->count();
        /*  #######  Set Offset & Limit  ###### */
        $sql->offset((int)$params['start'])->limit((int)$params['length']);
        $data=$sql->get();
        return array("data_cnt"=>$data_cnt,"data"=>$data);
      }
}
