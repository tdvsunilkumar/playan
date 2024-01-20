<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CboPayee;

class HrEmployee extends Model
{
    protected $guarded = ['id'];

    public $table = 'hr_employees';
    
    public $timestamps = false;

    protected $appends = ['standard_name'];

    public function getStandardNameAttribute(){
      $name = '';
      if($this->firstname == null){
        $name .= $this->middlename.' '.$this->lastname.', '.$this->suffix;
      }else if($this->middlename == null){
        $name .= $this->firstname.' '.$this->lastname.', '.$this->suffix;
      }else if($this->suffix == null){
        $name .= $this->firstname.' '.$this->middlename.' '.$this->lastname;
      }else if($this->firstname == null && $this->middlename == null && $this->suffix == null){
        $name .= $this->lastname;
      }else{
        $name .= $this->firstname.' '.$this->middlename.' '.$this->lastname.', '.$this->suffix;
      }

      return $name;
    }

    public function allEmployees($vars = '')
    {
        $employees = self::where('is_active', 1)->orderBy('id', 'asc')->get();

        $emps = array();
        if (!empty($vars)) {
            $emps[] = array('' => 'select a '.$vars);
        } else {
            $emps[] = array('' => 'select an employee');
        }
        foreach ($employees as $employee) {
            $fullname = (strlen($employee->middlename) > 0) ? ucwords($employee->firstname).' '.ucwords($employee->middlename[0]).'. '.ucwords($employee->lastname) : ucwords($employee->firstname).' '.ucwords($employee->lastname);
            $emps[] = array(
                $employee->id => $fullname
            );
        }

        $employees = array();
        foreach($emps as $emp) {
            foreach($emp as $key => $val) {
                $employees[$key] = $val;
            }
        }

        return $employees;
    }

    public function allCboEmployees($vars = '')
    {
        $employees = self::whereIn('id', CboPayee::select('hr_employee_id')->where('paye_type', 1)->get())->where('is_active', 1)->orderBy('id', 'asc')->get();

        $emps = array();
        if (!empty($vars)) {
            $emps[] = array('' => 'select a '.$vars);
        } else {
            $emps[] = array('' => 'select an employee');
        }
        foreach ($employees as $employee) {
            $fullname = (strlen($employee->middlename) > 0) ? ucwords($employee->firstname).' '.ucwords($employee->middlename[0]).'. '.ucwords($employee->lastname) : ucwords($employee->firstname).' '.ucwords($employee->lastname);
            $emps[] = array(
                $employee->id => $fullname
            );
        }

        $employees = array();
        foreach($emps as $emp) {
            foreach($emp as $key => $val) {
                $employees[$key] = $val;
            }
        }

        return $employees;
    }
    
    public function empDataById($id)
    {
        $employees = self::where('id',$id)->orderBy('id', 'asc')->first();
        return $employees;
    }
    public function empIdByUserId($id)
    {
        $employees = self::where('user_id',$id)->orderBy('id', 'asc')->first();
        return $employees;
    }
    public function hrEmpIdByUserId($id)
    {
        $employees = self::where('user_id',$id)->orderBy('id', 'asc')->first();
        return $employees->id;
    }

    public function reload_employees($department)
    {
        $employees = self::where(['acctg_department_id' => $department, 'is_active' => 1])->orderBy('id', 'asc')->get();
        return $employees;
    }

    public function reload_cbo_employees($department)
    {
        $employees = self::whereIn('id', CboPayee::select('hr_employee_id')->where('paye_type', 1)->get())->where(['acctg_department_id' => $department, 'is_active' => 1])->orderBy('id', 'asc')->get();
        return $employees;
    }

