<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class CaseStudyFamily extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
    public $table = 'welfare_swsc_dependent';

    public function info() 
    { 
        return $this->belongsTo(Citizen::class, 'wswscd_cit_id', 'id'); 
    }
}
