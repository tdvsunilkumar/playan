<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoDepartmentalRequestDisapproval extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_departmental_requests_disapprovals';
    
    public $timestamps = false;

    public function requisition()
    {
        return $this->belongsTo('App\Models\GsoDepartmentalRequisition', 'departmental_request_id', 'id');
    }
}
