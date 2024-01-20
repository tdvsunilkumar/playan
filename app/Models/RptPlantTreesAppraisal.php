<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPlantTreesAppraisal extends Model
{
    use HasFactory;
    public $table = 'rpt_plant_trees_appraisals';

    protected $fillable = [
        
        'rp_code',
        'rp_property_code',
        'rpa_code',
        'rp_planttree_code',
        'rvy_revision_year',
        'rvy_revision_code',
        'pc_class_code',
        'ps_subclass_code',
        'rpta_total_area_planted',
        'rpta_non_fruit_bearing',
        'rpta_fruit_bearing_productive',
        'rpta_fruit_bearing_non_productive',
        'rpta_date_planted',
        'rpta_unit_value',
        'rpta_market_value',
        'rpta_taxable',
        'rpta_registered_by',
        'rpta_modified_by',
    ];

    public function class(){
        return $this->belongsTo(RptPropertyClass::class,'pc_class_code');
    }

    public function subClass(){
        return $this->belongsTo(RptPropertySubclassification::class,'ps_subclass_code');
    }

    public function getPcClassDescriptionAttribute()
    {
        // return $this->class->pc_class_code.'-'.$this->class->pc_class_description;
        return $this->class->pc_class_description;    
    }

    public function getPsSubclassDescAttribute()
    {
        // return $this->subClass->ps_subclass_code.'-'.$this->subClass->ps_subclass_desc;    
        return $this->subClass->ps_subclass_desc;
    }

    public function palntTreeCode()
    {
        // return $this->subClass->ps_subclass_code.'-'.$this->subClass->ps_subclass_desc;    
        return $this->belongsTo(PlantTress::class,'rp_planttree_code');
    }
}
