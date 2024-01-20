<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoPregnancyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_pregnancy', function (Blueprint $table) {
            $table->id();
			$table->Integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id. Get Patient details');
			$table->Integer('hp_code')->length(14)->default('0')->comment('ref-table:hr_profile. hp_code. Get Doctor Name');
			$table->Integer('lab_req_id')->length(11)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_req_id');
			$table->string('lab_control_no')->length(20)->default('0')->comment('ref-table:ho_lab_request.lab_control_no');
			$table->date('pt_date');
			$table->Integer('pt_age')->length(6)->default('0');
			$table->string('pt_or_num')->length(20)->nullable()->default('0');
			$table->Integer('pt_lab_year')->length(4)->default('0');
			$table->Integer('pt_lab_no')->length(11)->default('0');
			$table->string('pt_lab_num')->length(20)->default('0');
			$table->string('pt_specimen')->length(40)->default('0');
			$table->string('pt_brand_lot')->length(100)->default('0');
			$table->date('pt_expiry');
			$table->Integer('pt_result')->length(6)->default('0')->comment('0 for Negative, 1 for Positive');
			$table->string('pt_remarks')->length(100)->nullable()->default('0');
			$table->string('med_tech_position')->length(100)->default('0');
			$table->string('health_officer_position')->length(100)->default('0');
			$table->Integer('pt_is_active')->default('0');
			$table->Integer('pt_created_by')->default('0')->length(11);
			$table->Integer('pt_modified_by')->default('0')->length(11);
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
        Schema::dropIfExists('ho_pregnancy');
    }
}
