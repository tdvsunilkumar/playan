<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyBldgFloorValTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_bldg_floor_val', function (Blueprint $table) {
            $table->id();
            $table->integer('rp_code')->unsigned()->comment('foreign key rpt_property.rp_code');
            $table->integer('rp_property_code')->unsigned()->comment('foreign key rpt_property.rp_property_code');
            $table->integer('rpbfv_floor_no')->unsigned()->comment('Floor Number Sample like[1 - 4 means 1st floor of 1 floor],[2 - 4 means 2nd floor of 4 floor]');
            $table->integer('rpbfv_total_floor')->comment('Of Total Floors');
            $table->string('bt_building_type_code')->comment('Building Structural Type foreign details rpt_building_type.bt_building_type_code');
            $table->string('pau_actual_use_code')->comment('Building Actual Use foreign details rpt_property_actual_use.pau_actual_use_code');
            $table->integer('rpbfv_floor_unit_value')->comment('refer to the [Kinds of Building] & [Structural Type]]');
            $table->integer('rpbfv_floor_area')->comment('rTotal Floor Area within the specific floor');
            $table->integer('rpbfv_floor_base_market_value')->comment('the result of [rpbfv_unit_value * rpbfv_floor_area]');
            $table->integer('rpbfv_floor_additional_value')->comment('Floor Additional Value');
            $table->integer('rpbfv_floor_adjustment_value')->comment('Floor Adjustment Value');
            $table->integer('rpbfv_total_floor_market_value')->comment('Total Floor Market Value the sum of [rpbfv_base_market_value + rpbfv_additional_value + rpbfv_adjustment_value]');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('rpt_property_bldg_floor_val');
    }
}
