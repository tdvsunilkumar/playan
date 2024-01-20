<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoUrinalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_urinalysis', function (Blueprint $table) {
            $table->id();
			$table->Integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id. Get Patient details');
			$table->Integer('hp_code')->length(14)->default('0')->comment('ref-table:hr_profile. hp_code. Get Doctor Name');
			$table->Integer('lab_req_id')->length(11)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_req_id');
            $table->string('lab_control_no')->length(20)->default('0')->nullable('ref-table:ho_lab_request.lab_control_no');
			$table->date('urin_date');
			$table->Integer('urin_age')->length(6)->default('0');
			$table->string('urin_or_num')->length(20)->default('0')->nullable();
			$table->Integer('urin_lab_year')->length(6)->default('0')->comment('incremental starts with 0001 reset every year');;
			$table->Integer('urin_lab_no')->length(4)->default('0')->comment('lab_year-lab_no.   2023-0001');;
			$table->string('urin_lab_num')->length(11)->default('0');
			$table->string('urin_color')->length(30)->default('0');
			$table->string('urin_appearance')->length(30)->default('0');
			$table->string('urin_leukocytes')->length(30)->default('0');
			$table->string('urin_nitrite')->length(30)->default('0');
			$table->string('urin_urobilinogen')->length(30)->default('0');
			$table->string('urin_protein')->length(30)->default('0');
			$table->float('urin_reaction')->length(11);
			$table->string('urin_blood')->length(30)->default('0');
			$table->float('urin_sg')->length(11)->default('0');
			$table->string('urin_ketones')->length(30)->default('0');
			$table->string('urin_bilirubin')->length(30)->default('0');
			$table->string('urin_glucose')->length(30)->default('0');
			$table->string('urin_rbc')->length(30)->default('0')->nullable();
			$table->string('urin_pc')->length(30)->default('0')->nullable();
			$table->string('urin_bac')->length(30)->default('0')->nullable();
			$table->string('urin_yc')->length(30)->default('0')->nullable();
			$table->string('urin_ec')->length(30)->default('0')->nullable();
			$table->string('urin_mt')->length(30)->default('0')->nullable();
			$table->string('urin_aup')->length(30)->default('0')->nullable();
			$table->string('urin_others1')->length(30)->default('0')->nullable();
			$table->string('urin_cast')->length(30)->default('0')->nullable();
			$table->string('urin_others2')->length(30)->default('0')->nullable();
			$table->string('urin_remarks')->length(100)->default('0')->nullable();
			$table->string('med_tech_position')->length(100)->default('0');
			$table->string('health_officer_position')->length(100)->default('0');
			$table->Integer('urin_is_active')->default('0');
			$table->Integer('urin_created_by')->length(11)->default('0');
			$table->Integer('urin_modified_by')->length(11)->default('0');	
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
        Schema::dropIfExists('ho_urinalysis');
    }
}
