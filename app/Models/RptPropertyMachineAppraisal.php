<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RptPropertyMachineAppraisal extends Model
{
    use HasFactory;

    protected $table = 'rpt_property_machine_appraisals';

    protected $fillable = [
        
        'rp_code',
        'rp_property_code',
        'pk_code',
        'rvy_revision_year',
        'rvy_revision_code',
        'rpma_description',
        'rpma_brand_model',
        'rpma_capacity_hp',
        'rpma_date_acquired',
        'rpma_condition',
        'rpma_estimated_life',
        'rpma_remaining_life',
        'rpma_date_installed',
        'rpma_date_operated',
        'rpma_remarks',
        'rpma_appr_no_units',
        'rpma_acquisition_cost',
        'rpma_freight_cost',
        'rpma_insurance_cost',
        'rpma_installation_cost',
        'rpma_other_cost',
        'rpma_base_market_value',
        'rpma_depreciation_rate',
        'rpma_date_operated',
        'rpma_depreciation',
        'rpma_market_value',
        'pc_class_code',
        'pau_actual_use_code',
        'al_assessment_level',
        'rpm_assessed_value',
        'rpma_registered_by',
        'rpma_modified_by',
    ];


    protected $appends = ['pc_class_description','subdivision_used_units'];

    // public function getPcClassDescriptionAttribute()
    // {
    //     return $this->class->pc_class_code.'-'.$this->class->pc_class_description;    
    // }
    public function getSubdivisionUsedUnitsAttribute()
    {
        $totalparentApprasalUnits    = DB::table('rpt_property_machine_appraisals')->where('id',$this->id)->first();
        $usedAppraisalUnits = DB::table('rpt_properties')
                              ->select(DB::raw('SUM(rpt_property_machine_appraisals.rpma_appr_no_units) as totalUsedUnits'))
                              ->join('rpt_property_machine_appraisals',function($j)use($totalparentApprasalUnits){
                               $j->on('rpt_property_machine_appraisals.rp_code','=','rpt_properties.id')->where('rpt_property_machine_appraisals.created_against',(isset($totalparentApprasalUnits->id))?$totalparentApprasalUnits->id:'');
                              })
                              ->where('rpt_properties.created_against',(isset($totalparentApprasalUnits->rp_code))?$totalparentApprasalUnits->rp_code:'')->first();
        return (isset($usedAppraisalUnits->totalUsedUnits) && $usedAppraisalUnits->totalUsedUnits != null)?$usedAppraisalUnits->totalUsedUnits:0;    
    }

    public function getPcClassDescriptionAttribute()
    {
        return $this->class->pc_class_description;    
    }
    public function actualUses(){
        return $this->belongsTo(RptPropertyActualUse::class,'pau_actual_use_code');
    }

    public function class(){
        return $this->belongsTo(RptPropertyClass::class,'pc_class_code');
    }
}
