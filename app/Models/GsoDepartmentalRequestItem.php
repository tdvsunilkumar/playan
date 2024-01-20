<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoDepartmentalRequestItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_departmental_requests_items';
    
    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo('App\Models\GsoDepartmentalRequisition', 'departmental_request_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }
}
