<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelUpdateCreate;

class HrEmpFamilyBg extends Model
{
    use ModelUpdateCreate;

    public $table = 'hr_emp_family_bg';
    protected $guarded = ['id'];
    public $timestamps = false;
}
