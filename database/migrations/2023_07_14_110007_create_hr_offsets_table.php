<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOffsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_offsets', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_employeesid')->comment('ref-Table: hr_employees.id');
            $table->string('applicationno')->comment('application no');
            $table->date('hro_work_date')->comment('work Date');
            $table->integer('hro_id')->comment('ref-Table: hr_leave_application.hrlt_id');
            $table->integer('hro_remaining_offset_hours')->default('0');
            $table->string('hro_reason')->comment('Reason')->nullable();
            $table->integer('hro_status')->comment('Status of Application')->nullable();
            $table->integer('hro_approved_by')->comment('Approved');
            $table->datetime('hro_approved_at')->comment('date time');
            $table->integer('hro_reviewed_by')->comment('Reviewed By');
            $table->datetime('hro_reviewed_at')->comment('date time');
            $table->integer('hro_noted_by')->comment('Noted By');
            $table->datetime('hro_noted_at')->comment('date time');
            $table->integer('hro_disapproved_by')->comment('Disapprove BY');
            $table->datetime('hro_disapproved_at')->comment('date time');
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
        Schema::dropIfExists('hr_offsets');
    }
}
