<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class AcctgDepartmentDivision extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'acctg_departments_divisions';
    
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'acctg_department_id', 'id');
    }

    public function reload_division_via_department($department, $search = null)
    {
        $divisions = self::where(['acctg_department_id' => $department, 'is_active' => 1])
        ->orderBy('id', 'asc')
        ->get();
        if ($search) {
            $division->where([
                [DB::raw('LOWER(name)'),'like',"%".strtolower($search)."%"],
                [DB::raw('LOWER(short_name)'),'like',"%".strtolower($search)."%"],
            ]);
        }
        return $divisions;
    }

    public function allDivisions($vars = '')
    {
        $divisions = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $divs = array();
        if (!empty($vars)) {
            $divs[] = array('' => 'select a '.$vars);
        } else {
            $divs[] = array('' => 'select a division');
        }
        foreach ($divisions as $division) {
            $divs[] = array(
                $division->id => $division->code . ' - ' . $division->name
            );
        }

        $divisions = array();
        foreach($divs as $div) {
            foreach($div as $key => $val) {
                $divisions[$key] = $val;
            }
        }

        return $divisions;
    }
}
