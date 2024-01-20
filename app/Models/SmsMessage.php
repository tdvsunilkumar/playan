<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_messages';
    
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo('App\Models\SmsType', 'type_id', 'id');
    }

    public function setting()
    {
        return $this->belongsTo('App\Models\SmsSetting', 'setting_id', 'id');
    }
}
