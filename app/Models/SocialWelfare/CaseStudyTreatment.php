<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class CaseStudyTreatment extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
    public $table = 'welfare_social_welfare_sc_treatment';
}
