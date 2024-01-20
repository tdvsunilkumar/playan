<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptCtoBillingDetail extends Model
{
    use HasFactory;

    public $table = 'rpt_cto_billing_details';

    public function rptProperty(){
        return $this->belongsTo(RptProperty::class,'rp_code');
    }

    public function billingPenaltyDetails(){
        return $this->belongsTo(RptCtoBillingDetailsPenalty::class,'cb_code','cb_code');
    }

    public function billingDiscountDetails(){
        return $this->belongsTo(RptCtoBillingDetailsDiscount::class,'cbd_covered_year','cbd_covered_year');
    }
}
