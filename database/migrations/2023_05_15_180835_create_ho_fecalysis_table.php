<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoFecalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_fecalysis', function (Blueprint $table) {
            $table->id();
			$table->Integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id. Get Patient details');
			$table->Integer('hp_code')->length(14)->default('0')->comment('ref-table:hr_profile. hp_code. Get Doctor Name');
			$table->Integer('lab_req_id')->length(11)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_req_id');
			$table->string('lab_control_no')->length(20)->default('0')->comment('ref-table:ho_lab_request.lab_control_no');
			$table->date('fec_date');
			$table->Integer('fec_age')->length(6)->default('0');
			$table->string('fec_or_num')->length(20)->default('0')->nullable();
			$table->Integer('fec_lab_year')->length(4)->default('0');
			$table->Integer('fec_lab_no')->length(11)->default('0');
			$table->string('fec_lab_num')->length(20)->default('0');
			$table->string('fec_color')->length(30)->default('0');
			$table->string('fec_consistency')->length(30)->default('0');
			$table->string('fec_rbc')->length(30)->default('0');
			$table->string('fec_wbc')->length(30)->default('0');
			$table->string('fec_bacteria')->length(30)->default('0');
			$table->string('med_tech_position')->length(100)->default('0');
			$table->string('health_officer_position')->length(100)->default('0');
			$table->string('fec_fat_glob')->length(30)->default('0')->nullable();
			$table->string('fec_parasite')->length(100)->default('0')->nullable();
			$table->string('fec_others')->length(100)->default('0')->nullable();
			$table->Integer('fec_is_active')->default('0');
			$table->Integer('fec_created_by')->length(11)->default('0');
			$table->Integer('fec_modified_by')->length(11)->default('0');
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
        Schema::dropIfExists('ho_fecalysis');
    }
}
