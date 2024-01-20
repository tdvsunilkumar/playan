<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoDisburse extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_disburse';
    
    public $timestamps = false;

    public function voucher()
    {
        return $this->belongsTo('App\Models\AcctgVoucher', 'voucher_id', 'id');
    }

    public function payee()
    {
        return $this->belongsTo('App\Models\CboPayee', 'payee_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }

    public function obligations()
    {   
        return $this->hasMany('App\Models\CtoPettyCashDetail', 'disburse_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
