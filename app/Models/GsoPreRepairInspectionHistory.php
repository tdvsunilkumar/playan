<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPreRepairInspectionHistory extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'gso_pre_repair_inspection_history';
    
    public $timestamps = false;

    public function request()
    {
        return $this->belongsTo('App\Models\GsoPreRepairInspectionRequest', 'repair_id', 'id');
    }
}
