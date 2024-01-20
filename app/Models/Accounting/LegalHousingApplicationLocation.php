<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class LegalHousingApplicationLocation extends Model
{
    use ModelUpdateCreate;
    protected $guarded = ['id'];
    public $table = 'eco_housing_application_loc';
    public $timestamps = false;

    public function subdivision() 
    { 
        return $this->belongsTo(LegalResidentialName::class, 'residential_name_id', 'id'); 
    }

    public function phase() 
    { 
        return $this->belongsTo(LegalResidentialLocation::class, 'residential_location_id', 'id'); 
    }

    public function blk() 
    { 
        return $this->belongsTo(LegalResidentialLocDetails::class, 'blk_lot_id', 'id'); 
    }

}
