<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;
use App\Models\HrEmployeeDepartmentalAccess;

class GsoDepartmentalRequestTrackingStatus extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_departmental_requests_tracking_status';
    
    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo('App\Models\GsoDepartmentalRequisition', 'departmental_request_id', 'id');
    }
}
