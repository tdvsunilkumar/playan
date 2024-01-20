<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeofSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_changeof_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_employeesid')->comment('ref-Table: hr_employees.id');
            $table->date('hrcos_start_date')->comment('Start Date');
            $table->date('hrcos_end_date')->comment('End Date');
            $table->integer('hrcos_original_schedule')->comment('default schedule id');
            $table->integer('hrcos_new_schedule')->comment('default schedule id');
            $table->string('reason')->comment('Reason')->nullable();
            $table->integer('status')->comment('Status of Application')->nullable();
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
        Schema::dropIfExists('hr_changeof_schedules');
    }
}