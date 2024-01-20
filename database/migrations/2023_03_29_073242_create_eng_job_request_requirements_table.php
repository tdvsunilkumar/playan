<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngJobRequestRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_job_request_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('ejr_id')->comment('auto increament of jobrequesttable');
            $table->integer('tfoc_id');
            $table->integer('es_id');
            $table->integer('req_id');
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
        Schema::dropIfExists('eng_job_request_requirements');
    }
}
