<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyStatus extends Model
{
    use HasFactory;

    protected $table = 'rpt_property_statuses';

    protected $fillable = [
        
        'rp_code',
        'pk_code',
        'rpss_mciaa_property',
        'rpss_peza_property',
        'rpss_beneficial_use',
        'rpss_beneficial_user_code',
        'rpss_beneficial_user_name',
        'rpss_is_mortgaged',
        'rpss_is_mortgaged_date',
        'rpss_is_levy',
        'rpss_is_auction',
        'rpss_is_protest',
        'rpss_is_protest_date',
        'rpss_is_idle_land',
        'rpss_mortgage_amount',
        'rpss_mortgage_to_code',
        'rpss_mortgage_cancelled',
        'rpss_mortgage_exec_date',
        'rpss_mortgage_exec_by',
        'rpss_mortgage_certified_before',
        'rpss_mortgage_notary_public_date',
        'rpss_registered_by',
        'rpss_modified_by',
    ];

    
}
