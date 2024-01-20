<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class CtoReceivable extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $timestamps = false;
}
