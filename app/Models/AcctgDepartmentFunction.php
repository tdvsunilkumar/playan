<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctgDepartmentFunction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'acctg_departments_functions';
    
    public $timestamps = false;

    public function allDepartmentFunctions($vars = '')
    {
        $functions = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $funcs = array();
        if (!empty($vars)) {
            $funcs[] = array('' => 'select a '.$vars);
        } else {
            $funcs[] = array('' => 'select a function');
        }
        foreach ($functions as $function) {
            $funcs[] = array(
                $function->id => $function->name
            );
        }

        $functions = array();
        foreach($funcs as $func) {
            foreach($func as $key => $val) {
                $functions[$key] = $val;
            }
        }

        return $functions;
    }
}
