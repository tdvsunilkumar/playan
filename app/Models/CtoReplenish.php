<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoReplenish extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_replenish';
    
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'department_id', 'id');
    }

    public function replenishments()
    {   
        return $this->hasMany('App\Models\CtoReplenishDetail', 'replenish_id', 'id');
    }

    public function obligation()
    {
        return $this->belongsTo('App\Models\CboAllotmentObligation', 'allotment_id', 'id');
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
