<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BploAssessPenaltyRates extends Model
{
    use HasFactory;
    public function updateData($id,$columns){
	    return DB::table('bplo_assess_penalty_rates')->where('id',$id)->update($columns);
	}
	public function addData($postdata){
			return DB::table('bplo_assess_penalty_rates')->insert($postdata);
	}
	public function addPenaltyRatelogData($postdata){
			return DB::table('bplo_assess_penalty_rate_logs')->insert($postdata);
	}

}
