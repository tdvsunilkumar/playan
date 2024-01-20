<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertySworn extends Model
{
    use HasFactory;

    protected $table = 'rpt_property_sworns';

    protected $fillable = [
        
        'rp_code',
        'pk_code',
        'rps_improvement_value',
        'rps_person_taking_oath_custom',
        'rps_person_taking_oath_code',
        'rps_date',
        'rps_ctc_no',
        'rps_ctc_issued_date',
        'rps_ctc_issued_place',
        'rps_administer_official1',
        'rps_administer_official_title1',
        'rps_administer_official2',
        'rps_administer_official_title2',
        'rps_registered_by',
        'rps_modified_by'
    ];
}
