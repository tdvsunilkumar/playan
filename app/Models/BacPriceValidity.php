<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacPriceValidity extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'bac_price_validities';
    
    public $timestamps = false;

    public function allPriceValidities($vars = '')
    {
        $validities = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $vldits = array();
        if (!empty($vars)) {
            $vldits[] = array('' => 'select a '.$vars);
        } else {
            $vldits[] = array('' => 'select a price validity');
        }
        foreach ($validities as $validity) {
            $vldits[] = array(
                $validity->id => $validity->code . ' - ' . $validity->description
            );
        }

        $validities = array();
        foreach($vldits as $valdit) {
            foreach($valdit as $key => $val) {
                $validities[$key] = $val;
            }
        }

        return $validities;
    }
}
