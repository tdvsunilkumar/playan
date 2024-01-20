<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrTimecardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_timecards', function (Blueprint $table) {
            $table->id();
            $table->integer('hrtc_employeesid')->comment('Employee Id');
            $table->integer('hrtc_employeesidno')->comment('Employee Indentification No');
            $table->integer('hrtc_department_id')->comment('ref-Table: hr_employees.acctg_department_id');
            $table->integer('hrtc_division_id')->comment('ref-Table: hr_employees.acctg_department__division_id');
            $table->date('hrtc_date')->comment('Date');
            $table->time('hrtc_time_in')->comment('In time');
            $table->time('hrtc_time_out')->comment('Out Time');
            $table->integer('is_active')->default(0);
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('hr_timecards');
    }
}
