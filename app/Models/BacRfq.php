<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacRfq extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'bac_rfqs';
    
    public $timestamps = false;

    public function fund()
    {
        return $this->belongsTo('App\Models\AcctgFundCode', 'fund_code_id', 'id');
    }

    public function exp()
    {
        return $this->belongsTo('App\Models\BacExpendableWarranty', 'warranty_exp_id', 'id');
    }

    public function non_exp()
    {
        return $this->belongsTo('App\Models\BacNonExpendableWarranty', 'warranty_non_exp_id', 'id');
    }

    public function price_validity()
    {
        return $this->belongsTo('App\Models\BacPriceValidity', 'price_validaty_id', 'id');
    }

    public function pur_type()
    {
        return $this->belongsTo('App\Models\GsoPurchaseType', 'purchase_type_id', 'id');
    }

    public function inserted()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function modified()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }
}
