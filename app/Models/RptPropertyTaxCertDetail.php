<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyTaxCertDetail extends Model
{
    use HasFactory;

    public function realProperty($value=''){
        return $this->belongsTo(RptProperty::class,'rp_code');
    }
}
