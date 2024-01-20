<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoHematologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_hematology', function (Blueprint $table) {
            $table->id();
			$table->Integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id. Get Patient details');
			$table->Integer('hp_code')->length(14)->default('0')->comment('ref-table:hr_profile. hp_code. Get Doctor Name');
			$table->Integer('chc_id')->length(1)->default('0')->nullable()->comment('ref-table:ho_hematology_category.chc_id');
			$table->Integer('lab_req_id')->length(11)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_control_no');
			$table->string('lab_control_no')->length(20)->default('0')->nullable()->comment('ref-table:ho_lab_request.lab_control_no');
			$table->date('hema_date');
			$table->Integer('hema_age')->length(6)->default('0');
			$table->Integer('hema_lab_year')->length(4)->default('0');
			$table->Integer('hema_lab_no')->length(11)->default('0');
			$table->string('hema_lab_num')->length(11)->nullable()->default('0');
			$table->string('hema_or_num')->length(11)->nullable()->default('0');
			$table->string('hema_wbc')->length(11)->nullable()->default('0');
			$table->string('hema_lymph_num')->length(11)->nullable()->default('0');
			$table->string('hema_mid_num')->length(11)->nullable()->default('0');
			$table->string('hema_gran_num')->length(11)->nullable()->default('0');
			$table->string('hema_lymph_pct')->length(11)->nullable()->default('0');
			$table->string('hema_mid_pct')->length(11)->nullable()->default('0');
			$table->string('hema_gran_pct')->length(11)->nullable()->default('0');
			$table->string('hema_hgb')->length(11)->nullable()->default('0');
			$table->string('hema_rbc')->length(11)->nullable()->default('0');
			$table->string('hema_hct')->length(11)->nullable()->default('0');
			$table->string('hema_mcv')->length(11)->nullable()->default('0');
			$table->string('hema_mch')->length(11)->nullable()->default('0');
			$table->string('hema_mchc')->length(11)->nullable()->default('0');
			$table->string('hema_rdw_cv')->length(11)->nullable()->default('0');
			$table->string('hema_rdw_sd')->length(11)->nullable()->default('0');
			$table->string('hema_plt')->length(11)->nullable()->default('0');
			$table->string('hema_mpv')->length(11)->nullable()->default('0');
			$table->string('hema_pdw')->length(11)->nullable()->default('0');
			$table->string('hema_pct')->length(11)->nullable()->default('0');
			$table->string('hema_blood_type')->length(11)->nullable()->default('0');
			$table->string('med_tech_position')->length(100)->nullable()->default('0');
			$table->string('health_officer_position')->length(100)->nullable()->default('0');
			$table->string('hema_remarks')->length(100)->nullable()->default('0');
			$table->string('hema_is_active')->length(100)->nullable()->default('0');
			$table->Integer('hema_created_by')->default('0')->length(11);
			$table->Integer('hema_modified_by')->default('0')->length(11);
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
        Schema::dropIfExists('ho_hematology');
    }
}
