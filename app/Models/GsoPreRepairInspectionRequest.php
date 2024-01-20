<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPreRepairInspectionRequest extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_pre_repair_inspection_requests';
    
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'requested_by', 'id');
    }

    public function property()
    {
        return $this->belongsTo('App\Models\GsoPropertyAccountability', 'property_id', 'id');
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
