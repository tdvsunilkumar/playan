<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_file', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->comment('ref-Table : cpdo_application_requirements.car_id');
            $table->string('cf_name')->comment('File Name');
            $table->string('cf_type')->comment('type')->nullable();
            $table->integer('cf_size')->comment('size')->nullable();
            $table->text('cf_path')->comment('file path');
            $table->integer('cf_is_active')->comment('if the status of the application type is active or not');
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
        Schema::dropIfExists('cpdo_file');
    }
}
