<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoDevelopmentPermitPaymentLinesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_development_permit_payment_lines_create', function (Blueprint $table) {
            $table->id();
            $table->integer('cdp_id');
            $table->integer('cdppl_plineid');
            $table->integer('cdppl_checkbox');
            $table->string('cdppl_description');
            $table->integer('cdppl_number');
            $table->integer('cdppl_type');
            $table->double('cdppl_amount',8,2);
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
        Schema::dropIfExists('cpdo_development_permit_payment_lines_create');
    }
}
