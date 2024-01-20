<?php

namespace App\Models\LocalServer;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];
    public $table = 'hr_bio_attendance';
    protected $connection= 'local_db';
}
