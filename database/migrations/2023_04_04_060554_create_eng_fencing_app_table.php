<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngFencingAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_fencing_app', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ejr_id')->comment('ref-Table: eng_job_request.ejr_id');
            $table->integer('mun_no')->comment('ref-Table: profile_municipality.mun_no');
            $table->string('efa_year');
            $table->string('efa_series_no');
            $table->string('efa_application_no')->comment('Combination(ebpa_year + ebpa_series_no)');
            $table->string('ebpa_permit_no')->comment('ref-Table:eng_bldg_permit_app.ebpa_permit_no');
            $table->integer('p_code')->comment('Client Id');
            $table->string('efa_form_of_own')->comment('Form of Ownership');
            $table->string('ebpa_location')->comment('Location of Construction');
            $table->integer('ebs_id')->comment('ref-Table: ebs_bldg_scope.ebs_id, fencing = 1');
            $table->integer('eft_id')->comment('ref-Table: eng_fencing_type.eft_id');
            $table->integer('efa_sign_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('efa_sign_consultant_id')->comment('Full Name');
            $table->integer('efa_inspector_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('efa_inspector_consultant_id')->comment('Full Name');
            $table->integer('efa_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->integer('efa_applicant_consultant_id')->comment('Full Name');
            $table->string('efa_owner_id')->comment('LOT OWNER');
            $table->double('efa_linegrade_amount',14,2)->comment('Line and Grade - Amount Due');
            $table->string('efa_linegrade_processed_by')->comment('Line and Grade - Processed By');
            $table->string('efa_linegrade_or_no')->comment('Line and Grade - O.R Number');
            $table->date('efa_linegrade_date_paid')->comment('Line and Grade - Date Paid');
            $table->double('efa_fencing_amount',14,2)->comment('Fencing - Amount Due');
            $table->string('efa_fencing_processed_by')->comment('Fencing - Processed By');
            $table->string('efa_fencing_or_no')->comment('Fencing - O.R Number');
            $table->date('efa_fencing_date_paid')->comment('Fencing - Date Paid');
            $table->double('efa_electrical_amount',14,2)->comment('Electrical - Amount Due');
            $table->string('efa_electrical_processed_by')->comment('Electrical - Processed By');
            $table->string('efa_electrical_or_no')->comment('Electrical - O.R Number');
            $table->date('efa_electrical_date_paid')->comment('Electrical - Date Paid');
            $table->double('efa_others_amount',14,2)->comment('Others - Amount Due');
            $table->string('efa_others_processed_by')->comment('Others - Processed By');
            $table->string('efa_others_or_no')->comment('Others - O.R Number');
            $table->date('efa_others_date_paid')->comment('Others - Date Paid');
            $table->double('efa_total_amount',14,2)->comment('Total - Amount Due');
            $table->string('efa_total_processed_by')->comment('Total - Processed By');
            $table->string('efa_total_or_no')->comment('Total - O.R Number');
            $table->string('measurelength')->comment('Measure Length')->nullable();
            $table->string('measureheight')->comment('Measure Height')->nullable();
            $table->string('typeoffencing')->comment('typeoffencing')->nullable();
            $table->date('efa_total_date_paid')->comment('Total - Date Paid');
            $table->string('efa_building_official')->comment('Building Name');
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
        Schema::dropIfExists('eng_fencing_app');
    }
}
