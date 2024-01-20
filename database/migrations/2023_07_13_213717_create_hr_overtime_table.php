<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOvertimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_overtime', function (Blueprint $table) {
            $table->id();
			$table->integer('hr_employeesid')->length(11)->comment('ref-Table: hr_employees.id');
			$table->integer('department_id')->length(11)->default('0')->comment('department_id');
			$table->string('hrot_application_no')->length(100)->comment('Application No')->nullable();
			$table->date('hrot_work_date')->comment('Work Date');
			$table->time('hrot_start_time')->comment('Start Time');
			$table->time('hrot_end_time')->comment('End Time');
			$table->integer('hrwc_id')->length(11);
			$table->string('hrot_considered_hours')->comment('total hours');
			$table->boolean('hrot_following_day')->comment('Yes, No');
			$table->string('hro_reason')->default('0')->length(11);
			$table->integer('hro_status')->default('0')->length(1);
			$table->integer('hro_approved_by')->length(11)->default('0')->comment('ref-Table: hr_employees.id');
			$table->datetime('hro_approved_at');
			$table->integer('hro_reviewed_by')->length(11)->default('0')->comment('ref-Table: hr_employees.id');
			$table->datetime('hro_reviewed_at');
			$table->integer('hro_noted_by')->length(11)->default('0')->comment('ref-Table: hr_employees.id');
			$table->datetime('hro_noted_at');
			$table->integer('hro_disapproved_by')->length(11)->default('0')->comment('ref-Table: hr_employees.id');
			$table->datetime('hro_disapproved_at');
			$table->integer('created_by')->default('0')->unsigned()->comment('reference hr_employee.p_code of the system who registered the details');
            $table->integer('updated_by')->default('0')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_overtime');
    }
}
