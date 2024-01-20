<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPermitFees extends Model
{
    public $table = 'ho_req_per_details';
    public $timestamps = false;
    protected $fillable = ['req_permit_id','requestor_id','service_id','tfoc_id','agl_account_id','sl_id','permit_fee','is_free','updated_at'];
    public function desc()
    {
        return $this->hasOne(HealthSafetySetupDataService::class, 'id', 'service_id');
    }
}