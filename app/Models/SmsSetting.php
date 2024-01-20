<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_settings';
    
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo('App\Models\SmsType', 'type_id', 'id');
    }

    public function mask()
    {
        return $this->belongsTo('App\Models\SmsMasking', 'shortcode_mask', 'id');
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
