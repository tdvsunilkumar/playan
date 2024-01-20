<?php

namespace App\Models\LocalServer;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $connection= 'local_db';
    protected $guarded = ['id'];
}
