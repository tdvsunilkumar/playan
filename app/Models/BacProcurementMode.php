<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacProcurementMode extends Model
{
    protected $guarded = ['id'];

    public $table = 'bac_procurement_modes';
    
    public $timestamps = false;

    public function allProcurementModes($vars = '')
    {
        $modez = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $modes = array();
        if (!empty($vars)) {
            $modes[] = array('' => 'select a '.$vars);
        } else {
            $modes[] = array('' => 'select an account group');
        }
        foreach ($modez as $mode) {
            $modes[] = array(
                $mode->id => $mode->description
            );
        }

        $modez = array();
        foreach($modes as $mode) {
            foreach($mode as $key => $val) {
                $modez[$key] = $val;
            }
        }

        return $modez;
    }
}
