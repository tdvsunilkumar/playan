<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptLocality extends Model
{
    protected $guarded = ['id'];

    public $table = 'rpt_locality';
    
    public $timestamps = false;

    public function budget_officer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'loc_budget_officer_id', 'id');
    }

    public function treasurer()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'loc_treasurer_id', 'id');
    }

    public function assessor()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'loc_assessor_id', 'id');
    }

    public function mayor()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'loc_mayor_id', 'id');
    }
}
