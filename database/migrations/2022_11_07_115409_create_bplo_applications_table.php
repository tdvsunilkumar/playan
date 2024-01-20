<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ba_code')->nullable();
            $table->integer('ba_cover_year');
            $table->integer('ba_record_no');
            $table->string('ba_business_account_no')->nullable();
            $table->integer('app_type_id');
            $table->integer('ba_tax_filing_mode')->comment('1=Annual,2=Semi-Annual,3=Quarterly');
            $table->string('ba_business_name')->nullable();
            $table->string('ba_address_house_lot_no')->nullable();
            $table->string('ba_address_street_name')->nullable();
            $table->string('ba_address_subdivision')->nullable();
            $table->integer('barangay_id');
            $table->string('brgy_name')->nullable();

            $table->string('ba_municipality')->nullable();
            $table->integer('profile_id')->default(0);
            // $table->string('ba_p_first_name')->length(50)->nullable();
            // $table->string('ba_p_middle_name')->length(50)->nullable();
            // $table->string('ba_p_last_name')->length(50)->nullable();
            // $table->text('ba_p_address')->nullable();
            $table->string('ba_telephone_no')->nullable();
            $table->string('ba_mobile_no')->length(13);
            $table->string('ba_fax_no')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_tin')->nullable();
            $table->string('business_email')->nullable();

            $table->string('ba_tin_no')->nullable();
            $table->string('ba_email_address')->length(50);
            $table->string('ba_building_total_area_occupied')->nullable();
            $table->integer('ba_building_is_owned')->length(1);
            $table->string('ba_building_permit_no')->length(30)->nullable();
            $table->string('ba_building_certificate_occupancy_number')->nullable();
            $table->dateTime('ba_building_info_date_updated');
            $table->date('ba_building_permit_issued_date')->nullable();
            $table->string('ba_building_assessed_value')->nullable();
            $table->string('ba_building_property_index_number')->length(30)->nullable();
            $table->integer('ba_type_id')->length(1)->comment('Ownership & Other Information: 1=single proprietorship, 2=partnership, 3=Corporation.');
            $table->integer('ba_no_of_personnel')->length(4)->default(0);
            $table->integer('ba_plate_is_issued')->length(1)->comment('1=means issued, 0=means not issued');
            $table->integer('ba_plate_big_small')->length(1)->comment('1=means BIG, 0=means SMALL');
            $table->integer('ba_office_type')->length(1)->comment('1=means Main Offce, 0=means Branch Office')->default(0);
            
            $table->integer('ba_taxable_store_sell_combustible_substance')->length(4);
            $table->integer('ba_taxable_owned_truck_wheeler_10above')->length(4);
            $table->integer('ba_taxable_owned_truck_wheeler_6above')->length(4);
            $table->integer('ba_taxable_owned_truck_wheeler_4above')->length(4);
            $table->string('ba_registration_ctc_no')->length(30)->nullable();
            $table->string('ba_registration_ctc_place_of_issuance')->length(30)->nullable();


            $table->dateTime('ba_registration_ctc_issued_date');
            $table->integer('ba_registration_ctc_amount_paid')->length(8);
            $table->string('ba_registration_sss_number')->length(20)->nullable();
            $table->dateTime('ba_registration_sss_date_issued');
            $table->string('ba_locational_clearance_no')->length(30)->nullable();
            $table->dateTime('ba_locational_clearance_date_issued');

            $table->string('ba_bureau_domestic_trade_no')->length(30)->nullable();
            $table->dateTime('ba_bureau_domestic_trade_date_issued')->nullable();
            $table->string('ba_sec_registration_no')->length(30)->nullable();
            $table->date('ba_sec_registration_date_issued');
            $table->string('ba_dti_no')->length(30)->nullable();
            $table->string('ba_city_name')->length(30)->nullable();
            $table->date('ba_dti_date_issued')->nullable();

            $table->integer('psic_section_id');
            $table->integer('psic_division_id');
            $table->integer('psic_group_id');
            $table->integer('psic_class_id');
            $table->integer('psic_subclass_id');
            $table->text('subclass_description')->nullable();

            $table->string('ba_capital_investment')->nullable();
            $table->string('is_approved')->length(1)->default(0);
            $table->text('nature_of_bussiness_json')->nullable();
            $table->date('ba_date_started');
            $table->integer('is_active');
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
        Schema::dropIfExists('bplo_applications');
    }
}
