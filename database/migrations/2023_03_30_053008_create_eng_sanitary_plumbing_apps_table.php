<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngSanitaryPlumbingAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_sanitary_plumbing_apps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mum_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('espa_year');
            $table->string('espa_series_no');
            $table->string('espa_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no)');
            $table->date('espa_application_date')->comment('Application Date)');
            $table->date('espa_issued_date')->comment('Issued Date)');
            $table->integer('p_code')->comment('Client Id)');
            $table->string('espa_location');
            $table->integer('ebs_id')->comment('scope of work ref-Table: ebs_bldg_scope.ebs_id, sanitary/plumbing = 1');
            $table->integer('ebot_id')->comment('(Use / Character of occupancy) ref-Table: eng_bldg_occupancy_type.ebot_id, sanitary/plumbing = 1');
            $table->integer('espa_water_closet_qty');
            $table->integer('espa_water_closet_type');
            $table->integer('espa_floor_drain_qty');
            $table->integer('espa_floor_drain_type');
            $table->integer('espa_lavatories_qty');
            $table->integer('espa_lavatories_type');
            $table->integer('espa_kitchen_sink_qty');
            $table->integer('espa_kitchen_sink_type');
            $table->integer('espa_faucet_qty');
            $table->integer('espa_faucet_type');
            $table->integer('espa_shower_head_qty');
            $table->integer('espa_shower_head_type');
            $table->integer('espa_water_meter_qty');
            $table->integer('espa_water_meter_type');
            $table->integer('espa_grease_trap_qty');
            $table->integer('espa_grease_trap_type');
            $table->integer('espa_bath_tubs_qty');
            $table->integer('espa_bath_tubs_type');
            $table->integer('espa_slop_sink_qty');
            $table->integer('espa_slop_sink_type');
            $table->integer('espa_urinal_qty');
            $table->integer('espa_urinal_type');
            $table->integer('espa_airconditioning_unit_qty');
            $table->integer('espa_airconditioning_unit_type');
            $table->integer('espa_water_tank_qty');
            $table->integer('espa_water_tank_type');
            $table->integer('espa_bidette_qty');
            $table->integer('espa_bidettet_type');
            $table->integer('espa_laundry_trays_qty');
            $table->integer('espa_laundry_trays_type');
            $table->integer('espa_dental_cuspidor_qty');
            $table->integer('espa_dental_cuspidor_type');
            $table->integer('espa_gas_heater_qty');
            $table->integer('espa_gas_heater_type');
            $table->integer('espa_electric_heater_qty');
            $table->integer('espa_electric_heater_type');
            $table->integer('espa_water_boiler_qty');
            $table->integer('espa_water_boiler_type');
            $table->integer('espa_drinking_fountain_qty');
            $table->integer('espa_drinking_fountain_type');
            $table->integer('espa_bar_sink_qty');
            $table->integer('espa_bar_sink_type');
            $table->integer('espa_soda_fountain_qty');
            $table->integer('espa_soda_fountain_type');
            $table->integer('espa_laboratory_qty');
            $table->integer('espa_laboratory_type');
            $table->integer('espa_sterilizer_qty');
            $table->integer('espa_sterilizer_type');
            $table->integer('espa_swimmingpool_qty');
            $table->integer('espa_swimmingpool_type');
            $table->integer('espa_others_qty');
            $table->integer('espa_others_type');
            $table->integer('espa_others_category');
            $table->integer('ewst_id')->comment('Water Supply');
            $table->integer('edst_id')->comment('System of Disposal');
            $table->integer('espa_no_of_storey')->comment('Number of storeys building');
            $table->integer('espa_floor_area')->comment('Total area of Building/subdivision');
            $table->date('espa_installation_date')->comment('Installation date');
            $table->integer('espa_installation_cost')->comment('Total Cost of Installation');
            $table->date('espa_completion_date')->comment('Expected date of completion');
            $table->double('espa_amount_due',8,2)->comment('Amount Due');
            $table->integer('espa_assessed_by');
            $table->string('espa_or_no');
            $table->date('espa_date_paid');
            $table->integer('espa_sign_category');
            $table->integer('espa_sign_consultant_id');
            $table->integer('espa_incharge_category');
            $table->integer('espa_incharge_consultant_id');
            $table->integer('espa_applicant_category');
            $table->integer('espa_applicant_consultant_id');
            $table->string('espa_building_official');
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
        Schema::dropIfExists('eng_sanitary_plumbing_apps');
    }
}
