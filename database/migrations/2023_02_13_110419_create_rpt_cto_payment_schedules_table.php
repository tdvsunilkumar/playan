<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoPaymentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cto_payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('rcpsched_year'); 
            $table->integer('sd_mode');
            $table->date('rcpsched_date_start');
            $table->date('rcpsched_date_end');
            $table->date('rcpsched_penalty_due_date');
            $table->date('rcpsched_discount_due_date');
            $table->integer('rcpsched_discount_rate');
            $table->integer('is_active')->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('rpt_cto_payment_schedules');
    }
}
