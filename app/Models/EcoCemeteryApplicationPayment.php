<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoCemeteryApplicationPayment extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_cemetery_application_payments';
    
    public $timestamps = false;

    public function application()
    {
        return $this->belongsTo('App\Models\EcoCemeteryApplication', 'cemetery_application_id', 'id');
    }

    public function citizen()
    {
        return $this->belongsTo('App\Models\Citizen', 'citizen_id', 'id');
    }
}
