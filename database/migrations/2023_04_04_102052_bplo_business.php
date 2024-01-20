<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('locality_id')->nullable();
            $table->integer('busn_tax_year')->nullable();
            $table->integer('busn_tax_month')->nullable();
            $table->integer('busn_series_no')->nullable();
			$table->integer('is_individual')->nullable();
            $table->string('busn_tracking_no', 100)->nullable();
            $table->integer('app_code')->nullable();
            $table->integer('pm_id')->nullable();
            $table->string('busn_id_initial', 100)->nullable();
            $table->string('loc_local_id', 100)->nullable();
            $table->integer('busns_id')->nullable();
            $table->string('busns_id_no', 100)->nullable();
            $table->string('busn_name', 150)->nullable();
            $table->string('busn_trade_name', 150)->nullable();
            $table->integer('btype_id')->nullable();
            $table->string('busn_registration_no', 100)->nullable();
            $table->string('busn_tin_no', 100)->nullable();
            $table->integer('client_id')->nullable();
            $table->string('busn_office_main_building_no', 100)->nullable();
            $table->string('busn_office_main_building_name', 100)->nullable();
            $table->string('busn_office_main_add_block_no', 100)->nullable();
            $table->string('busn_office_main_add_lot_no', 100)->nullable();
            $table->string('busn_office_main_add_street_name', 100)->nullable();
            $table->string('busn_office_main_add_subdivision', 100)->nullable();
            $table->integer('busn_office_main_barangay_id')->nullable();
            $table->integer('busloc_id')->nullable();
            $table->integer('busn_bldg_area')->nullable();
            $table->integer('busn_bldg_total_floor_area')->nullable();
            $table->integer('busn_employee_no_female')->nullable();
            $table->integer('busn_employee_no_male')->nullable();
            $table->integer('busn_employee_total_no')->nullable();
            $table->integer('busn_employee_no_lgu')->nullable();
            $table->integer('busn_vehicle_no_van_truck')->nullable();
            $table->integer('busn_vehicle_no_motorcycle')->nullable();
            $table->integer('busn_bldg_is_owned')->nullable();
            $table->string('busn_bldg_tax_declaration_no',150)->nullable();
            $table->longText('floor_val_id')->nullable()->default(null)->comment('rpt_building_floor_values.id')->change();
            $table->integer('rp_code')->nullable();
            $table->string('rp_property_code',150)->nullable();
            $table->string('busn_bldg_property_index_no',150)->nullable();
            $table->string('online_busn_bldg_tax_declaration_no',150)->nullable();
            $table->string('online_busn_bldg_property_index_no',150)->nullable();
            $table->tinyInteger('busn_tax_incentive_enjoy')->nullable();
            $table->tinyInteger('busn_office_is_same_as_main')->nullable();
            $table->string('busn_office_building_no', 100)->nullable();
            $table->string('busn_office_building_name', 100)->nullable();
            $table->string('busn_office_add_block_no', 100)->nullable();
            $table->string('busn_office_add_lot_no', 100)->nullable();
            $table->string('busn_office_add_street_name', 100)->nullable();
            $table->string('busn_office_add_subdivision', 100)->nullable();
            $table->integer('busn_office_barangay_id')->nullable();
            $table->tinyInteger('busn_app_status')->nullable();
            $table->tinyInteger('busn_dept_involved')->nullable();
            $table->tinyInteger('busn_dept_completed')->nullable();
            $table->enum('busn_app_method', array('Online','Walk-In'))->nullable()->default('Walk-In');
            $table->tinyInteger('is_final_assessment')->default(0)->length(1)->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_synced')->default(0);
            $table->date('bplo_business')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bplo_business');
    }
}
