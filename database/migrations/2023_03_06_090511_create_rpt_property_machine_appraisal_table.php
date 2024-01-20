<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyMachineAppraisalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_machine_appraisal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code')->comment('foreign details rpt_property.rp_code');
            $table->unsignedBigInteger('rp_property_code')->comment('permanent code domainate thought out the record, regardles of how many tax declaration number has been pass or revisions has been made');
            $table->string('pk_code',1)->comment('foregin details rpt_property_kind.pk_code');
            $table->integer('rvy_revision_year')->comment('foreign details rpt_revision_year.rvy_revision_year');
            $table->string('rvy_revision_code')->comment('foreign details rpt_revision_year.rvy_revision_year.rvy_revision_code');
            $table->string('rpma_description')->comment("Description of the machine");
            $table->string('rpma_brand_model')->comment('Brand/Model');
            $table->string('rpma_capacity_hp')->comment('Capacity/ Horse Power of the machine');
            $table->integer('rpma_date_acquired')->comment('Date Year when the machine has been acquired');
            $table->string('rpma_condition')->comment('Condition of the machine');
            $table->integer('rpma_estimated_life')->comment("Estimated Life of the machine");
            $table->integer('rpma_remaining_life')->comment("Remaining Life of the machine");
            $table->integer('rpma_date_installed')->comment('Date Year when the machine has been installed');
            $table->integer('rpma_date_operated')->comment('Date Year when the machine has operated');
            $table->string('rpma_remarks')->comment('Remarks');
            $table->integer('rpma_appr_no_units')->comment("No. of units for the machine");
            $table->integer('rpma_acquisition_cost')->comment("Machine Appraisal Detail: Acquisition Cost");
            $table->integer('rpma_freight_cost')->comment("Machine Appraisal Detail: Freight Cost");
            $table->integer('rpma_insurance_cost')->comment("Machine Appraisal Detail: Insurance Cost");
            $table->integer('rpma_installation_cost')->comment("Machine Appraisal Detail: Installation Cost");
            $table->integer('rpma_other_cost')->comment("Machine Appraisal Detail: Other Cost");
            $table->integer('rpma_base_market_value')->comment("Machine Appraisal Detail: Base Market Value");
            $table->integer('rpma_depreciation_rate')->comment("Machine Appraisal Detail: Depreciation Rate");
            $table->integer('rpma_depreciation')->comment("Machine Appraisal Detail: Depreciation");
            $table->integer('rpma_market_value')->comment("Market Value");
            $table->integer('pc_class_code')->comment("foreign details rpt_property_class.pc_class_code");
            $table->integer('pau_actual_use_code')->comment('foreign details rpt_property_actual_use.pau_actual_use_code');
            $table->integer('al_assessment_level')->comment('assessment level percentage');
            $table->integer('rpm_assessed_value')->comment('Assessment Value');
            $table->integer('rpma_registered_by')->comment('reference profile.p_code of the system who registered the rpt_property_appraisal');
            $table->timestamp('rpma_registered_date')->comment('Format: [yyyy-mm-dd hh:mm:ss]');
            $table->integer('rpma_modified_by')->comment('reference profile.p_code of the system who update the rpt_property_appraisal');
            $table->timestamp('rpma_modified_date')->comment('Format: [yyyy-mm-dd hh:mm:ss]');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_property_machine_appraisal');
    }
}
