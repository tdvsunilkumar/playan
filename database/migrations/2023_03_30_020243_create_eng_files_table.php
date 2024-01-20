<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_files', function (Blueprint $table) {
            $table->id();
            $table->integer('ejrr_id')->comment('ref-Table: eng_job_request_requirements.id');
            $table->integer('ejr_id')->comment('ref-Table: eng_job_request.id');
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
        Schema::dropIfExists('eng_files');
    }
}
