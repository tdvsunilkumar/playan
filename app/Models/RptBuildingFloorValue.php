<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptBuildingFloorValue extends Model
{
    use HasFactory;

    protected $table = 'rpt_building_floor_values';

    protected $fillable = [
        
        'rp_code',
        'rp_property_code',
        'rpbfv_floor_no',
        'rpbfv_total_floor',
        'bt_building_type_code',
        'pau_actual_use_code',
        'rpbfv_floor_unit_value',
        'rpbfv_floor_area',
        'rpbfv_floor_base_market_value',
        'rpbfv_floor_additional_value',
        'rpbfv_floor_adjustment_value',
        'rpbfv_total_floor_market_value',
        'al_assessment_level',
        'rpb_assessed_value',
        'rpbfv_registered_by',
        'rpbfv_modified_by',
    ];

    protected $appends = ['bt_building_type_code_desc','pau_actual_use_code_desc'];

    public function getBtBuildingTypeCodeDescAttribute($value=''){
    	return $this->buildingType->bt_building_type_code.'-'.$this->buildingType->bt_building_type_desc;
    }

    public function getPauActualUseCodeDescAttribute($value=''){
    	return $this->actualUses->pau_actual_use_code.'-'.$this->actualUses->pau_actual_use_desc;
    }

    public function actualUses(){
        return $this->belongsTo(RptPropertyActualUse::class,'pau_actual_use_code');
    }

    public function buildingType(){
        return $this->belongsTo(RptBuildingType::class,'bt_building_type_code');
    }

    public function additionalItems($value=''){
        return $this->hasMany(RptPropertyBuildingFloorAdItem::class,'rpbfv_code');
    }

    
}
