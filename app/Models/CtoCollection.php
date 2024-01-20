<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtoCollection extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'cto_collections';
    
    public $timestamps = false;

    public function fund()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function officer()
    {
        return $this->belongsTo('App\Models\User', 'officer_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'officer_id', 'user_id');
    }

    public function denominations()
    {
        return $this->hasMany('App\Models\CtoCollectionDetail', 'collection_id', 'id');
    }

    public function approved()
    {
        return $this->belongsTo('App\Models\User', 'approved_by', 'id');
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
