<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBldgFeesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_bldg_fees_details', function (Blueprint $table) {
            $table->id();
            $table->integer('ebpa_id')->unsigned()->comment('ref-Table: eng_bldg_permit_app.ebpa_id');
            $table->double('ebfd_bldg_est_cost',14,2)->comment('Total Estimated Cost - Building');
            $table->double('ebfd_elec_est_cost',14,2)->comment('Total Estimated Cost - Electrical');
            $table->double('ebfd_plum_est_cost',14,2)->comment('Total Estimated Cost - Pumbing');
            $table->double('ebfd_mech_est_cost',14,2)->comment('Total Estimated Cost - Mechanical');
            $table->double('ebfd_other_est_cost',14,2)->comment('Total Estimated Cost - Other Cost');
            $table->double('ebfd_total_est_cost',14,2)->comment('Total Estimated Cost - Estimated');
            $table->double('ebfd_equip_cost_1',14,2)->comment('Cost of equipment installed - Cost 1');
            $table->double('ebfd_equip_cost_2',14,2)->comment('Cost of equipment installed - Cost 2');
            $table->double('ebfd_equip_cost_3',14,2)->comment('Cost of equipment installed - Cost 3');
            $table->integer('ebfd_no_of_storey')->comment('Number of Storeys');
            $table->date('ebfd_construction_date')->comment('date of construction');
            $table->double('ebfd_floor_area',14,2)->comment('Total Floor Area');
            $table->string('ebfd_mats_const')->comment('Material of Construction');
            $table->integer('ebfd_sign_category')->comment('Consultant Category 1=Employee, 2=External Consultant');
            $table->integer('ebfd_sign_consultant_id')->comment('if sign category = 1 then ref-table:hr_employees.id/ if sign category = 2 then ref-table: eng_consultant_external.id');
            $table->string('ebfd_sign_prc_reg_no')->comment('PRC Reg No if sign category = 1 then ref-table:hr_employees.emp_prc_no/ if sign category = 2 then ref-table: eng_consultant_external.prc_no');
            $table->string('ebfd_sign_address_house_lot_no')->comment('House Lot No current complete address of the business owner');
            $table->string('ebfd_sign_address_street_name')->comment('Street Name current complete address of the business owner');
            $table->string('ebfd_sign_address_subdivision')->comment('Subdivision current complete address of the business owner');
            $table->string('ebfd_sign_brgy_code')->comment('ref-Table: barangay.brgy_code');
            $table->string('ebfd_sign_ptr_no')->comment('PTR No. of Engineer who signed and sealed plans &specs');
            $table->integer('ebfd_incharge_category')->comment('Consultant Category 1=Employee, 2=External Consultant');
            $table->integer('ebfd_incharge_consultant_id')->comment('Full Name if sign category = 1 then ref-table:hr_employees.id/ if sign category = 2 then ref-table: eng_consultant_external.id');
            $table->string('ebfd_incharge_prc_reg_no')->comment('PRC Registration No of Engineer whos incharge of Construction');
            $table->string('ebfd_incharge_address_house_lot_no')->comment('current complete address of the business owner');
            $table->string('ebfd_incharge_address_street_name')->comment('current complete address of the business owner');
            $table->string('ebfd_incharge_address_subdivision')->comment('current complete address of the business owner');
            $table->string('ebfd_incharge_brgy_code')->comment('ref-Table: barangay.brgy_code');
            $table->string('ebfd_incharge_ptr_no')->comment('PTR No. of Engineer whos incharge of construction');
            $table->date('ebfd_incharge_ptr_date_issued');
            $table->string('ebfd_incharge_ptr_place_issued');
            $table->string('ebfd_incharge_tan');
            $table->integer('ebfd_applicant_category')->comment('1=Employee, 2=External Consultant');
            $table->string('ebfd_applicant_consultant_id')->comment('Full Name if sign category = 1 then ref-table:hr_employees.id/ if sign category = 2 then ref-table: eng_consultant_external.id');
            $table->string('ebfd_applicant_comtaxcert')->comment('Community tax certificate of Applicant');
            $table->date('ebfd_applicant_date_issued')->comment('Date when community tax certificate issued');
            $table->date('ebfd_applicant_place_issued')->comment('Date where community tax issued');
            $table->string('ebfd_consent_tctoct_no')->comment('TCT/OCT No of lot owner consent');
            $table->string('ebfd_consent_id')->comment('Lot Owner ref-table: clients.is_rpt=1');
            $table->string('ebpa_address_house_lot_no')->comment('current complete address of the business owner');
            $table->string('ebpa_address_street_name')->comment('current complete address of the business owner');
            $table->string('ebpa_address_subdivision')->comment('current complete address of the business owner');
            $table->string('ebfd_consent_brgy_code')->comment('ref-Table: barangay.brgy_code');
            $table->string('ebfd_consent_comtaxcert')->comment('communit tax certificate of Lot Owner');
            $table->integer('ebost_is_active')->comment('if the status of the application type is active or not');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('eng_bldg_fees_details');
    }
}
