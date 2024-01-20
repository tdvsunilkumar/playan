<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoTaxRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cto_tax_revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trev_id')->nullable();
            $table->integer('tax_what_year')->nullable();
            $table->unsignedBigInteger('basic_tfoc_id')->nullable();
            $table->unsignedBigInteger('sef_tfoc_id')->nullable();
            $table->unsignedBigInteger('sh_tfoc_id')->nullable();
            $table->unsignedBigInteger('tf_tfoc_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('rpt_cto_tax_revenues');
    }
}
