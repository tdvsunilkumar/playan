<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyMachineAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_machine_appraisals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code');
            $table->unsignedBigInteger('rp_property_code');
            $table->string('pk_code');
            $table->integer('rvy_revision_year');
            $table->string('rvy_revision_code',10);
            $table->string('rpma_description',75)->nullable();
            $table->string('rpma_brand_model',75)->nullable();
            $table->string('rpma_capacity_hp',20)->nullable();
            $table->string('rpma_date_acquired',4)->nullable();
            $table->string('rpma_condition',30)->nullable();
            $table->decimal('rpma_estimated_life',4,2);
            $table->decimal('rpma_remaining_life',4,2);
            $table->string('rpma_date_installed',4);
            $table->string('rpma_date_operated',4);
            $table->string('rpma_remarks',75)->nullable();
            $table->integer('rpma_appr_no_units');
            $table->decimal('rpma_acquisition_cost',20,3);
            $table->decimal('rpma_freight_cost',20,3);
            $table->decimal('rpma_insurance_cost',20,3);
            $table->decimal('rpma_installation_cost',20,3);
            $table->decimal('rpma_other_cost',20,3);
            $table->decimal('rpma_base_market_value',20,3);
            $table->decimal('rpma_depreciation_rate',20,3);
            $table->decimal('rpma_depreciation',20,3);
            $table->decimal('rpma_market_value',20,3);
            $table->integer('pc_class_code');
            $table->integer('pau_actual_use_code');
            $table->decimal('al_assessment_level',20,3);
            $table->decimal('rpm_assessed_value',20,3);
            $table->integer('rpma_registered_by');
            $table->integer('rpma_modified_by');
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
        Schema::dropIfExists('rpt_property_machine_appraisals');
    }
}
