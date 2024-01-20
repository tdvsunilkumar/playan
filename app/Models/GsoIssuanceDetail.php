<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoIssuanceDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_issuances_details';
    
    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\GsoIssuanceCategory', 'category_id', 'id');
    }

    public function fundcode()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');   
    }

    public function type()
    {
        return $this->belongsTo('App\Models\GsoIssuanceType', 'issuance_type_id', 'id');
    }
}
