<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class SPFamilyComposition extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_spa_family_composition';
    public $timestamps = false;
    protected $guarded = ['id'];
    public function info() 
    { 
        return $this->belongsTo(Citizen::class, 'wsfc_cit', 'id'); 
    }
}
