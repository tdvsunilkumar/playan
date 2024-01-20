<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptBuildingFlooringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    
    public function up()
    {
        Schema::create('rpt_building_floorings', function (Blueprint $table) {
            $table->id();
            $table->string('rbf_building_flooring_desc',30);
            $table->integer('rbf_is_active')->default(1);
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
        Schema::dropIfExists('rpt_building_floorings');
    }
}
