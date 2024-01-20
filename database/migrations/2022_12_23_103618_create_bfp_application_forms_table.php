<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('bfp_application_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('ba_code')->nullable()->comment('foreign key bplo_application.ba_code reference');
            $table->string('ba_business_account_no')->nullable()->comment('foreign details bplo_application.ba_business_account_no. BAN. Business Account Number');
            $table->integer('profile_id')->nullable()->comment('foreign key profile.p_code');
            $table->integer('p_code')->nullable()->comment('foreign key profile.p_code');
            $table->string('brgy_code')->nullable()->comment('foreign key barangay.brgy_code');
            $table->integer('bff_year')->nullable();
            $table->date('bff_date')->nullable();
            $table->string('bff_application_type')->nullable()->comment('FSIC/FSEC/Others');
            $table->integer('bff_application_no')->nullable();
            $table->integer('bff_representative_code')->nullable()->comment('foreign details profile.p_code for representative');
            $table->integer('subclass_code')->nullable()->comment('foreign key psic_subclass.subclass_code');
            $table->integer('bot_code')->nullable()->comment('foreign key bfp_occupancy_type.id');
            $table->string('bot_occupancy_type')->nullable()->length(1);
            $table->string('ba_building_total_area_occupied')->nullable()->length(1);
            $table->string('bff_no_of_storey')->nullable();
            $table->string('bff_email_addrress')->nullable();
            $table->string('bff_telephone_no')->nullable();
            $table->string('bff_mobile_no')->nullable();
            $table->integer('bff_req_occupancy_fsic')->nullable();
            $table->integer('bff_req1')->nullable();
            $table->string('bff_req1_file')->nullable();
            $table->integer('bff_req2')->nullable();
            $table->string('bff_req2_file')->nullable();
            $table->integer('bff_req3')->nullable();
            $table->string('bff_req3_file')->nullable();
            $table->integer('bff_req4')->nullable();
            $table->string('bff_req4_file')->nullable();
            $table->integer('bff_req5')->nullable();
            $table->string('bff_req5_file')->nullable();
            $table->integer('bff_req_new_business')->nullable();
            $table->integer('bff_req6')->nullable();
            $table->string('bff_req6_file')->nullable();
            $table->integer('bff_req7')->nullable();
            $table->string('bff_req7_file')->nullable();
            $table->integer('bff_req8')->nullable();
            $table->string('bff_req8_file')->nullable();
            $table->integer('bff_req9')->nullable();
            $table->string('bff_req9_file')->nullable();
            $table->integer('bff_req_renew_business')->nullable();
            $table->integer('bff_req10')->nullable();
            $table->string('bff_req10_file')->nullable();
            $table->integer('bff_req11')->nullable();
            $table->string('bff_req11_file')->nullable();
            $table->integer('bff_req12')->nullable();
            $table->string('bff_req12_file')->nullable();
            $table->integer('bff_req13')->nullable();
            $table->string('bff_req13_file')->nullable();
            $table->string('bff_verified_by')->nullable();
            $table->datetime('bff_veridifed_date')->nullable();
            $table->date('bff_cro_date')->nullable();
            $table->string('bff_cro_in')->nullable();
            $table->string('bff_cro_out')->nullable();
            $table->date('bff_fca_date')->nullable();
            $table->string('bff_fca_in')->nullable();
            $table->string('bff_fca_out')->nullable();
            $table->date('bff_fcca_date')->nullable();
            $table->string('bff_fcca_in')->nullable();
            $table->string('bff_fcca_out')->nullable();
            $table->date('bff_cfses1_date')->nullable();
            $table->string('bff_cfses1_in')->nullable();
            $table->string('bff_cfses1_out')->nullable();
            $table->date('bff_fsi_date')->nullable();
            $table->string('bff_fsi_in')->nullable();
            $table->string('bff_fsi_out')->nullable();
            $table->date('bff_cfses2_date')->nullable();
            $table->string('bff_cfses2_in')->nullable();
            $table->string('bff_cfses2_out')->nullable();
            $table->date('bff_cfm_mfm_date')->nullable();
            $table->string('bff_cfm_mfm_in')->nullable();
            $table->string('bff_cfm_mfm_out')->nullable();
            $table->integer('bff_status')->nullable()->default(1)->comment('1=Closed, 0=Open');
            $table->string('bff_remarks',50)->nullable();
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
        Schema::dropIfExists('bfp_application_forms');
    }
}
