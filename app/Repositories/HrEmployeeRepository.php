<?php

namespace App\Repositories;

use App\Interfaces\HrEmployeeRepositoryInterface;
use App\Models\AcctgDepartment;
use App\Models\AcctgDepartmentDivision;
use App\Models\HrDesignation;
use App\Models\HrEmployee;
use App\Models\HrEmployeeDepartmentalAccess;
use App\Models\Barangay;
use App\Models\CboPayee;
use App\Models\FileUpload;
use DB;

class HrEmployeeRepository implements HrEmployeeRepositoryInterface 
{
    public function getAll() 
    {
        return HrEmployee::all();
    }

    public function find($id) 
    {
        return HrEmployee::findOrFail($id);
    }
    
    public function validate($identification_no, $id = '')
    {   
        if ($id !== '') {
            return HrEmployee::where(['identification_no' => $identification_no])->where('id', '!=', $id)->count();
        } 
        return HrEmployee::where(['identification_no' => $identification_no])->count();
    }

    public function create(array $details, $departmental_access, $timestamp, $user) 
    {
        $human_resource = HrEmployee::create($details);

        $payee = CboPayee::where('hr_employee_id' , $human_resource->id)->get();
        if ($payee->count() > 0) {
            $payeeDetails = array(
                'paye_type' => 1,
                'paye_name' => $details['fullname'],
                'paye_address_lotno' => $details['c_house_lot_no'],
                'paye_address_street' => $details['c_street_name'],
                'paye_address_subdivision' => $details['c_subdivision'],
                'paye_full_address' => $details['current_address'],
                'brgy_code' => $details['barangay_id'],
                'paye_telephone_no' => $details['telephone_no'],
                'paye_mobile_no' => $details['mobile_no'],
                'paye_email_address' => $details['email_address'],
                'paye_fax_no' => $details['fax_no'],
                'paye_tin_no' => $details['tin_no'],
                'updated_at' => $details['created_at'],
                'paye_modified_by' => $details['created_by']
            );
            CboPayee::whereId($payee->first()->id)->update($payeeDetails);
        } else {
            $payeeDetails = array(
                'paye_type' => 1,
                'hr_employee_id' => $human_resource->id,
                'paye_name' => $details['fullname'],
                'paye_address_lotno' => $details['c_house_lot_no'],
                'paye_address_street' => $details['c_street_name'],
                'paye_address_subdivision' => $details['c_subdivision'],
                'paye_full_address' => $details['current_address'],
                'brgy_code' => $details['barangay_id'],
                'paye_telephone_no' => $details['telephone_no'],
                'paye_mobile_no' => $details['mobile_no'],
                'paye_email_address' => $details['email_address'],
                'paye_fax_no' => $details['fax_no'],
                'paye_tin_no' => $details['tin_no'],
                'created_at' => $details['created_at'],
                'paye_generated_by' => $details['created_by']
            );
            $payee = CboPayee::create($payeeDetails);
            HrEmployee::whereId($human_resource->id)->update(['payee_id' => $payee->id]);
        }

        if (!empty($departmental_access)) {
            foreach ($departmental_access as $access) {
                $products = HrEmployeeDepartmentalAccess::create([
                    'employee_id' => $human_resource->id,
                    'department_id' => $access,
                    'created_at' => $timestamp,
                    'created_by' => $user
                ]);
            }
        }
        return $human_resource;
    }

    public function remove_restore($id, array $newDetails)
    {
        return HrEmployee::whereId($id)->update($newDetails);
    }

