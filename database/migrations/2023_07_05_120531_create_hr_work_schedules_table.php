<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrWorkSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_employeesid')->comment('ref-Table: hr_employees.id');
            $table->date('hrds_date')->comment('Date');
            $table->integer('hrds_id')->comment('ref-Table: hr_default_schedule.hrds_id');
            $table->integer('year')->comment('Current Year');
            $table->integer('month')->comment('month in year');
            $table->text('monthdate_json')->comment('whole month date json');
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
        Schema::dropIfExists('hr_work_schedules');
    }
}
