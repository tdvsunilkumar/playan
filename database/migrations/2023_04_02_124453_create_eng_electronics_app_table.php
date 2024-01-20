<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngElectronicsAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_electronics_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mum_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('eeta_year');
            $table->string('eeta_series_no');
            $table->string('eeta_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no');
            $table->integer('p_code')->comment('Client Id');
            $table->string('eeta_form_of_own')->comment('Form of Ownership');
            $table->string('eeta_location')->comment('Location of Construction');
            $table->integer('ebs_id')->comment('ref-Table: ebs_bldg_scope.ebs_id, electronics = 1');
            $table->integer('ebot_id')->comment('ref-Table: eng_bldg_occupancy_type.ebot_id Use / Character of occupancy');
            $table->integer('eest_id')->comment('ref-Table: eng_equipment_system_type.eest_id');
            $table->integer('eeta_sign_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eeta_sign_consultant_id')->comment('Full Name');
            $table->integer('eeta_incharge_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eeta_incharge_consultant_id')->comment('Full Name');
            $table->integer('eeta_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eeta_applicant_consultant_id')->comment('Full Name');
            $table->string('eeta_owner_id')->comment('LOT OWNER');
            $table->string('eeta_building_official')->comment('Building Name');
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
        Schema::dropIfExists('eng_electronics_app');
    }
}
