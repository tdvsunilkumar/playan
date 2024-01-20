<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialWelfare\AssistanceRequirements;
use App\Models\Requirements;
use App\Traits\ModelUpdateCreate;

class AssistanceFile extends Model
{
    use ModelUpdateCreate;

    public $table = 'files_welfare_assistance';

    protected $guarded = ['id'];
    public $timestamps = false;

    public function requirement() 
    { 
        if ($this->wsr_type === 0) {
            return $this->belongsTo(AssistanceRequirements::class, 'wsr_id', 'id'); 
        } elseif ($this->wsr_type === 1) {
            return $this->belongsTo(Requirements::class, 'wsr_id', 'id'); 
        }
    }

    public function getReqNameAttribute() 
    { 
        $requirement = $this->requirement;
        if ($this->wsr_type === 0) {
            return $requirement->wsr_description; 
        } elseif ($this->wsr_type === 1) {
            return $requirement->req_description; 
        }
    }
}
