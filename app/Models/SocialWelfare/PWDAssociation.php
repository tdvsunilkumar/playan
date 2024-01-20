<?php

namespace App\Models\SocialWelfare;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class PWDAssociation extends Model
{
    use ModelUpdateCreate;
    public $table = 'welfare_pwd_organization';
    protected $guarded = ['id'];
    public $timestamps = false;
}
