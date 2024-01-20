<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoLabReqFees extends Model
{
    public $table = 'ho_lab_fees';
    public $timestamps = false;
    protected $fillable = ['lab_req_id','service_id','hlf_service_name','cit_id','hlf_fee','hlf_is_free','lab_control_no','tfoc_id','agl_account_id','sl_id','top_transaction_type_id','updated_at'];
    public function desc()
    {
        return $this->hasOne(HealthSafetySetupDataService::class, 'id', 'service_id');
    }
    public function lab_req()
    {
        return $this->belongsTo(HoLabRequest::class, 'lab_req_id', 'id');
    }
}