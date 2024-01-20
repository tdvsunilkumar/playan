<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploAssessPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_assess_payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('psched_year')->comment('For The Year');
            $table->integer('psched_mode_no')->comment('Mode');
            $table->string('psched_description')->comment('Description');
            $table->string('psched_short_desc')->comment('Short Description');
            $table->date('psched_date_start')->comment('Start Date');
            $table->date('psched_date_end')->comment('End Date');
            $table->date('psched_penalty_due_date')->comment('Penalty Due Date');
            $table->date('psched_discount_due_date')->comment('Discount Due Date');
            $table->integer('is_active')->default(1); 
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('bplo_assess_payment_schedules');
    }
}
