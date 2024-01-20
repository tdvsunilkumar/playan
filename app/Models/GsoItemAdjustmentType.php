<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemAdjustmentType extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_item_adjustments_types';
    
    public $timestamps = false;

    public function allAdjustmentTypes($vars = '')
    {
        $adjustments = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $adjusts = array();
        if (!empty($vars)) {
            $adjusts[] = array('' => 'select a '.$vars);
        } else {
            $adjusts[] = array('' => 'select a type');
        }
        foreach ($adjustments as $adjustment) {
            $adjusts[] = array(
                $adjustment->id => $adjustment->code . ' - ' . $adjustment->name
            );
        }

        $adjustments = array();
        foreach($adjusts as $adjust) {
            foreach($adjust as $key => $val) {
                $adjustments[$key] = $val;
            }
        }

        return $adjustments;
    }
}
