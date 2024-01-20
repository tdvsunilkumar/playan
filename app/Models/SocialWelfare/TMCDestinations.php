<?php

namespace App\Models\SocialWelfare;

use App\Traits\ModelUpdateCreate;
use Illuminate\Database\Eloquent\Model;

class TMCDestinations extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_tcm_destination';
    public $timestamps = false;
    protected $guarded = ['id'];
}
