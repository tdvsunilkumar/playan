<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPropertyAccountability extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_property_accountabilities';
    
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('App\Models\GsoPropertyCategory', 'property_category_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\GsoPropertyType', 'property_type_id', 'id');
    }

    public function mode()
    {
        return $this->belongsTo('App\Models\GsoDepreciationType', 'depreciation_type_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\GsoItem', 'item_id', 'id');
    }

    public function gl_account()
    {
        return $this->belongsTo('App\Models\AcctgAccountGeneralLedger', 'gl_account_id', 'id');
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\GsoUnitOfMeasurement', 'uom_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function allFixedAssets($vars = '')
    {
        $empty = '';
        $fixed_assets = self::where('is_active', 1)->where('is_locked', 1)->whereNotNull('fixed_asset_no')->orderBy('id', 'asc')->get();
    
        $deps = array();
        if (!empty($vars)) {
            $deps[] = array('' => 'select a '.$vars);
        } else {
            $deps[] = array('' => 'select a fixed asset');
        }
        foreach ($fixed_assets as $fixed_asset) {
            $deps[] = array(
                $fixed_asset->id => $fixed_asset->fixed_asset_no
            );
        }

        $fixed_assets = array();
        foreach($deps as $dep) {
            foreach($dep as $key => $val) {
                $fixed_assets[$key] = $val;
            }
        }

        return $fixed_assets;
    }
}
