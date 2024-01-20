<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoInspectionGeotaggingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_inspection_geotagging', function (Blueprint $table) {
            $table->id();
            $table->integer('cir_id')->comment('ref-Table : cpdo_inspection_reports.cir_id');
            $table->string('cig_location_description')->comment('Location Description');
            $table->string('cig_remarks')->comment('Remark');
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
        Schema::dropIfExists('cpdo_inspection_geotagging');
    }
}
