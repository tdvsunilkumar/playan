<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCboAllotmentObligationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbo_allotment_obligations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('departmental_request_id')->unsigned()->comment('gso_departmental_requests');
            $table->integer('department_id')->unsigned()->comment('acctg_departments');
            $table->integer('division_id')->unsigned()->comment('acctg_departments_divisions');
            $table->integer('payee_id')->unsigned()->comment('cbo_payee')->nullable();
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes')->nullable();
            $table->date('date_requested')->nullable();
            $table->year('budget_year')->comment('alob year')->nullable();
            $table->string('budget_control_no', 40)->nullable();
            $table->string('budget_no', 40)->nullable();
            $table->double('total_amount')->default(0);
            $table->text('address')->nullable();
            $table->text('particulars')->nullable();
            $table->string('status', 40)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->integer('disapproved_by')->unsigned()->nullable();
            $table->text('disapproved_remarks')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cbo_allotment_obligations');
    }
}
