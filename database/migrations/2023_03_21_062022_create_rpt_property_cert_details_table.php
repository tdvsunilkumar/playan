<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreateRptPropertyCertDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_cert_details', function (Blueprint $table) {
            $table->id();
            $table->integer('rpc_code')->comment('foreign details rpt_property_cert.rpc_code');
            $table->integer('rp_code')->comment('foreign details rpt_property.rp_code');
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
        Schema::dropIfExists('rpt_property_cert_details');
    }
}
