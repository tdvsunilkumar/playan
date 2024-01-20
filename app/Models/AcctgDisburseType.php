<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgDisburseType extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_disburse_types';
    
    public $timestamps = false;
}
