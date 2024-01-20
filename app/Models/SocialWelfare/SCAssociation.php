<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class SCAssociation extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_sca_association';
    protected $guarded = ['id'];
    public $timestamps = false;
}
