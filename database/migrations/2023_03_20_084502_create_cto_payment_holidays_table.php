<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_holidays', function (Blueprint $table) {
            $table->id();
            $table->integer('htype_id')->comment('cto_payment_holiday_type.htype_id');
            $table->string('hol_desc',100);
            $table->date('hol_start_date')->nullable();
            $table->date('hol_end_date')->nullable();
            $table->integer('hol_is_active')->length(1)->default('0');
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
        Schema::dropIfExists('cto_payment_holidays');
    }
}
