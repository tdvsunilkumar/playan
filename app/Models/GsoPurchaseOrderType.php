<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseOrderType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_purchase_order_types';

    public function allPoTypes($vars = '')
    {
        $typez = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $types = array();
        if (!empty($vars)) {
            $types[] = array('' => 'select a '.$vars);
        } else {
            $types[] = array('' => 'select an account group');
        }
        foreach ($typez as $type) {
            $types[] = array(
                $type->id => $type->name
            );
        }

        $po_types = array();
        foreach($types as $type) {
            foreach($type as $key => $val) {
                $po_types[$key] = $val;
            }
        }

        return $po_types;
    }
}
