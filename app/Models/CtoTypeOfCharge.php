<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class CtoTypeOfCharge extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
}
