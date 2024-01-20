<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_properties', function (Blueprint $table) {
            $table->id();
            $table->integer('rp_property_code')->nullable();
            $table->unsignedBigInteger('pk_id');
            $table->unsignedBigInteger('rvy_revision_year_id');
            $table->string('rvy_revision_code',10);
            $table->unsignedBigInteger('brgy_code_id');
            $table->string('brgy_no',5);
            $table->integer('rp_td_no')->nullable();
            $table->string('rp_suffix',5);
            $table->string('rp_tax_declaration_no',20)->nullable();
            $table->string('rp_tax_declaration_aform',100)->nullable();
            $table->string('pr_tax_arp_no',50)->nullable();
            $table->string('loc_local_code',5);
            $table->string('dist_code',10);
            $table->string('rp_section_no',5);
            $table->integer('rp_pin_no');
            $table->string('rp_pin_suffix',4);
            $table->string('rp_pin_declaration_no',50)->nullable();
            $table->string('rp_oct_tct_cloa_no',50);
            $table->string('rp_cadastral_lot_no',200);
            $table->integer('rpo_code');
            $table->integer('rp_administrator_code');
            $table->string('loc_group_brgy_no',5);
            $table->string('rp_location_number_n_street',70);
            $table->string('rp_bound_north',150);
            $table->string('rp_bound_south',150);
            $table->string('rp_bound_east',150);
            $table->string('rp_bound_west',150);
            $table->string('uc_code',10);
            $table->integer('rp_app_taxability')->comment('1=Taxable, 0=Exempt');
            $table->integer('rp_app_effective_year');
            $table->integer('rp_app_effective_quarter');
            $table->date('rp_app_posting_date');
            $table->string('rp_app_memoranda',250);
            $table->string('rp_app_extension_section',30);
            $table->string('rp_app_assessor_lot_no',30);
            $table->integer('rp_code_lref');
            $table->integer('rp_td_no_lref');
            $table->string('rp_suffix_lref',5);
            $table->integer('rp_section_no_lref');
            $table->integer('rp_pin_no_lref');
            $table->string('rp_pin_suffix_lref',5);
            $table->integer('rpo_code_lref');
            $table->string('rp_oct_tct_cloa_no_lref',50);
            $table->string('rp_cadastral_lot_no_lref',200);
            $table->decimal('rp_total_land_area',14,3);
            $table->integer('rp_code_bref');
            $table->integer('rp_section_no_bref');
            $table->string('rp_pin_suffix_bref',5)->nullable();
            $table->integer('rp_pin_no_bref');
            $table->string('rbf_building_roof_desc1',30);
            $table->string('rbf_building_roof_desc2',30);
            $table->string('rbf_building_roof_desc3',30);
            $table->string('rbf_building_floor_desc1',30);
            $table->string('rbf_building_floor_desc2',30);
            $table->string('rbf_building_floor_desc3',30);
            $table->string('rbf_building_wall_desc1',30);
            $table->string('rbf_building_wall_desc2',30);
            $table->string('rbf_building_wall_desc3',30);
            $table->string('bk_building_kind_code',10);
            $table->string('pc_class_code',10);
            $table->string('rp_bulding_permit_no',20);
            $table->string('rp_building_name',50);
            $table->string('rp_building_cct_no',40);
            $table->string('rp_building_unit_no',40);
            $table->decimal('rp_building_age',5,2);
            $table->integer('rp_building_no_of_storey');
            $table->integer('rp_constructed_month');
            $table->integer('rp_constructed_year');
            $table->integer('rp_occupied_month');
            $table->integer('rp_occupied_year');
            $table->integer('rp_building_completed_year');
            $table->integer('rp_building_completed_percent');
            $table->decimal('rp_building_gf_area',5,0);
            $table->decimal('rp_building_total_area',5,0);
            $table->decimal('rp_depreciation_rate',5,2);
            $table->decimal('rp_accum_depreciation',14,3);
            $table->decimal('rpb_accum_deprec_market_value',14,3);
            $table->decimal('al_assessment_level',5,3);
            $table->decimal('rpb_assessed_value',14,3);
            $table->integer('pk_is_active')->comment('1=active,0=in-active');
            $table->integer('is_deleted')->nullable();
            $table->integer('created_against')->nullable();
            $table->integer('created_against_appraisal')->nullable();
            $table->integer('rp_modified_by');
            $table->integer('rp_registered_by');
            $table->timestamps();

            /*$table->foreign('pk_id')
              ->references('id')->on('rpt_property_kinds')->onDelete('cascade');

            $table->foreign('rvy_revision_year_id')
              ->references('id')->on('rpt_revision_year')->onDelete('cascade');

            $table->foreign('brgy_code_id')
              ->references('id')->on('barangays')->onDelete('cascade');  */  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_properties');
    }
}
