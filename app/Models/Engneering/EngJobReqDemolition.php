<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqDemolition extends Model
{
    public $table = 'eng_demolition_app';
    // Project Consultant
    public function consultant() 
    { 
        // dd($this);
        switch ($this->eda_incharge_category) {
            case 1:
                return $this->belongsTo(HrEmployee::class, 'eda_incharge_consultant_id', 'id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'eda_incharge_consultant_id', 'id'); 
                break;
                
            default:
                return $this->belongsTo(ConsultantExternal::class, 'eda_incharge_consultant_id', 'id'); 
                break;
        }
    }

    public function bldgOccupancyType() 
    {
        return $this->hasOne(EngBldgOccupancyType::class, 'id', 'ebot_id');
    }
    public function getOccupancyTypeAttribute()
    {
        return ($this->bldgOccupancyType)? $this->bldgOccupancyType->ebot_description : '';
    }
}
