<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSchedule extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'sms_schedules';
    
    public $timestamps = false;
}
