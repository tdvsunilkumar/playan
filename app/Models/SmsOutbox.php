<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsOutbox extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_outbox';
    
    public $timestamps = false;

    public function message()
    {
        return $this->belongsTo('App\Models\SmsMessage', 'message_id', 'id');
    }
}
