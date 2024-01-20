<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsServerSetting extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_server_settings';
    
    public $timestamps = false;

    public function mask()
    {
        return $this->belongsTo('App\Models\SmsMasking', 'masking_id', 'id');
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
