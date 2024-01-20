<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyHistory extends Model
{
    use HasFactory;

    public function activeProp(){
        return $this->belongsTo(RptProperty::class,'rp_code_active');
    }

    public function cancelProp(){
        return $this->belongsTo(RptProperty::class,'rp_code_cancelled');
    }
}
