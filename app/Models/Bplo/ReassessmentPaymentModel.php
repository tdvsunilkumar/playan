<?php

namespace App\Models\Bplo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ReassessmentPaymentModel extends Model
{
    public function updateActiveInactive($id,$columns){
     return DB::table('cto_bplo_re_assessment_payment_mode')->where('id',$id)->update($columns);
    }  
    public function updateData($id,$columns){
        return DB::table('cto_bplo_re_assessment_payment_mode')->where('id',$id)->update($columns);
    }
    public function addData($postdata){
        DB::table('cto_bplo_re_assessment_payment_mode')->insert($postdata);
        return DB::getPdo()->lastInsertId();
    }
    
    public function getEditDetails($id){
        return DB::table('cto_bplo_re_assessment_payment_mode')->where('id',$id)->first();
    }
	
	public function getEditDetailssss(){
        return DB::table('cto_bplo_re_assessment_payment_mode')->first();
    }
}
