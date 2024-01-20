<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyAppraisal extends Model
{
    use HasFactory;

    public $table = 'rpt_property_appraisals';

    protected $fillable = [
        
        'rp_code',
        'rp_property_code',
        'pk_code',
        'rvy_revision_year',
        'rvy_revision_code',
        'pc_class_code',
        'ps_subclass_code',
        'pau_actual_use_code',
        'lav_unit_value',
        'rpa_total_land_area',
        'lav_unit_measure',
        'rls_code',
        'rls_percent',
        'lav_strip_unit_value',
        'rpa_base_market_value',
        'rpa_adjusted_market_value',
        'al_assessment_level',
        'rpa_assessed_value',
        'rpa_taxable',
        'rpa_adjustment_factor_a',
        'rpa_adjustment_factor_b',
        'rpa_adjustment_factor_c',
        'rpa_adjustment_percent',
        'rpa_adjustment_value',
        'rpa_registered_by',
        'rpa_modified_by',
        
    ];

    protected $appends = ['pc_class_description','ps_subclass_desc','pau_actual_use_desc','remaining_area'];

    public function getPcClassDescriptionAttribute()
    {
        // return $this->class->pc_class_code.'-'.$this->class->pc_class_description;
        return $this->class->pc_class_description;    
    }

    public function getPsSubclassDescAttribute()
    {
        // return $this->subClass->ps_subclass_code.'-'.$this->subClass->ps_subclass_desc;  
        return (isset($this->subClass->ps_subclass_desc))?$this->subClass->ps_subclass_desc:'';    
    }


    public function getPauActualUseDescAttribute()
    {
        // return $this->actualUses->pau_actual_use_code.'-'.$this->actualUses->pau_actual_use_desc;   
        return $this->actualUses->pau_actual_use_desc; 
    }

    public function class(){
        return $this->belongsTo(RptPropertyClass::class,'pc_class_code');
    }

    public function subClass(){
        return $this->belongsTo(RptPropertySubclassification::class,'ps_subclass_code');
    }

    public function actualUses(){
        return $this->belongsTo(RptPropertyActualUse::class,'pau_actual_use_code');
    }

    public function rptProperty(){
        return $this->belongsTo(RptProperty::class,'rp_code');
    }

    public function plantTreeAppraisals(){
        return $this->hasMany(RptPlantTreesAppraisal::class,'rpa_code');
    }

    public function canHaveRptProperty(){
        return $this->hasMany(RptProperty::class,'created_against_appraisal');
    }

    public function getRemainingAreaAttribute(){
        $taxDec = $this->canHaveRptProperty->where('is_deleted',1)->where('rp_registered_by',\Auth::User()->creatorId());
        $totalOcupiedLandArea = 0;
        foreach ($taxDec as  $dec) {
            $landAreaOfTaxDec = $dec->landAppraisals->sum('rpa_total_land_area');
            $totalOcupiedLandArea += $landAreaOfTaxDec;
        }
        return $totalOcupiedLandArea;
    }

}
