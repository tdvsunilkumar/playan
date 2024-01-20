<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrDesignation extends Model
{
    protected $guarded = ['id'];

    public $table = 'hr_designations';
    
    public $timestamps = false;

    public function allDesignations($vars = '')
    {
        $designations = self::where('is_active', 1)->orderBy('id', 'asc')->get();

        $designs = array();
        if (!empty($vars)) {
            $designs[] = array('' => 'select a '.$vars);
        } else {
            $designs[] = array('' => 'select an designation');
        }
        foreach ($designations as $designation) {
            $designs[] = array(
                $designation->id => $designation->description
            );
        }

        $designations = array();
        foreach($designs as $design) {
            foreach($design as $key => $val) {
                $designations[$key] = $val;
            }
        }

        return $designations;
    }

    public function allEmptyDesignations()
    {
        $designations = self::where('is_active', 1)->orderBy('id', 'asc')->get();

        $designs = array();
        foreach ($designations as $designation) {
            $designs[] = array(
                $designation->id => $designation->description
            );
        }

        $designations = array();
        foreach($designs as $design) {
            foreach($design as $key => $val) {
                $designations[$key] = $val;
            }
        }

        return $designations;
    }
}
