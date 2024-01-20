<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoDisburseDetail extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_disburse_details';
    
    public $timestamps = false;

    public function disburse()
    {
        return $this->belongsTo('App\Models\AcctgVoucher', 'disburse_id', 'id');
    }

    public function obligation()
    {
        return $this->belongsTo('App\Models\CboAllotmentObligation', 'obligation_id', 'id');
    }
}
