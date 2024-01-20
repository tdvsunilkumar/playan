<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrMissedLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_missed_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hr_emp_id');
            $table->string('hml_application_no');
            $table->date('hml_work_date');
            $table->integer('hrlog_id');
            $table->time('hml_actual_time');
            $table->string('hml_reason');
            $table->integer('hml_status');
            $table->integer('hml_approved_by')->nullable();
            $table->datetime('hml_approved_at')->nullable();
            $table->integer('hml_reviewed_by')->nullable();
            $table->datetime('hml_reviewed_at')->nullable();
            $table->integer('hml_noted_by')->nullable();
            $table->datetime('hml_noted_at')->nullable();
            $table->integer('hml_disapproved_by')->nullable();
            $table->datetime('hml_disapproved_at')->nullable();
            $table->integer('created_by');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_missed_log');
    }
}
