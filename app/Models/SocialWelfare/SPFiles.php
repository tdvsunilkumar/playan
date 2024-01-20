<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;
use App\Models\Requirements;

class SPFiles extends Model
{
    use ModelUpdateCreate;
    public $table = 'files_welfare_solo_parent';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $appends = array('req_name');
    
    public function requirement() {
        return $this->belongsTo(Requirements::class, 'req_id', 'id'); 
    }
    public function getReqNameAttribute() 
    { 
        if ($this->req_type === 0) {
            $id = $this->req_id;
            return config('constants.spFileRequirements')[$id];
        } elseif ($this->req_type === 1) {
            return $this->requirement->req_description; 
        }
    }
}
