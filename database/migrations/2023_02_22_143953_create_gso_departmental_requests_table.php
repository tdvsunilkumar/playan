<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoDepartmentalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_departmental_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id')->unsigned()->comment('acctg_departments');
            $table->integer('division_id')->unsigned()->comment('acctg_departments_divisions');
            $table->integer('employee_id')->unsigned()->comment('hr_employees');
            $table->integer('designation_id')->unsigned()->comment('hr_designations');
            $table->integer('request_type_id')->unsigned()->comment('gso_purchase_request_types');
            $table->integer('purchase_type_id')->unsigned()->comment('gso_purchase_types');
            $table->string('control_no', 40);
            $table->date('requested_date')->nullable();
            $table->double('total_amount')->default(0);
            $table->text('remarks')->nullable();
            $table->string('status', 40)->default('draft');
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
        Schema::dropIfExists('gso_departmental_requests');
    }
}
