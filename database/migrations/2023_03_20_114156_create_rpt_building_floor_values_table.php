<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptBuildingFloorValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_building_floor_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code');
            $table->unsignedBigInteger('rp_property_code');
            $table->integer('rpbfv_floor_no');
            $table->integer('rpbfv_total_floor');
            $table->unsignedBigInteger('bt_building_type_code');
            $table->unsignedBigInteger('pau_actual_use_code');
            $table->decimal('rpbfv_floor_unit_value',20,3);
            $table->decimal('rpbfv_floor_area',20,3);
            $table->decimal('rpbfv_floor_base_market_value',20,3);
            $table->decimal('rpbfv_floor_additional_value',20,3);
            $table->decimal('rpbfv_floor_adjustment_value',20,3);
            $table->decimal('rpbfv_total_floor_market_value',20,3);
            $table->decimal('al_assessment_level',20,2);
            $table->decimal('rpb_assessed_value',20,2);
            $table->integer('rpbfv_registered_by');
            $table->integer('rpbfv_modified_by');
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
        Schema::dropIfExists('rpt_building_floor_values');
    }
}
