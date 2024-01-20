<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateEngBldgOccupancySubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_bldg_occupancy_sub_types', function (Blueprint $table) {
            $table->id();
            $table->integer('ebost_id')->comment('ref-Table: eng_bldg_occupancy_type.ebot_id');
            $table->string('ebost_description',75);
            $table->integer('ebost_is_active')->default('0');
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
        Schema::dropIfExists('eng_bldg_occupancy_sub_types');
    }
}
