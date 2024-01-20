<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsPrefix extends Model
{
    protected $guarded = ['id'];

    public $table = 'sms_prefixes';
    
    public $timestamps = false;
}
