<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RptPropertyAnnotation extends Model
{
    use HasFactory;

    protected $table = 'rpt_property_annotations';

     protected $fillable = [
        
        'rp_code',
        'pk_code',
        'rpa_annotation_date_time',
        'rpa_annotation_by_code',
        'rpa_annotation_desc',
        'rpa_registered_by',
        'rpa_modified_by'
    ];
}
