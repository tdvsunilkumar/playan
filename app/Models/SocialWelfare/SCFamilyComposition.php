<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class SCFamilyComposition extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_sca_family_composition';
    public $timestamps = false;
    protected $guarded = ['id'];
    public function info() 
    { 
        return $this->belongsTo(Citizen::class, 'wsfc_cit', 'id'); 
    }
}
