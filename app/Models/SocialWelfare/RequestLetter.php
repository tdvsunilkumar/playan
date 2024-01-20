<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class RequestLetter extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
    public $table = 'welfare_social_welfare_assistance_request_letter';

    public function assistance()
    {
        return $this->belongsTo(Assistance::class, 'wswa_id', 'id'); 
    }
}
