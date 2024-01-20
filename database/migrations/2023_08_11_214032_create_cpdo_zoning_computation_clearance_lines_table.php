<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoZoningComputationClearanceLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_zoning_computation_clearance_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('czcc_id')->length(11);
            $table->decimal('czccl_below')->length(10, 2)->nullable();
            $table->decimal('czccl_over')->length(10, 2)->nullable();
            $table->integer('czccl_over_by_amount')->length(11)->nullable();
            $table->decimal('czccl_amount')->length(11)->nullable();
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
        Schema::dropIfExists('cpdo_zoning_computation_clearance_lines');
    }
}
