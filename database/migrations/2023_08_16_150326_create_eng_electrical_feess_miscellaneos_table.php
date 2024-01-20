<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngElectricalFeessMiscellaneosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_electrical_feess_miscellaneos', function (Blueprint $table) {
            $table->id();
            $table->String('eefm_description')->comment('Description');
            $table->double('eefm_amount',8,2)->comment('Amount');
            $table->integer('is_active')->default(0);
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
        Schema::dropIfExists('eng_electrical_feess_miscellaneos');
    }
}
