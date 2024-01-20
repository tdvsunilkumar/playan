<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBldgPermitAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_bldg_permit_apps', function (Blueprint $table) {
            $table->id();
            $table->integer('ejr_id')->unsigned()->comment('table ref :eng_job_request.id');
            $table->integer('ebpa_mun_no')->unsigned()->comment('Palayan City profile_municipality.mun_no');
            $table->string('ebpa_application_no')->comment('unique code and series for the building permit application');
            $table->string('ebpa_permit_no')->comment('ebpa_permit_no');
            $table->string('eba_id')->comment('Dropdown (Application Type)');
            $table->datetime('ebpa_application_date')->comment('Date Of Application current date when bldg permit application created');
            $table->datetime('ebpa_issued_date')->comment('Date Issued date when bldg permit issued');
            $table->string('ebpa_owner_last_name')->comment('Owners Last Name');
            $table->string('ebpa_owner_first_name')->comment('Owners First Name');
            $table->string('ebpa_owner_mid_name')->comment('Owners Middle Name');
            $table->string('ebpa_owner_suffix_name')->comment('Suffix')->nullable();
            $table->string('ebpa_tax_acct_no')->comment('Tax Acct  no')->nullable();
            $table->string('ebpa_form_of_own')->comment('Form of Ownership')->nullable();
            $table->string('ebpa_economic_act')->comment('Main Economic Activity Kind Business')->nullable();
            $table->string('ebpa_address_house_lot_no')->comment('House Lot No')->nullable();
            $table->string('ebpa_address_street_name')->comment('Street Name')->nullable();
            $table->string('ebpa_address_subdivision')->comment('Subdivision')->nullable();
            $table->string('brgy_code')->comment('Barangay|Municipality|Province|Region');
            $table->string('ebpa_location')->comment('Location of Construction');
            $table->integer('ebs_id')->comment('Dropdown Scope oF work ref-Table: eng_bldg_scope.ebs_id');
            $table->string('ebpa_scope_remarks')->comment('Other Remarks (scope)');
            $table->integer('no_of_units')->comment('Number of Units')->nullable();
            $table->integer('ebot_id')->comment('Radio Button (Use Type of occupancy) ref-Table: eng_bldg_occupancy_type.ebot_id');
            $table->integer('ebost_id')->comment('Dropdown (Use Type of occupancy) ref-Table: eng_bldg_occupancy_sub_type.ebost_id');
            $table->string('ebpa_occ_other_remarks')->comment('Other Remarks (Residential)');
            $table->string('ebpa_bldg_official_name')->comment('Building Official Name');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('eng_bldg_permit_apps');
    }
}