    public function designation()
    {
        return $this->belongsTo('App\Models\HrDesignation', 'hr_designation_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\AcctgDepartment', 'acctg_department_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo('App\Models\AcctgDepartmentDivision', 'acctg_department_division_id', 'id');
    }
    public function cbo_payee()
    {
        return $this->belongsTo('App\Models\CboPayee', 'id', 'hr_employee_id');
    }

    public function department_access() 
    {
        return $this->hasMany('App\Models\HrEmployeeDepartmentalAccess', 'employee_id', 'id');
    }
    public function brgy() 
    {
        return $this->hasOne(Barangay::class, 'id','barangay_id');
    }
    public function brgy_perm() 
    {
        return $this->hasOne(Barangay::class, 'id', 'hr_emp_brgy_code_permanent');
    }
    public function children() 
    {
        return $this->hasMany('App\Models\HR\HrEmpChildren', 'employee_id', 'id');
    }
    public function civil() 
    {
        return $this->hasMany('App\Models\HR\HrEmpCivilService', 'employee_id', 'id');
    }
    public function work() 
    {
        return $this->hasMany('App\Models\HR\HrEmpWorkExp', 'employee_id', 'id');
    }
    public function voluntary() 
    {
        return $this->hasMany('App\Models\HR\HrEmpVoluntaryWork', 'employee_id', 'id');
    }
    public function training() 
    {
        return $this->hasMany('App\Models\HR\HrEmpTrainingProgram', 'employee_id', 'id');
    }
    public function skills() 
    {
        return $this->hasMany('App\Models\HR\HrEmpHobbies', 'employee_id', 'id');
    }
    public function recognition() 
    {
        return $this->hasMany('App\Models\HR\HrEmpRecognition', 'employee_id', 'id');
    }
    public function orgs() 
    {
        return $this->hasMany('App\Models\HR\HrEmpOrg', 'employee_id', 'id');
    }
    public function reference() 
    {
        return $this->hasMany('App\Models\HR\HrEmpReference', 'employee_id', 'id');
    }

    // attributes
    public function getPrcNoAttribute()
    {
        return $this->emp_prc_no;
    }
    public function getPrcValidityAttribute()
    {
        return $this->emp_prc_validity;
    }
    public function getPrcDateIssuedAttribute()
    {
        return $this->emp_prc_date_issued;
    }
    public function getPtrNoAttribute()
    {
        return $this->emp_ptr_no;
    }
    public function getPtrDateIssuedAttribute()
    {
        return $this->emp_issue_date;
    }
    public function getPtrIssuedAtAttribute()
    {
        return $this->emp_issue_at;
    }
    public function getData($column, $table,$id = null)
    {
        if (isset($this->id)) {
            $emp_id_col = 'employee_id';
            $where = [];
            switch ($table) {
                case 'family':
                    $query = new \App\Models\HR\HrEmpFamilyBg();
                    break;
                case 'child':
                    $query = new \App\Models\HR\HrEmpChildren();
                    $where[] = ['id',$id];
                    break;
                case 'educ':
                    $query = new \App\Models\HR\HrEmpEduc();
                    $where[] = ['hree_level',$id];
                    break;
                case 'civil':
                    $query = new \App\Models\HR\HrEmpCivilService();
                    $where[] = ['id',$id];
                    break;
                case 'work':
                    $query = new \App\Models\HR\HrEmpWorkExp();
                    $where[] = ['id',$id];
                    break;
                case 'voluntary':
                    $query = new \App\Models\HR\HrEmpVoluntaryWork();
                    $where[] = ['id',$id];
                    break;
                case 'training':
                    $query = new \App\Models\HR\HrEmpTrainingProgram();
                    $where[] = ['id',$id];
                    break;
                case 'skill':
                    $query = new \App\Models\HR\HrEmpHobbies();
                    $where[] = ['id',$id];
                    break;
                case 'recognition':
                    $query = new \App\Models\HR\HrEmpRecognition();
                    $where[] = ['id',$id];
                    break;
                case 'org':
                    $query = new \App\Models\HR\HrEmpOrg();
                    $where[] = ['id',$id];
                case 'other':
                    $query = new \App\Models\HR\HrEmpOtherDetail();
                    $where[] = ['hreo_question',$id];
                    break;
                case 'reference':
                    $query = new \App\Models\HR\HrEmpReference();
                    $where[] = ['id',$id];
                    break;
                case 'appoint':
                    $query = new \App\Models\HR\HrAppointment();
                    $emp_id_col = 'hr_emp_id';
                    break;
            }
            $where[] = [$emp_id_col,$this->id];
            $data = $query->where($where)->first();
            $data = $data ? $data[$column] : null;
            return $data;
        }
        return null;
    }

    public function addEmployee($row, $relations)
    {
        $emp = self::updateOrCreate(
            [
                'id' => $row['id']
            ],
            $row
        );
        $emp_id = $emp->id;
        HrEmployeeDepartmentalAccess::where('employee_id',$emp_id)->update(['is_active'=>0]);
            if ($row['is_dept_restricted'] === 1) {
                $access = HrEmployeeDepartmentalAccess::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'department_id' => $row->acctg_department_id,
                    ],
                    [
                        'is_active' => 1
                    ]
                );
            } else {
                if ($relations['access']) {
                    foreach ($relations['access'] as $value) {
                            $access = HrEmployeeDepartmentalAccess::updateOrCreate(
                                [
                                    'employee_id' => $emp_id,
                                    'department_id' => $value,
                                ],
                                [
                                    'is_active' => 1
                                ]
                            );
                        }
                }
            }
        if ($relations['family']) {
            $relations['family']['employee_id'] = $emp_id;
            \App\Models\HR\HrEmpFamilyBg::updateOrCreate(
                [
                    'employee_id' => $emp_id
                ],
                $relations['family']
            );

        }
        if ($relations['children']) {
            foreach ($relations['children'] as $id => $value) {
                $value['employee_id'] = $emp_id;
                $child = \App\Models\HR\HrEmpChildren::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );

            }
        }
        if ($relations['educ']) {
            foreach ($relations['educ'] as $id => $value) {
                \App\Models\HR\HrEmpEduc::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'hree_level' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['civil']) {
            foreach ($relations['civil'] as $id => $value) {
                \App\Models\HR\HrEmpCivilService::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['work']) {
            foreach ($relations['work'] as $id => $value) {
                \App\Models\HR\HrEmpWorkExp::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['voluntary']) {
            foreach ($relations['voluntary'] as $id => $value) {
                \App\Models\HR\HrEmpVoluntaryWork::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['training']) {
            foreach ($relations['training'] as $id => $value) {
                \App\Models\HR\HrEmpTrainingProgram::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['skills']) {
            foreach ($relations['skills'] as $id => $value) {
                \App\Models\HR\HrEmpHobbies::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['recognition']) {
            foreach ($relations['recognition'] as $id => $value) {
                \App\Models\HR\HrEmpRecognition::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['orgs']) {
            foreach ($relations['orgs'] as $id => $value) {
                \App\Models\HR\HrEmpOrg::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['other']) {
            foreach ($relations['other'] as $id => $value) {
                if (isset($value['hreo_details']) && is_array($value['hreo_details'])) {
                    $value['hreo_details'] = implode('$_$',$value['hreo_details']);
                    // $test = explode('$_$',$value['hreo_details']);
                }
                \App\Models\HR\HrEmpOtherDetail::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'hreo_question' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['reference']) {
            foreach ($relations['reference'] as $id => $value) {
                \App\Models\HR\HrEmpReference::updateOrCreate(
                    [
                        'employee_id' => $emp_id,
                        'id' => $id,
                    ],
                    $value
                );
            }
        }
        if ($relations['appoint']) {
            $designation = HrDesignation::find($emp->hr_designation_id);
            $relations['appoint']['hra_department_id'] = $emp->acctg_department_id;
            $relations['appoint']['hra_division_id'] = $emp->acctg_department_division_id;
            $relations['appoint']['hra_employee_no'] = $emp->identification_no;
            $relations['appoint']['hra_designation'] = $designation->description;
            $relations['appoint']['hra_monthly_rate'] = currency_to_float($relations['appoint']['hra_monthly_rate']);
            $relations['appoint']['hra_annual_rate'] = currency_to_float($relations['appoint']['hra_annual_rate']);
            \App\Models\HR\HrAppointment::updateOrCreate(
                [
                    'hr_emp_id' => $emp_id,
                ],
                $relations['appoint']
            );
        }
    }
    public function removeRelation($type, $id)
    {
        switch ($type) {
            case 'child':
                \App\Models\HR\HrEmpChildren::destroy($id);
                break;
            case 'civil':
                \App\Models\HR\HrEmpCivilService::destroy($id);
                break;
            case 'work':
                \App\Models\HR\HrEmpWorkExp::destroy($id);
                break;
            case 'voluntary':
                \App\Models\HR\HrEmpVoluntaryWork::destroy($id);
                break;
            case 'training':
                \App\Models\HR\HrEmpTrainingProgram::destroy($id);
                break;
            case 'skill':
                \App\Models\HR\HrEmpHobbies::destroy($id);
                break;
            case 'recognition':
                \App\Models\HR\HrEmpRecognition::destroy($id);
                break;
            case 'org':
                \App\Models\HR\HrEmpOrg::destroy($id);
                break;
            case 'reference':
                \App\Models\HR\HrEmpReference::destroy($id);
                break;
        }
    }

    // users
    public function variousEmployee()
    {
        return self::where('is_various',1)->first();
    }
    public function getMayor()
    {
        return self::join('hr_designations','hr_designations.id','hr_employees.hr_designation_id')
        ->where([
            'hr_designations.code'=>'CM',
            'hr_employees.is_active'=>1,
        ])
        ->first();
    }
}

