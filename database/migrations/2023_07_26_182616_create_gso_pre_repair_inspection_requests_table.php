<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPreRepairInspectionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_pre_repair_inspection_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('property_id')->unsigned()->comment('gso_property_accountabilities');
            $table->integer('requested_by')->unsigned()->nullable()->comment('hr_employees');
            $table->date('requested_date')->nullable();
            $table->string('repair_no', 40)->nullable();
            $table->text('issues')->nullable();
            $table->string('status', 40)->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->integer('approved_counter')->default(1);
            $table->timestamp('approved_at')->nullable();
            $table->text('approved_by')->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->integer('disapproved_by')->unsigned()->nullable();
            $table->text('disapproved_remarks')->nullable();
            $table->boolean('is_inspected')->default(0);
            $table->timestamp('inspected_at')->nullable();
            $table->text('inspected_by')->nullable();
            $table->boolean('is_checked')->default(0);
            $table->timestamp('checked_at')->nullable();
            $table->text('checked_by')->nullable();
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
        Schema::dropIfExists('gso_pre_repair_inspection_requests');
    }
}
