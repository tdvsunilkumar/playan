<?php

namespace App\Models\Engneering;

use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;

class EngJobReqSanitary extends Model
{
    public $table = 'eng_sanitary_plumbing_apps';
    
    // permit
    public function permit() 
    {
        return $this->hasOne(EngBldgPermitApp::class, 'id', 'ebpa_permit_no');
    }

    // Type of Occupancy
    public function bldgOccupancyType() 
    {
        return $this->hasOne(EngBldgOccupancyType::class, 'id', 'ebot_id');
    }
    public function getOccupancyTypeAttribute()
    {
        return ($this->bldgOccupancyType)? $this->bldgOccupancyType->ebot_description : '';
    }
    public function occupancyTypeCheck($check)
    {
        return $this->occupancy_type === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }

    // Building Scope of work
    public function signBldgScope() 
    {
        return $this->hasOne(EngBldgScope::class, 'id', 'ebs_id');
    }
    public function getBldgScopeAttribute()
    {
        return ($this->signBldgScope)? $this->signBldgScope->ebs_description : '';
    }
    public function bldgScopeCheck($check)
    {
        return $this->bldg_scope === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }
    public function bldgRemarks($check)
    {
        return $this->bldg_scope === $check ? ' OF <b>'.$this->ebpa_scope_remarks.'</b>': '';
    }

    // System of Disposal Type
    public function disposalSystemType() 
    {
        return $this->hasOne(EngDisposalSystemType::class, 'id', 'edst_id');
    }
    public function getDisposalTypeAttribute()
    {
        return ($this->disposalSystemType)? $this->disposalSystemType->edst_description : '';
    }
    public function disposalTypeCheck($check)
    {
        return $this->disposal_type === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }

    // Water Supply Type
    public function waterSupplyType() 
    {
        return $this->hasOne(EngWaterSupplyType::class, 'id', 'ewst_id');
    }
    public function getWaterTypeAttribute()
    {
        return ($this->waterSupplyType)? $this->waterSupplyType->ewst_description : '';
    }
    public function waterTypeCheck($check)
    {
        return $this->water_type === $check ?config('constants.checkbox.checked'): config('constants.checkbox.unchecked');
    }
    
    // applicant consultant
    public function applicantConsultant() 
    { 
        return $this->hasOne(EngClients::class, 'id', 'espa_applicant_consultant_id');
    }

    // Project Consultant
    public function consultant() 
    { 
        switch ($this->espa_sign_category) {
            case 1:
                return $this->belongsTo(HrEmployee::class, 'espa_sign_consultant_id', 'id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'espa_sign_consultant_id', 'id'); 
                break;
                
            default:
                return $this->belongsTo(ConsultantExternal::class, 'espa_sign_consultant_id', 'id'); 
                break;
        }
    }

    // Project Incharge
    public function incharge() 
    { 
        switch ($this->espa_incharge_category) {
            case 1:
                return $this->belongsTo(HrEmployee::class, 'espa_incharge_consultant_id', 'id'); 
                break;
            
            case 2:
                return $this->belongsTo(ConsultantExternal::class, 'espa_incharge_consultant_id', 'id'); 
                break;
                
            default:
                return $this->belongsTo(ConsultantExternal::class, 'espa_incharge_consultant_id', 'id'); 
                break;
        }
    }

    // Building official
    public function official() 
    { 
        return $this->belongsTo(HrEmployee::class, 'espa_building_official', 'id'); 
    }

    // public function preparedBy() 
    // { 
    //     return $this->belongsTo(HrEmployee::class, 'espa_building_official', 'id'); 
    // }
    public function assessedBy() 
    { 
        return $this->belongsTo(HrEmployee::class, 'espa_assessed_by', 'id'); 
    }
}
