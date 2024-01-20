<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoBasicSefTaxratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cto_taxrates', function (Blueprint $table) {
            $table->id();
            $table->string('pc_class_code')->comment('foreign details rpt_property_class.pc_class_code')->nullable(); 
            $table->integer('bsst_basic_rate');
            $table->integer('bsst_sef_rate');
            $table->integer('bsst_sh_rate');
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('rpt_cto_taxrates');
    }
}
