<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfp_application_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('bff_id')->nullable()->comment('Ref-Table: bfp_application_form.id');
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.busn_id');
            $table->integer('client_id')->comment('Owners Information foreign key clients.id');
            $table->integer('barangay_id')->comment('Barangay No. Ref-Table: barangays.id');
            $table->string('bff_application_type')->comment('FSIC/FSEC/Others');
            $table->string('bff_application_no')->comment('FSIC Applicaton Number.');
            $table->string('bfpas_ops_code')->comment('OPS Code');
            $table->string('bfpas_ops_year')->comment('OPS year');
            $table->integer('bfpas_ops_no')->comment('OPS no');
            $table->string('bfpas_control_no')->comment('Combination of (bfpas_ops_code - bfpas_ops_year - bfpas_ops_no)');
            $table->double('bfpas_total_amount', 8, 2)->comment('Total Amount of the assessment');
            $table->double('bfpas_total_amount_paid', 8, 2)->comment('Total Amount paid in the cashier');
            $table->integer('bfpas_is_fully_paid')->comment('When the assessment is already paid')->default(0);
            $table->string('bfpas_payment_or_no')->comment('Official Receipt no. when the assessment is already paid');
            $table->integer('bfpas_payment_type')->comment('1=Cash, 2=Check, 3=Money Order');
            $table->date('bfpas_date_paid')->comment('Payment Date');
            $table->text('bfpas_document_json')->nullable()->comment('Json');
            $table->string('bfpas_remarks')->comment('in case assessment has remarks');
            $table->integer('ocr_id')->default('0')->comment('Cancellation Reason Ref-Table: cto_payment_or_cancel_reason.id');
            $table->text('cancellation_reason')->nullable();
            $table->integer('payment_status')->length(1)->comment('0-Pending, 1-Paid, 2-Cancelled')->default('0');
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
        Schema::dropIfExists('bfp_application_assessments');
    }
}
