<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoServiceRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_service_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('tfoc_id')->comment('ref-Table: cto_tfoc.tfoc_id');
            $table->integer('cs_id')->comment('ref-Table: cpdo_service.cs_id');
            $table->integer('req_id')->comment('ref-Table: requirements.req_id');
            $table->integer('csr_is_required')->comment('required = 1 not required = 0');
            $table->integer('csr_is_active')->comment('0 inactive and 1 active');
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
        Schema::dropIfExists('cpdo_service_requirements');
    }
}
