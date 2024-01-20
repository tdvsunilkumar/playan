<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('bas_code');
            $table->integer('application_id');
            $table->string('ba_business_account_no');
            $table->string('app_type');
            $table->integer('ba_tax_filing_mode')->comment('1=Annual,2=Semi-Annual,3=Quarterly');
            $table->string('brgy_code');
            $table->string('ba_municipality');
            $table->integer('profile_id');
            $table->integer('engneering_feeid');
            $table->text('engneeringfee_description')->nullable();
            $table->double('engneering_amount', 8, 2);
            $table->string('engneering_code')->nullable();
            $table->integer('ba_capital_investment');
            $table->double('bas_gross_sales_amount', 8, 2);
            $table->integer('ba_building_total_area_occupied')->length(5);
            $table->integer('ba_taxable_owned_truck_wheeler_10above')->length(2);
            $table->integer('ba_taxable_owned_truck_wheeler_6above')->length(2);
            $table->integer('ba_taxable_owned_truck_wheeler_4above')->length(2);
            $table->integer('bas_applicable_quarter_from')->length(1);
            $table->integer('bas_applicable_quarter_to')->length(1);
            $table->integer('section_id');
            $table->integer('division_id');
            $table->integer('group_id');
            $table->integer('class_id');
            $table->integer('subclass_id');
            $table->integer('tax_class_id')->comment('foreign key tax_classes id');
            $table->integer('tax_type_id')->comment('foreign key tax_types id');
            $table->integer('bbc_classification_id')->comment('foreign key bplo_business_classifications id');
            $table->integer('bba_id')->comment('foreign key bplo_business_activities id');
            $table->integer('bas_is_essential_commodities');
            $table->integer('bas_no_of_days')->length(3);
            $table->string('bas_remarks');
            $table->integer('no_of_personnel')->nullable()->comment('Occupational Tax');
            $table->integer('big')->nullable();
            $table->integer('small')->nullable();
            $table->string('lessor');
            $table->string('lessoraddress');
            $table->string('administrator');
            $table->double('rentalstart', 8, 2);
            $table->double('presentrate', 8, 2);
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('bplo_assessments');
    }
}
