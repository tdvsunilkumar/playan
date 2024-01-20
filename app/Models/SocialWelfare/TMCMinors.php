<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model; 
use App\Traits\ModelUpdateCreate;

class TMCMinors extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_tcm_minors';
    public $timestamps = false;
    protected $guarded = ['id'];
    public function info() 
    { 
        return $this->belongsTo(Citizen::class, 'cit_id', 'id'); 
    }
}
