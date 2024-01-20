<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcoRentalDiscount extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'eco_rental_discounts';
    
    public $timestamps = false;

    public function allDiscounts($vars = '')
    {   
        $discountz = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $dicsounts = array();
        if (!empty($vars)) {
            $dicsounts[] = array('' => 'select a '.$vars);
        } else {
            $dicsounts[] = array('' => 'select a discount');
        }
        foreach ($discountz as $discount) {
            $dicsounts[] = array(
                $discount->id => $discount->name
            );
        }

        $discountz = array();
        foreach($dicsounts as $disc) {
            foreach($disc as $key => $val) {
                $discountz[$key] = $val;
            }
        }

        return $discountz;
    }
}
