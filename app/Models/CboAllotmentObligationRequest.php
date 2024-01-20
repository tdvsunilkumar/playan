<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CboAllotmentObligationRequest extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cbo_allotment_obligations_requests';
    
    public $timestamps = false;

    public function obligation()
    {
        return $this->belongsTo('App\Models\CboAllotmentObligation', 'allotment_id', 'id');
    }

    public function budget_officer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'budget_officer_id', 'id');
    }

    public function treasurer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'treasurer_id', 'id');
    }

    public function mayor()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'mayor_id', 'id');
    }
}
