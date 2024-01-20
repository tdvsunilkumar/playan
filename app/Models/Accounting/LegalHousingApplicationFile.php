<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class LegalHousingApplicationFile extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $table = 'eco_housing_application_files';
    public $timestamps = false;

}
