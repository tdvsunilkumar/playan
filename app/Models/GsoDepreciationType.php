<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;
use App\Models\HrEmployeeDepartmentalAccess;

class GsoDepreciationType extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_depreciation_types';
    
    public $timestamps = false;

    public function allDepreciations($vars = '')
    {
        $depreciations = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $deps = array();
        if (!empty($vars)) {
            $deps[] = array('' => 'select a '.$vars);
        } else {
            $deps[] = array('' => 'select a depreciation');
        }
        foreach ($depreciations as $depreciation) {
            $deps[] = array(
                $depreciation->id => $depreciation->code . ' - ' . $depreciation->name
            );
        }

        $depreciations = array();
        foreach($deps as $dep) {
            foreach($dep as $key => $val) {
                $depreciations[$key] = $val;
            }
        }

        return $depreciations;
    }
}
