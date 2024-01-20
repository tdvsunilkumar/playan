<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyTaxCertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_tax_certs', function (Blueprint $table) {
            $table->id();
            $table->integer('rptc_year')->nullable();
            $table->unsignedBigInteger('rptc_control_no')->nullable();
            $table->unsignedBigInteger('rptc_owner_code')->nullable();
            $table->integer('rptc_owner_tin_no')->nullable();
            $table->unsignedBigInteger('rptc_requestor_code')->nullable();
            $table->integer('rptc_including_year')->nullable();
            $table->string('rptc_purpose',150)->nullable();
            $table->date('rptc_date')->nullable();
            $table->unsignedBigInteger('rptc_checked_by')->nullable();
            $table->string('rptc_checked_position',50)->nullable();
            $table->unsignedBigInteger('rptc_prepared_by')->nullable();
            $table->string('rptc_prepared_position',50)->nullable();
            $table->string('rptc_or_no',20)->nullable();
            $table->date('rptc_or_date')->nullable();
            $table->decimal('rptc_or_amount',20,2)->nullable();
            $table->unsignedBigInteger('rptc_registered_by')->nullable();
            $table->unsignedBigInteger('rptc_modified_by')->nullable();
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
        Schema::dropIfExists('rpt_property_tax_certs');
    }
}
