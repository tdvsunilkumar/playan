<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngElectricalFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_electrical_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('eef_jobrequestid')->comment('Job Requestid');
            $table->integer('eef_total_load_kva');
            $table->double('eef_total_load_total_fees')->length(10, 2);
            $table->integer('eef_total_ups');
            $table->double('eef_total_ups_total_fees')->length(10, 2);
            $table->integer('eef_pole_location_qty');
            $table->double('eef_pole_location_total_fees')->length(10, 2);
            $table->integer('eef_guying_attachment_qty');
            $table->double('eef_guying_attachment_fees')->length(10, 2);
            $table->integer('eefm_id');
            $table->integer('eef_electric_meter_fees');
            $table->integer('eef_wiring_permit_fees');
            $table->double('eef_miscellaneous_tota_fees')->length(10, 2);
            $table->double('eef_total_fees')->length(10, 2);
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
        Schema::dropIfExists('eng_electrical_fees');
    }
}
