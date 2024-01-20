<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyBuildingFloorAdItem extends Model
{
    use HasFactory;

    protected $table = 'rpt_property_building_floor_ad_items';

    protected $fillable = [
        
        'rp_code',
        'rp_property_code',
        'rpbfv_code',
        'bei_extra_item_code',
        'bei_extra_item_desc',
        'rpbfai_total_area',
        'rpbfai_registered_by',
        'rpbfai_modified_by',
    ];
}
