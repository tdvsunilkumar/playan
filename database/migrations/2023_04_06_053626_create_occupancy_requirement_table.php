<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupancyRequirementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupancy_requirement', function (Blueprint $table) {
            $table->id();
            $table->integer('eoa_id')->comment('ref-Table: eng_occupancy_app.eoa_id');
            $table->integer('tfoc_id');
            $table->integer('es_id');
            $table->integer('req_id');
            $table->integer('orderno')->default('0');
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
        Schema::dropIfExists('occupancy_requirement');
    }
}
