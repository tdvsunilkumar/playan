<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPropertyAccountabilityHistory extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_property_accountabilities_history';
    
    public $timestamps = false;

    public function property()
    {
        return $this->belongsTo('App\Models\GsoPropertyAccountability', 'property_id', 'id');
    }

    public function acquiree()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'acquired_by', 'id');
    }

    public function issuer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'issued_by', 'id');
    }    

    public function returnee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'returned_by', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'received_by', 'id');
    }
}
