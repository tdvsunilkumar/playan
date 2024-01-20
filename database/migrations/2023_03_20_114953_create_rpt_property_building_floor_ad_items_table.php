<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyBuildingFloorAdItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_building_floor_ad_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code');
            $table->unsignedBigInteger('rpbfv_code');
            $table->unsignedBigInteger('rp_property_code');
            $table->integer('bei_extra_item_code');
            $table->string('bei_extra_item_desc',75);
            $table->decimal('rpbfai_total_area',20,3);
            $table->integer('rpbfai_registered_by');
            $table->integer('rpbfai_modified_by');
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
        Schema::dropIfExists('rpt_property_building_floor_ad_items');
    }
}
