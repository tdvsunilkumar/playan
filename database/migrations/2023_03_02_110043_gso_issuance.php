<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GsoIssuance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_issuance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_type')->unsigned();
            $table->integer('issue_type')->unsigned();
            $table->integer('paye_type')->unsigned();
            $table->integer('issue_year')->unsigned()->nullable();
            $table->integer('issue_month')->unsigned()->nullable();
            $table->integer('issue_no')->unsigned()->nullable();
            $table->string('issue_control_no', 20);
            $table->date('issue_date');
            $table->string('dept_code',10);
            $table->string('ddiv_code',10);
            $table->integer('dept_idcode')->unsigned()->nullable();
            $table->string('issue_remarks',150)->nullable();
            $table->string('issue_fund_cluster',150)->nullable();
            $table->integer('issue_requestor')->unsigned()->nullable();
            $table->string('issue_requestor_position',50)->nullable();
            $table->date('issue_requestor_date')->nullable();
            $table->integer('issue_approver')->unsigned()->nullable();
            $table->string('issue_approver_position',50)->nullable();
            $table->date('issue_approver_date')->nullable();
            $table->integer('issue_personnel')->unsigned()->nullable();
            $table->string('issue_personnel_position',50)->nullable();
            $table->date('issue_personnel_date')->nullable();
            $table->integer('issue_receiver')->unsigned()->nullable();
            $table->string('issue_receiver_position',50)->nullable();
            $table->date('issue_receiver_date')->nullable();
            $table->integer('issue_registered_by')->unsigned()->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->integer('issue_modified_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gso_issuance');
    }
}
