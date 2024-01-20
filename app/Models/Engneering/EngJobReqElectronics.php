<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqElectronics extends Model
{
    public $table = 'eng_electronics_app';
    // Project Consultant
    public function consultant() 
    { 
        switch ($this->eeta_sign_category) {
            case 1:
                return $this->belongsTo(HrEmployee::class, 'eeta_sign_consultant_id', 'id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'eeta_sign_consultant_id', 'id'); 
                break;

            default:
                return $this->belongsTo(ConsultantExternal::class, 'eeta_sign_consultant_id', 'id'); 
                break;
        }
    }

    // Type of Occupancy
    // $data->details->bldgOccupancyType = occupancy data
    public function bldgOccupancyType() 
    {
        return $this->hasOne(EngBldgOccupancyType::class, 'id', 'ebot_id');
    }
    public function getOccupancyTypeAttribute()
    {
        return ($this->bldgOccupancyType)? $this->bldgOccupancyType->ebot_description : '';
    }
}
