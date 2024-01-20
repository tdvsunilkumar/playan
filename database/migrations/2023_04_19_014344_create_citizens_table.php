<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitizensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizens', function (Blueprint $table) {
            $table->id();
            $table->string('cit_last_name');
            $table->string('cit_first_name');
            $table->string('cit_middle_name');
            $table->string('cit_suffix_name')->nullable();
            $table->string('cit_house_lot_no')->nullable();
            $table->string('cit_street_name')->nullable();
            $table->string('cit_subdivision')->nullable();
            $table->integer('brgy_id')->length(11)->comment('ref-Table: barangays.brgy_id');
            $table->string('cit_gender')->nullable()->comment('male, female');
            $table->integer('ccs_id')->length(11)->comment('ref: constants.citCivilStatus');
            $table->date('cit_date_of_birth');
            $table->integer('cit_age')->length(11)->comment('compute based from date of birth, read only, disable edit function');
            $table->string('cit_place_of_birth')->nullable();
            $table->string('cit_blood_type')->nullable();
            $table->string('cit_mobile_no')->nullable();
            $table->string('cit_telephone_no')->nullable();
            $table->string('cit_fax_no')->nullable();
            $table->string('cit_tin_no')->nullable();
            $table->string('cit_nationality')->nullable();
            $table->string('cit_email_address')->nullable();
            $table->integer('cea_id')->length(11)->comment('ref: constants.citEducationalAttainment')->nullable();
            $table->string('cit_height')->nullable();
            $table->string('cit_weight')->nullable();
            $table->string('cit_sss_no')->nullable();
            $table->string('cit_gsis_no')->nullable();
            $table->string('cit_pagibig_no')->nullable();
            $table->string('cit_psn_no')->nullable();
            $table->string('cit_philhealth_no')->nullable();
            $table->integer('cit_created_by')->unsigned()->comment('reference profile.reg_code of the system who create the application type');
            $table->timestamp('cit_created_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Format: [yyyy-mm-dd hh:mm:ss]. ');
            $table->integer('cit_modified_by')->unsigned()->nullable()->comment('reference profile.reg_code of the system who modified  the application type');
            $table->timestamp('cit_modified_date')->nullable()->comment('Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizens');
    }
}
