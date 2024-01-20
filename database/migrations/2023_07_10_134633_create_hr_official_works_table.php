<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOfficialWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_official_works', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_employeesid')->comment('ref-Table: hr_employees.id');
            $table->date('hrow_work_date')->comment('Work Date');
            $table->integer('hrwt_id')->comment('ref-Table: hr_work_type.hrwt_id');
            $table->time('hrow_time_in')->comment('Start Time');
            $table->time('hrow_time_out')->comment('End Time');
            $table->string('hrow_reason')->comment('Reason')->nullable();
            $table->integer('hrow_status')->comment('Status of time log')->nullable();
            $table->integer('hrow_approved_by')->comment('Approved');
            $table->datetime('hrow_approved_at')->comment('date time');
            $table->integer('hrow_reviewed_by')->comment('Reviewed By');
            $table->datetime('hrow_reviewed_at')->comment('date time');
            $table->integer('hrow_noted_by')->comment('Noted By');
            $table->datetime('hrow_noted_at')->comment('date time');
            $table->integer('hrow_disapproved_by')->comment('Disapprove BY');
            $table->datetime('hrow_disapproved_at')->comment('date time');
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
        Schema::dropIfExists('hr_official_works');
    }
}
