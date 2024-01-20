<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_appraisals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code');
            $table->string('pk_code',1);
            $table->integer('rvy_revision_year');
            $table->string('rvy_revision_code',10);
            $table->string('pc_class_code',10);
            $table->string('ps_subclass_code',4);
            $table->string('pau_actual_use_code',4);
            $table->decimal('lav_unit_value',14,2);
            $table->decimal('rpa_total_land_area',10,3);
            $table->string('lav_unit_measure',20);
            $table->string('rls_code',10)->nullable();
            $table->decimal('rls_percent',3)->nullable();
            $table->decimal('lav_strip_unit_value',14,3)->nullable();
            $table->decimal('rpa_base_market_value',10,3);
            $table->decimal('rpa_adjusted_market_value',10,3);
            $table->decimal('rpa_adjusted_plant_tree_value',14,3)->default(0)->nullable();
            $table->decimal('rpa_adjusted_total_planttree_market_value',14,3)->default(0)->nullable();
            $table->decimal('al_assessment_level',10,3);
            $table->decimal('rpa_assessed_value',14,3);
            $table->integer('rpa_taxable');
            $table->decimal('rpa_adjustment_factor_a',5,3);
            $table->decimal('rpa_adjustment_factor_b',5,3);
            $table->decimal('rpa_adjustment_factor_c',5,3);
            $table->decimal('rpa_adjustment_percent',5,3);
            $table->decimal('rpa_adjustment_value',14,2);
            
            $table->integer('rpa_registered_by');
            $table->integer('rpa_modified_by');
            $table->timestamps();
            $table->foreign('rp_code')
              ->references('id')->on('rpt_properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_property_appraisals');
    }
}
