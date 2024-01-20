<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngExcavationGroundAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_excavation_ground_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mum_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('eega_year');
            $table->string('eega_series_no');
            $table->string('eega_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no');
            $table->integer('p_code')->comment('Client Id');
            $table->string('eega_form_of_own')->comment('Form of Ownership');
            $table->string('eega_location')->comment('Location of Construction');
            $table->integer('ebs_id')->comment('ref-Table: ebs_bldg_scope.ebs_id, electronics = 1');
            $table->integer('ebot_id')->comment('ref-Table: eng_bldg_occupancy_type.ebot_id Use / Character of occupancy');
            $table->integer('eegt_id')->comment('ref-Table: eng_excavation_ground_type.eegt_id');
            $table->integer('eega_sign_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eega_sign_consultant_id')->comment('Full Name');
            $table->integer('eega_incharge_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eega_incharge_consultant_id')->comment('Full Name');
            $table->integer('eega_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eega_applicant_consultant_id')->comment('Full Name');
            $table->string('eega_owner_id')->comment('LOT OWNER');
            $table->string('eega_building_official')->comment('Building Name');
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
        Schema::dropIfExists('eng_excavation_ground_app');
    }
}
