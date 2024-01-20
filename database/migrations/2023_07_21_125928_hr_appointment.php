<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrAppointment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_appointment', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_emp_id')->length(11)->comment('ref-Table: hr_employees.id');
            $table->integer('hra_department_id')->length(11)->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hra_division_id')->length(11)->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->string('hra_employee_no');
            $table->date('hra_date_hired');
            $table->string('hra_designation')->comment('ref-Table: hr_employees.hr_designation_id');
            $table->integer('hres_id')->length(11)->comment('ref-Table: hr_employee_status.hres_id');
            $table->integer('hras_id')->length(11)->comment('ref-Table: hr_employee_appointment_status');
            $table->integer('hrpt_id')->length(11)->comment('ref-Table: hr_payment_term.hrpt_id');
            $table->integer('hrol_id')->length(11)->comment('ref-Table: hr_occupational_level.hrol_id');
            $table->integer('hrsg_id')->length(11)->comment('ref-Table: hr_salary_grade.hrsg_id');
            $table->integer('hrsgs_id')->length(11)->comment('ref-Table: hr_salary_grade_step.hrsgs_id');
            $table->double('hra_monthly_rate', 10, 2);
            $table->double('hra_annual_rate', 10, 2);
            $table->integer('is_active')->default(0);
            $table->integer('created_by')->length(11);
            $table->date('created_at')->default(now()); // Adding default value for created_at column
            $table->integer('updated_by')->nullable()->length(11);
            $table->date('updated_at')->nullable();

            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_appointment');
    }
}
