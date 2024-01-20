<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CtoCashier extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_cashier';
    
    public $timestamps = false;
    public function getLastPayment($busn_id,$year){
        return DB::table('cto_cashier AS cc')
             ->Leftjoin('cto_top_transactions AS tt', 'tt.id', '=', 'cc.top_transaction_id')
             ->select('or_no','cashier_or_date','total_paid_amount','transaction_no','tt.created_at AS assessment_date','total_amount')->where('busn_id',$busn_id)->where('cashier_year',$year)->orderBy('cc.id','desc')->first();
    }
}
