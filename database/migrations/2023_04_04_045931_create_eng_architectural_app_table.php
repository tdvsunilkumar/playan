<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngArchitecturalAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_architectural_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mum_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('eea_year');
            $table->string('eea_series_no');
            $table->string('eea_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no');
            $table->integer('p_code')->comment('Client Id');
            $table->string('eea_form_of_own')->comment('Form of Ownership');
            $table->string('eea_location')->comment('Location of Construction');
            $table->integer('ebs_id')->comment('ref-Table: ebs_bldg_scope.ebs_id, electronics = 1');
            $table->integer('ebot_id')->comment('ref-Table: eng_bldg_occupancy_type.ebot_id Use / Character of occupancy');
            $table->integer('eeft_id')->comment('ref-Table: eng_architectural_features_type.eaft_id');
            $table->string('eaa_footprint')->comment('Foot Print');
            $table->string('eaa_impervious_area')->comment('Percentage of Impervious Surface Area');
            $table->string('eaa_unpaved_area')->comment('Percentage of unpaved surface area');
            $table->string('eaa_others_percentage')->comment('Others');
            $table->integer('ectfc_id')->comment('ref-Table: eng_architectural_features_type.eaft_id');
            $table->integer('eea_sign_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eea_sign_consultant_id')->comment('Full Name');
            $table->integer('eea_incharge_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eea_incharge_consultant_id')->comment('Full Name');
            $table->integer('eea_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eea_applicant_consultant_id')->comment('Full Name');
            $table->string('eea_owner_id')->comment('LOT OWNER');
            $table->string('eea_building_official')->comment('Building Name');
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
        Schema::dropIfExists('eng_architectural_app');
    }
}