    public function update($id, array $newDetails, $departmental_access, $timestamp, $user) 
    {
        $human_resource = HrEmployee::whereId($id)->update($newDetails);

        $payee = CboPayee::where('hr_employee_id' , $id)->get();
        if ($payee->count() > 0) {
            $payeeDetails = array(
                'paye_type' => 1,
                'paye_name' => $newDetails['fullname'],
                'paye_address_lotno' => $newDetails['c_house_lot_no'],
                'paye_address_street' => $newDetails['c_street_name'],
                'paye_address_subdivision' => $newDetails['c_subdivision'],
                'paye_full_address' => $newDetails['current_address'],
                'brgy_code' => $newDetails['barangay_id'],
                'paye_telephone_no' => $newDetails['telephone_no'],
                'paye_mobile_no' => $newDetails['mobile_no'],
                'paye_email_address' => $newDetails['email_address'],
                'paye_fax_no' => $newDetails['fax_no'],
                'paye_tin_no' => $newDetails['tin_no'],
                'updated_at' => $newDetails['updated_at'],
                'paye_modified_by' => $newDetails['updated_by']
            );
            CboPayee::whereId($payee->first()->id)->update($payeeDetails);
        } else {
            $payeeDetails = array(
                'paye_type' => 1,
                'hr_employee_id' => $id,
                'paye_name' => $newDetails['fullname'],
                'paye_address_lotno' => $newDetails['c_house_lot_no'],
                'paye_address_street' => $newDetails['c_street_name'],
                'paye_address_subdivision' => $newDetails['c_subdivision'],
                'paye_full_address' => $newDetails['current_address'],
                'brgy_code' => $newDetails['barangay_id'],
                'paye_telephone_no' => $newDetails['telephone_no'],
                'paye_mobile_no' => $newDetails['mobile_no'],
                'paye_email_address' => $newDetails['email_address'],
                'paye_fax_no' => $newDetails['fax_no'],
                'paye_tin_no' => $newDetails['tin_no'],
                'created_at' => $newDetails['updated_at'],
                'paye_generated_by' => $newDetails['updated_by']
            );
            $payee = CboPayee::create($payeeDetails);
            HrEmployee::whereId($id)->update(['payee_id' => $payee->id]);
        }

        HrEmployeeDepartmentalAccess::where('employee_id', $id)->update(['updated_at' => $timestamp, 'updated_by' => $user, 'is_active' => 0]);
        if (!empty($departmental_access)) {
            foreach ($departmental_access as $access) {
                $depAccess = HrEmployeeDepartmentalAccess::where(['employee_id' => $id, 'department_id' => $access])->get();
                if ($depAccess->count() > 0) {
                    $depAccess = HrEmployeeDepartmentalAccess::find($depAccess->first()->id);
                    $depAccess->updated_at = $timestamp;
                    $depAccess->updated_by = $user;
                    $depAccess->is_active = 1;
                    $depAccess->update();
                } else {
                    $depAccess = HrEmployeeDepartmentalAccess::create([
                        'employee_id' => $id,
                        'department_id' => $access,
                        'created_at' => $timestamp,
                        'created_by' => $user
                    ]);
                }
            }
        }
        return $human_resource;
    }

