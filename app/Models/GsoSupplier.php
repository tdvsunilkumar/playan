<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoSupplier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_suppliers';
    
    public $timestamps = false;

    public function product_lines()
    {
        return $this->hasMany('App\Models\GsoSupplierProductLine', 'supplier_id', 'id')->where('gso_suppliers_product_lines.is_active', 1);
    }
    
    public function ewt()
    {
        return $this->belongsTo('App\Models\AcctgExpandedWithholdingTax', 'ewt_id', 'id');
    }

    public function evat()
    {
        return $this->belongsTo('App\Models\AcctgExpandedVatableTax', 'evat_id', 'id');
    }

    public function lastId()
    {
       $supplier=self::orderBy('id', 'desc')->first();
       return $supplier->id;
    }

    public function allSuppliers($vars = '')
    {
        $suppliers = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $sups = array();
        if (!empty($vars)) {
            $sups[] = array('' => 'select a '.$vars);
        } else {
            $sups[] = array('' => 'select a supplier');
        }
        foreach ($suppliers as $supplier) {
            $sups[] = array(
                $supplier->id => $supplier->code
            );
        }

        $suppliers = array();
        foreach($sups as $sup) {
            foreach($sup as $key => $val) {
                $suppliers[$key] = $val;
            }
        }

        return $suppliers;
    }
}
