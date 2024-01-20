<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobs extends Model
{
    use HasFactory;
    
    public $table = 'cron_jobs';
}
