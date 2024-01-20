<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HrEmployee;
use App\Models\HrEmployeeDepartmentalAccess;

class AcctgDepartment extends Model
{
    protected $guarded = ['id'];

    public $table = 'acctg_departments';
    
    public $timestamps = false;

    public function functions()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentFunction', 'acctg_department_function_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\HrEmployee', 'hr_employee_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'hr_designation_id', 'id');
    }

    public function divisions()
    {
        return $this->hasMany('App\Models\AcctgDepartmentDivision', 'acctg_department_id', 'id')->where('is_active', 1);
    }

    public function allDepartments($vars = '')
    {
        $departments = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $deps = array();
        if (!empty($vars)) {
            $deps[] = array('' => 'select a '.$vars);
        } else {
            $deps[] = array('' => 'select a department');
        }
        foreach ($departments as $department) {
            $deps[] = array(
                $department->id => $department->code . ' - ' . $department->name
            );
        }

        $departments = array();
        foreach($deps as $dep) {
            foreach($dep as $key => $val) {
                $departments[$key] = $val;
            }
        }

        return $departments;
    }

    public function allDepartmentsMultiple()
    {
        $departments = self::where('is_active', 1)->orderBy('id', 'asc')->get();
    
        $deps = array();
        foreach ($departments as $department) {
            $deps[] = array(
                $department->id => $department->code . ' - ' . $department->name
            );
        }

        $departments = array();
        foreach($deps as $dep) {
            foreach($dep as $key => $val) {
                $departments[$key] = $val;
            }
        }

        return $departments;
    }

    public function allDepartmentsWithRestriction($user)
    {
        $departments = self::where('is_active', 1)->orderBy('id', 'asc');

        $res = HrEmployee::where('user_id', $user)->first();
        if ($res->is_dept_restricted > 0) {
            $departments = self::where('is_active', 1)->orderBy('id', 'asc')
            ->whereIn('id', 
                HrEmployee::select(['acctg_department_id'])
                ->where('user_id', $user)
                ->where('is_dept_restricted', 1)
                ->get()
            )
            ->get();
        } else {
            $departments = self::where('is_active', 1)->orderBy('id', 'asc')
            ->whereIn('id', 
                HrEmployeeDepartmentalAccess::select('hr_employees_departmental_access.department_id')
                ->leftJoin('hr_employees', function($join)
                {
                    $join->on('hr_employees.id', '=', 'hr_employees_departmental_access.employee_id');
                })
                ->where([
                    'hr_employees_departmental_access.is_active' => 1,
                    'hr_employees.user_id' => $user
                ])
                ->get()
            )
            ->get();
        }
    
        $deps = array();
        $deps[] = array('' => 'select a department');
        foreach ($departments as $department) {
            $deps[] = array(
                $department->id => $department->code . ' - ' . $department->name
            );
        }

        $departments = array();
        foreach($deps as $dep) {
            foreach($dep as $key => $val) {
                $departments[$key] = $val;
            }
        }

        return $departments;
    }
}
