<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoApplicationRequirementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_application_requirement', function (Blueprint $table) {
            $table->id();
            $table->integer('caf_id')->comment('ref-Table : cpdo_application_form.caf_id');
            $table->integer('tfoc_id')->comment('ref-Table : cto_tfoc_table.id');
            $table->integer('cs_id')->comment('ref-Table: cpdo_service.cs_id');
            $table->integer('req_id')->comment('ref-Table: requirements.req_id');
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
        Schema::dropIfExists('cpdo_application_requirement');
    }
}
