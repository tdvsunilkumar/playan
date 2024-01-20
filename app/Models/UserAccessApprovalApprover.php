<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessApprovalApprover extends Model
{
    protected $guarded = ['id'];

    public $table = 'user_access_approval_approvers';
    
    public $timestamps = false;

    public function module()
    {
        return $this->belongsTo('App\Models\UserAccessApprovalSetting', 'setting_id', 'id');
    }
}
