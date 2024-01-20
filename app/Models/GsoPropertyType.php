<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsoPropertyType extends Model
{
    protected $guarded = ['id'];

    public $table = 'gso_property_types';
    
    public $timestamps = false;

    public function allProperties()
    {
        $properties = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $props = array();
        if (!empty($vars)) {
            $props[] = array('' => 'select a '.$vars);
        } else {
            $props[] = array('' => 'select a property type');
        }
        foreach ($properties as $property) {
            $props[] = array(
                $property->id => $property->code . ' - ' . $property->name
            );
        }

        $properties = array();
        foreach($props as $prop) {
            foreach($prop as $key => $val) {
                $properties[$key] = $val;
            }
        }

        return $properties;
    }
}
