<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\AssistanceTypeRequirement;
use App\Traits\ModelUpdateCreate;

class AssistanceRequirements extends Model
{
    use ModelUpdateCreate;
    
    public $table = 'welfare_swa_requirements';
    public function scopeActiveRequirements($query)
    {
        $query->where('wsr_is_active', 1);
    }
    public function link() 
    { 
        return $this->belongTo(AssistanceTypeRequirement::class, 'wsr_id', 'id'); 
    } 
}