    public function listItems($request)
    {   
        $columns = array( 
            0 => 'hr_employees.identification_no',
            1 => 'hr_employees.firstname',
            2 => 'hr_employees.title',
            3 => 'hr_employees.current_address',
            4 => 'hr_employees.mobile_no',   
            5 => 'hr_designations.description',
            6 => 'acctg_departments.name',
            7 => 'acctg_departments_divisions.name'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'order' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        //filter
        $department = $request->department;
        $status     = $request->status;

        $res = HrEmployee::select([
            '*',
            'hr_employees.id as empId', 
            'hr_designations.description as desigName',
            'acctg_departments.name as depName',
            'acctg_departments_divisions.name as divName',
            'hr_employees.is_active as empStatus',
            'hr_employees.created_at as empCreated_at',
            'hr_employees.updated_at as empUpdatedAt'
        ])
        ->leftJoin('hr_designations', function($join)
        {
            $join->on('hr_designations.id', '=', 'hr_employees.hr_designation_id');
        })
        ->leftJoin('acctg_departments', function($join)
        {
            $join->on('acctg_departments.id', '=', 'hr_employees.acctg_department_id');
        })
        ->leftJoin('acctg_departments_divisions', function($join)
        {
            $join->on('acctg_departments_divisions.id', '=', 'hr_employees.acctg_department_division_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('hr_employees.identification_no', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.firstname', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.middlename', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.lastname', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.title', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.current_address', 'like', '%' . $keywords . '%')
                ->orWhere('hr_employees.mobile_no', 'like', '%' . $keywords . '%')
                ->orWhere('hr_designations.description', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments.name', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_departments_divisions.name', 'like', '%' . $keywords . '%');
            }
        })
        ->where(function($q) use ($department, $status) {
            if ($department) {
                $q->where('hr_employees.acctg_department_id', $department);
            }
            if ($status != 2) {
                $q->where('hr_employees.is_active', $status);
            }
        })
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    public function delete($id)
    {
        FileUpload::destroy($id);
    }
    
    public function listItemsUpload($request, $itemId)
    {   
        $columns = array( 
            0 => 'name',
            1 => 'type',
            2 => 'size'
        );
        $start     = $request->get('start');
        $limit     = $request->get('length');
        $column    = (!isset($request->get('order')['0']['column'])) ? 'name' : $columns[$request->get('order')['0']['column']];
        $order     = (!isset($request->get('order')['0']['dir'])) ? 'asc' : $request->get('order')['0']['dir'];
        $keywords  = $request->get('search')['value'];

        $res = FileUpload::select([
            '*'
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('name', 'like', '%' . $keywords . '%')
                ->orWhere('type', 'like', '%' . $keywords . '%')
                ->orWhere('size', 'like', '%' . $keywords . '%');
            }
        })
        ->where(['category' => 'employees', 'category_id' => $itemId])
        ->orderBy($column, $order);
        $count = $res->count();
        $res   = $res->skip($start)->take($limit)->get();

        return (object) array('count' => $count, 'data' => $res);
    }

    public function allDepartments()
    {
        return (new AcctgDepartment)->allDepartments();
    }

    public function allDesignations()
    {
        return (new HrDesignation)->allDesignations();
    }

    public function reload_division($department, $search = null)
    {
        return (new AcctgDepartmentDivision)->reload_division_via_department($department, $search);
    }

    public function allBarangays()
    {
        return (new Barangay)->allBarangays();
    }

    public function allDepartmentsMultiple()
    {
        return (new AcctgDepartment)->allDepartmentsMultiple();
    }

    public function findAccess($id)
    {
        $res = HrEmployeeDepartmentalAccess::where(['employee_id' => $id, 'is_active' => 1])->get();        
        return $res->map(function($line) {
            return  $line->department_id;
        });
    }

    public function selectEmploymentStatus(){
        $status = DB::table('hr_employee_statuses')->where('is_active',1)->orderBy('hres_description')->get()->mapWithKeys(function ($row, $key) {
            $array[$row->id] = $row->hres_description;
            return $array;
        })->toArray();
        $status = [''=>'Please Select'] + $status;
        return $status;
      }
    public function selectAppointmentStatus(){
        $status = DB::table('hr_appointment_status')->where('is_active',1)->orderBy('hras_description')->get()->mapWithKeys(function ($row, $key) {
            $array[$row->id] = $row->hras_description;
            return $array;
        })->toArray();
        $status = [''=>'Please Select'] + $status;
        return $status;
    }
    public function selectOccupationalLevel(){
        $status = DB::table('hr_occupation_levels')->where('is_active',1)->orderBy('hrol_description')->get()->mapWithKeys(function ($row, $key) {
            $array[$row->id] = $row->hrol_description;
            return $array;
        })->toArray();
        $status = [''=>'Please Select'] + $status;
        return $status;
    }
    public function selectSalaryGrade(){
        $status = DB::table('hr_salary_grades')->where('is_active',1)->orderBy('hrsg_salary_grade')->get()->mapWithKeys(function ($row, $key) {
            $array[$row->id] = $row->hrsg_salary_grade;
            return $array;
        })->toArray();
        $status = [''=>'Please Select'] + $status;
        return $status;
    }
    public function selectSalaryGradeStep(){
        $status = DB::table('hr_salary_grade_steps')->where('is_active',1)->orderBy('hrsgs_description')->get()->mapWithKeys(function ($row, $key) {
            $array[$row->id] = $row->hrsgs_description;
            return $array;
        })->toArray();
        $status = [''=>'Please Select'] + $status;
        return $status;
    }
}