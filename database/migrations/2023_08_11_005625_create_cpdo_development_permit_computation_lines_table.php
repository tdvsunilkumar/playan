<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoDevelopmentPermitComputationLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_development_permit_computation_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('cdpc_id')->length(11);
            $table->string('cdpcl_description')->nullable();
            $table->decimal('cdpcl_amount')->length(10, 2)->nullable();
            $table->integer('cis_id')->length(11)->nullable();
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
        Schema::dropIfExists('cpdo_development_permit_computation_lines');
    }
}
