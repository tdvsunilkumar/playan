<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_purchase_requests';
    
    public $timestamps = false;

    public function requisition()
    {
        return $this->belongsTo('App\Models\GsoDepartmentalRequisition', 'departmental_request_id', 'id');
    }

    public function allotment()
    {
        return $this->hasOne('App\Models\CboAllotmentObligation', 'departmental_request_id', 'departmental_request_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
