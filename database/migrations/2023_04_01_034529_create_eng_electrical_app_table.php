<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngElectricalAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_electrical_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mum_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('eea_year');
            $table->string('eea_series_no');
            $table->string('eea_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no');
            $table->date('eea_application_date')->comment('current date when bldg permit application created)');
            $table->date('eea_issued_date')->comment('date when bldg permit issued');
            $table->integer('p_code')->comment('Client Id');
            $table->integer('ebs_id')->comment('ref-Table: ebs_bldg_scope.ebs_id, electrical = 1');
            $table->integer('ebot_id')->comment('ref-Table: eng_bldg_occupancy_type.ebot_id');
            $table->integer('eeet_id')->comment('ref-Table: eng_electrical_equipment_type.eeet_id');
            $table->date('eea_date_of_construction')->comment('Date of Proposed Start of Construction');
            $table->double('eea_estimated_cost',8,2)->comment('Estimated Cost of Electrical Installation');
            $table->date('eea_date_of_completion')->comment('Estimated Cost of Electrical Installation');
            $table->integer('eea_prepared_by')->comment('ref-table:hr_employees.id');
            $table->integer('eea_sign_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eea_sign_consultant_id')->comment('Full Name');
            $table->integer('eea_incharge_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eea_incharge_consultant_id')->comment('Full Name');
            $table->integer('eea_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('eea_applicant_consultant_id')->comment('Full Name');
            $table->string('eea_owner_id')->comment('LOT OWNER');
            $table->double('eea_amount_due',8,2)->comment('Amount Due');
            $table->integer('eea_assessed_by')->comment('Assessed By');
            $table->string('eea_or_no')->comment('OR Number');
            $table->date('eea_date_paid')->comment('Date Paid');
            $table->string('eea_building_official')->comment('building_official');
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
        Schema::dropIfExists('eng_electrical_app');
    }
}
