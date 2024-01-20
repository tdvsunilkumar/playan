<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesOccupancyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_occupancy', function (Blueprint $table) {
            $table->id();
            $table->integer('eoar_id')->comment('ref-Table: occupancy_requirement.id');
            $table->integer('eoa_id')->comment('ref-Table: eng_occupancy_apps.id');
            $table->string('fe_name')->comment('file name');
            $table->string('fe_type')->comment('file type');
            $table->string('fe_size')->comment('file size');
            $table->string('fe_path')->comment('file path');
            $table->integer('fe_is_active')->comment('fe_is_active');
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
        Schema::dropIfExists('files_occupancy');
    }
}
