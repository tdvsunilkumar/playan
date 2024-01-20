<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpInspectionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfp_inspection_orders', function (Blueprint $table) {
            $table->id();
			$table->Integer('bend_id')->length(11)->comment('Ref-Table:bplo_business_endorsement.id');
			$table->Integer('bff_id')->length(11)->comment('foreign key bfp_application_form.bff_id reference');
            $table->Integer('client_id')->length(11)->comment('Ref-Table: clients.id');
            $table->Integer('brgy_id')->length(11)->comment('Ref-Table: barangays.id');
            $table->Integer('busn_id')->length(11)->length(11)->comment('Ref-Table: bplo_business.busn_id_no via busn_id');
            $table->Integer('bio_year')->length(4)->comment('foreign details bplo_applcation.ba_business_account_no (Covered Year + Business Account Number)');
            $table->string('bio_inspection_region')->length(10)->comment('Region Code');
            $table->Integer('bio_inspection_no')->length(14)->comment('Pre-Numbered');
            $table->date('bio_date')->comment('foreign key profile.p_code');
			$table->Integer('bio_assigned_to')->length(11)->comment('Ref-Table: hr_employees.id');
            $table->string('bio_inspection_proceed')->length(200)->comment('Company Name + Owner\'s Name + Complete Address');
            $table->string('bio_inspection_purpose')->length(200);
            $table->float('bio_inspection_duration')->length(4)->comment('Can be half-day/whole day/ 2 days');
            $table->text('bio_remarks')->length(200);
            $table->Integer('bio_recommending_approval')->length(11);
			$table->string('bio_recommending_position')->length(50);
			$table->Integer('bio_recommending_status')->length(1);
			$table->date('bio_recommending_approva_date')->nullable();
            $table->Integer('bio_approved')->length(4);
			$table->string('bio_approved_position')->length(50);
			$table->Integer('bio_approved_status')->length(1);
			$table->date('bio_approved_date')->nullable();
			$table->Integer('is_active');
			$table->Integer('created_by');
            $table->Integer('updated_by');
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
        Schema::dropIfExists('bfp_inspection_orders');
    }
}
