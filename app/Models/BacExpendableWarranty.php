<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacExpendableWarranty extends Model
{   
    use HasFactory;
    
    protected $guarded = ['id'];

    public $table = 'bac_expendable_warranties';
    
    public $timestamps = false;

    public function allExpendableWarranties($vars = '')
    {
        $warranties = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $wrntys = array();
        if (!empty($vars)) {
            $wrntys[] = array('' => 'select a '.$vars);
        } else {
            $wrntys[] = array('' => 'select a non expendable warranty');
        }
        foreach ($warranties as $warranty) {
            $wrntys[] = array(
                $warranty->id => $warranty->description
            );
        }

        $warranties = array();
        foreach($wrntys as $wrnty) {
            foreach($wrnty as $key => $val) {
                $warranties[$key] = $val;
            }
        }

        return $warranties;
    }
}
