<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngDemolitionAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_demolition_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mun_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('eda_year');
            $table->string('eda_series_no');
            $table->string('eda_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no');
            $table->integer('p_code')->comment('Client Id');
            $table->string('eda_form_of_own')->comment('Form of Ownership');
            $table->string('eda_location')->comment('Location of Construction');
            $table->integer('ebs_id')->comment('ref-Table: ebs_bldg_scope.ebs_id, electronics = 1');
            $table->integer('ebot_id')->comment('ref-Table: eng_bldg_occupancy_type.ebot_id Use / Character of occupancy');
            $table->integer('eda_sign_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eda_sign_consultant_id')->comment('Full Name');
            $table->integer('eda_incharge_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eda_incharge_consultant_id')->comment('Full Name');
            $table->integer('eda_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eda_applicant_consultant_id')->comment('Full Name');
            $table->string('eda_owner_id')->comment('LOT OWNER');
            $table->string('eda_building_official')->comment('Building Name');
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
        Schema::dropIfExists('eng_demolition_app');
    }
}
