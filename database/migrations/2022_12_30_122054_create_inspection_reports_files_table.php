<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionReportsFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_reports_files', function (Blueprint $table) {
            $table->id();
            $table->integer('occupancy_typeid')->comment('Occupancy Type Foreign Id');
            $table->text('occupancy_typepdfname')->comment('Occupancy Upload Pdf File');
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
        Schema::dropIfExists('inspection_reports_files');
    }
}
