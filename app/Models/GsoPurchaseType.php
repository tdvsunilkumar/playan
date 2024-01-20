<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPurchaseType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_purchase_types';
    
    public $timestamps = false;

    public function allPurchaseTypes($vars = '')
    {
        $types = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $typs = array();
        if (!empty($vars)) {
            $typs[] = array('' => 'select a '.$vars);
        } else {
            $typs[] = array('' => 'select a purchase type code');
        }
        foreach ($types as $type) {
            $typs[] = array(
                $type->id => $type->code . ' - ' . $type->description
            );
        }

        $types = array();
        foreach($typs as $typ) {
            foreach($typ as $key => $val) {
                $types[$key] = $val;
            }
        }

        return $types;
    }
}
