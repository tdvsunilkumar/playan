<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoTaxInterestSurcharge extends Model
{
    public function updateData($id,$columns){
        return DB::table('cto_tax_interest_surcharges')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_tax_interest_surcharges')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails(){
        return DB::table('cto_tax_interest_surcharges')->orderBy('id', 'ASC')->first();
    }

    public function getSchedule(){
        return DB::table('cto_data_schedule')->select('id','ds_schedule')->where('ds_is_active',1)->orderBy('ds_schedule', 'ASC')->get()->toArray();
    }
    public function getFormula(){
        return DB::table('cto_data_formulas')->select('id','df_desc')->where('ds_is_active',1)->orderBy('df_desc', 'ASC')->get()->toArray();
    }
    public function getComputeMode(){
        return DB::table('cto_data_compute_modes')->select('id','dcm_desc')->where('dcm_is_active',1)->orderBy('dcm_desc', 'ASC')->get()->toArray();
    }

    public function getList(){
        $sql="SELECT tis_interest_amount,tis_interest_rate_type,tis_interest_max_month,tis_surcharge_amount,tis_surcharge_rate_type, 
            (SELECT ds_schedule FROM cto_data_schedule AS ds WHERE ds.id=tis_interest_schedule) AS tis_interest_schedule, 
            (SELECT ds_schedule FROM cto_data_schedule AS ds1 WHERE ds1.id=tis_surcharge_schedule) AS tis_surcharge_schedule, 

            (SELECT df_desc FROM cto_data_formulas AS df WHERE df.id=tis_interest_formula) AS tis_interest_formula, 
            (SELECT df_desc FROM cto_data_formulas AS df1 WHERE df1.id=tis_surcharge_formula) AS tis_surcharge_formula,

            (SELECT dcm_desc FROM cto_data_compute_modes AS cm WHERE cm.id=tis_interest_compute_mode) AS tis_interest_compute_mode, 
            (SELECT dcm_desc FROM cto_data_compute_modes AS cm1 WHERE cm1.id=tis_surcharge_compute_mode) AS tis_surcharge_compute_mode

            FROM cto_tax_interest_surcharges AS isc ORDER BY isc.id ASC ";
        return DB::select($sql);
    }
}

