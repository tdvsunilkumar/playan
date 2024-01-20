<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoDevelopmentPermitRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_development_permit_requirements', function (Blueprint $table) {
            $table->id();
            $table->Integer('cdp_id')->length(11)->default('0')->comment('ref-Table : cpdo_development_permit.cpd_id');
            $table->integer('tfoc_id')->comment('Service Fee');
            $table->integer('cs_id')->comment('ref-Table: cpdo_service. cs_id');
            $table->integer('req_id')->comment('ref-Table: requirements.req_id');
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
        Schema::dropIfExists('cpdo_development_permit_requirements');
    }
}
