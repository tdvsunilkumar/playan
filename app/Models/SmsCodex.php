<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCodex extends Model
{
    protected $guarded = ['id'];

    public $table = 'sms_codex';
    
    public $timestamps = false;
}
