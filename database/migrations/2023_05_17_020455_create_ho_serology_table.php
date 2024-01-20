<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoSerologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_serology', function (Blueprint $table) {
            $table->id();
			$table->Integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id. Get Patient details');
			$table->Integer('hp_code')->length(14)->default('0')->comment('ref-table:hr_profile. hp_code. Get Doctor Name');
			$table->Integer('lab_req_id')->length(11)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_req_id');
            $table->string('lab_control_no')->length(20)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_control_no');
			$table->date('ser_date');
			$table->Integer('ser_age')->length(6)->default('0');
			$table->string('ser_or_num')->length(20)->default('0')->nullable()->comment('if free = 1');
			$table->Integer('ser_lab_year')->length(4)->default('0')->comment('Year today');
			$table->Integer('ser_lab_no')->length(11)->default('0')->comment('incremental starts with 0001 reset every year');
			$table->string('ser_lab_num')->length(20)->default('0')->comment('lab_year-lab_no.   2023-0001');
			$table->Integer('ser_hep_test')->length(6)->default('0')->nullable()->comment('0 not selected, 1 selected');
			$table->Integer('ser_hiv_test')->length(6)->default('0')->nullable()->comment('0 not selected, 1 selected');
			$table->Integer('ser_syp_test')->length(6)->default('0')->nullable()->comment('0 not selected, 1 selected');
			$table->string('ser_hep_specimen')->length(20)->default('0')->nullable();
			$table->string('ser_hep_brand')->length(20)->default('0')->nullable();
			$table->string('ser_hep_lot')->length(20)->default('0')->nullable();
			/* $table->date('ser_hep_exp');
			$table->Integer('ser_hep_result')->length(6)->nullable()->comment('0 is Negative, 1 is Positive');
			$table->string('ser_hiv_specimen')->length(20)->default('0')->nullable();
			$table->string('ser_hiv_brand')->length(20)->default('0')->nullable();
			$table->string('ser_hiv_lot')->length(20)->default('0')->nullable();
			$table->date('ser_hiv_exp');
			$table->Integer('ser_hiv_result')->length(6)->nullable()->comment('0 is Negative, 1 is Positive');
			$table->Integer('ser_syp_method')->length(6)->nullable()->comment('Selection Below');
			$table->string('ser_syp_specimen')->length(20)->default('0')->nullable();
			$table->string('ser_syp_brand')->length(20)->default('0')->nullable();
			$table->string('ser_syp_lot')->length(20)->default('0')->nullable();
			$table->date('ser_syp_exp');
			$table->Integer('ser_syp_result')->length(6)->nullable()->comment('0 is Negative, 1 is Positive'); */
			$table->string('med_tech_position')->length(100)->default('0');
			$table->string('health_officer_position')->length(100)->default('0');
			$table->string('ser_remarks')->length(100)->default('0');
			$table->Integer('ser_is_active')->default('0');
			$table->Integer('ser_created_by')->length(11)->default('0');
			$table->Integer('ser_modified_by')->length(11)->default('0');	
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
        Schema::dropIfExists('ho_serology');
    }
}
