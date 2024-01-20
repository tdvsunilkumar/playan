<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','rp_code','rp_property_code','pk_code','rp_app_appraised_by','rp_app_appraised_date','rp_app_appraised_is_signed','rp_app_assessed_by','rp_app_assessed_date','rp_app_recommend_by','rp_app_recommend_date','rp_app_recommend_is_signed','rp_app_approved_by','rp_app_approved_date','rp_app_approved_is_signed','rp_app_cancel_is_direct','rp_app_cancel_by','rp_app_cancel_type','rp_app_cancel_date','rp_app_cancel_by_td_no','rp_app_cancel_remarks'
    ];

    public $table = 'rpt_property_approvals';

    public function use(){
        return $this->belongsTo(User::class,'rp_app_cancel_by');
    }
    public function recommendBy(){
        return $this->belongsTo(User::class,'rp_app_recommend_by');
    }
    public function approveBy(){
        return $this->belongsTo(HrEmployee::class,'rp_app_approved_by');
    }
    public function apprisedBy(){
        return $this->belongsTo(User::class,'rp_app_appraised_by');
    }
    
}
