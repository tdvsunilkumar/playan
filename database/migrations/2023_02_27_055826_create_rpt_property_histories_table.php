<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_histories', function (Blueprint $table) {
            $table->id();
            $table->string('pk_code', 1);
            $table->integer('rp_property_code');
            $table->integer('rp_code_active');
            $table->integer('rp_code_cancelled');
            $table->integer('ph_registered_by');
            $table->date('ph_registered_date');
            $table->integer('ph_modified_by');
            $table->date('ph_modified_date');
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
        Schema::dropIfExists('rpt_property_histories');
    }
}
