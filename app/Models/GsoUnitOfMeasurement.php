<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoUnitOfMeasurement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_unit_of_measurements';
    
    public $timestamps = false;

    public function allUOMs($vars = '')
    {
        $measurements = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $uoms = array();
        if (!empty($vars)) {
            $uoms[] = array('' => 'select a '.$vars);
        } else {
            $uoms[] = array('' => 'select a uom');
        }
        foreach ($measurements as $measurement) {
            $uoms[] = array(
                $measurement->id => $measurement->code
            );
        }

        $measurements = array();
        foreach($uoms as $uom) {
            foreach($uom as $key => $val) {
                $measurements[$key] = $val;
            }
        }

        return $measurements;
    }
}
