<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfp_certificates', function (Blueprint $table) {
            $table->id();
            $table->text('bfp_certificateno');
            $table->integer('bfpcert_code')->nullable();
            $table->integer('bff_code')->comment('foreign key bfp_application_form.bff_code reference');
            $table->integer('bfpas_code')->comment('foreign key bfp_application_assessment.bfpas_code reference');
            $table->integer('bio_code')->comment('foreign key bfp_inspection_order.bio_code reference');
            $table->integer('p_code')->comment('foreign key profile.p_code');
            $table->integer('brgy_code')->comment('foreign key bfp_application_form.bff_code reference');
            $table->string('brgy_name')->comment('foreign key barangay.brgy_code');
            $table->integer('ba_code')->comment('foreign key bplo_application.ba_code reference');
            $table->string('ba_business_account_no')->comment('foreign details bplo_application.ba_business_account_no. BAN. Business Account Number');
            $table->integer('bfpcert_type')->comment('1=For Certificate Of Occupancy, 2=For Business Permit(New/Renewal)');
            $table->date('bfpcert_date_issue')->comment('Date Generated');
            $table->date('bfpcert_date_expired')->comment('Date Expired');
            $table->integer('bfpcert_approved_recommending')->comment('foreign key profile.p_code recommending approval');
            $table->integer('bfpcert_approved')->comment('foreign key profile.p_code approver');
            $table->string('bio_remarks');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('bfp_certificates');
    }
}
