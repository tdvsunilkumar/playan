<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_employeesid')->comment('ref-Table: hr_employees.id');
            $table->date('hrl_start_date')->comment('Start Date');
            $table->date('hrl_end_date')->comment('End Date');
            $table->integer('hrlt_id')->comment('Leave Type');
            $table->integer('hrla_id')->comment('ref-Table: hr_leave_application.hrlt_id');
            $table->integer('dayswithpay')->default('0');
            $table->string('hrla_reason')->comment('Reason')->nullable();
            $table->integer('hrla_status')->comment('Status of Application')->nullable();
            $table->integer('hrla_approved_by')->comment('Approved');
            $table->datetime('hrla_approved_at')->comment('date time');
            $table->integer('hrla_reviewed_by')->comment('Reviewed By');
            $table->datetime('hrla_reviewed_at')->comment('date time');
            $table->integer('hrla_noted_by')->comment('Noted By');
            $table->datetime('hrla_noted_at')->comment('date time');
            $table->integer('hrla_disapproved_by')->comment('Disapprove BY');
            $table->datetime('hrla_disapproved_at')->comment('date time');
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
        Schema::dropIfExists('hr_leaves');
    }
}
