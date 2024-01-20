<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoItemType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'gso_item_types';
    
    public $timestamps = false;

    public function allItemTypes($vars = '')
    {
        $types = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $typs = array();
        if (!empty($vars)) {
            $typs[] = array('' => 'select a '.$vars);
        } else {
            $typs[] = array('' => 'select a type');
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
