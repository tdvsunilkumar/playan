<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $guarded = ['id'];

    public $table = 'files';
    
    public $timestamps = false;
}
