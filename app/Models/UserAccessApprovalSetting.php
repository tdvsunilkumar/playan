<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessApprovalSetting extends Model
{
    protected $guarded = ['id'];

    public $table = 'user_access_approval_settings';
    
    public $timestamps = false;

    public function module()
    {
        return $this->belongsTo('App\Models\MenuModule', 'module_id', 'id');
    }

    public function sub_module()
    {
        return $this->belongsTo('App\Models\MenuSubModule', 'sub_module_id', 'id');
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
