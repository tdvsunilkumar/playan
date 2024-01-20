<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngServiceRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_service_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('tfoc_id')->comment('Service Fee ref-Table: cto_tfoc.tfoc_id');
            $table->integer('es_id')->comment('ref-Table: eng_service.es_id');
            $table->integer('req_id')->comment('ref-Table: requirements.req_id');
            $table->integer('esr_is_required')->default(0);
            $table->integer('esr_is_active')->default(0);
            $table->integer('orderno')->default(0);  
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
        Schema::dropIfExists('eng_service_requirements');
    }
}