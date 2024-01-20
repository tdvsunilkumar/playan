<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoCashierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_cashier', function (Blueprint $table) {
            $table->id();
            $table->integer('cashier_year')->comment('Current Year');
            $table->integer('cashier_month')->comment('month');
            $table->date('cashier_or_date')->nullable();
            $table->integer('cashier_issue_no')->comment('Reset Every Year')->nullable();
            $table->string('cashier_batch_no')->comment('Combination of table fields [cashier_year-cashier_issue_no]')->nullable(); 
            $table->integer('top_transaction_id')->comment('Ref-Table: cto_top_transactions.id')->default(0);
            $table->integer('tfoc_is_applicable')->comment('Make a default in saving of details in the system. 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->integer('payee_type')->comment('1=Client(table:clients), 2=Citizen(table:citizens)');
            $table->integer('client_citizen_id')->comment('Taxpayers and Citizen ID reference number');

            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->default(0);
            $table->integer('app_code')->comment('Application Type')->default(0);
            $table->integer('pm_id')->comment('Payment Mode')->default(0);
            $table->integer('pap_id')->comment('Assessment Period')->default(0);
            

            $table->string('cashier_particulars')->comment('Summary of the Payment');
            $table->double('total_amount',14,2)->comment('SUM of Ref-Table: cto_cashier_details.tfc_amount');
            $table->double('total_paid_amount',14,2)->comment('SUM of Ref-Table: cto_cashier_details.tfc_amount');
            $table->double('total_paid_surcharge',14,2)->comment('SUM of Ref-Table: cto_cashier_details.surcharge_fee');
            $table->double('total_paid_interest',14,2)->default(0);
            $table->double('total_amount_change',14,2)->comment('');
            $table->string('or_no')->comment('OR Number')->default(0);
            $table->integer('ortype_id')->comment('Ref-Table: cto_payment_or_type.id');
            $table->integer('payment_terms')->comment('1=Cash, 2=Bank, 3=Check, 4= Credit Card, 5=Online Payment')->nullable();
            $table->enum('payment_type', array('Online','Walk-In'))->nullable()->default('Walk-In')->nullable();;
            $table->text('payment_response')->comment('Token Response from Payment Gateway')->nullable();
            $table->text('payment_transaction_id')->comment('When Online Payment')->nullable();
            $table->text('ctc_place_of_issuance')->comment('Place of Issuance');
            $table->text('cashier_remarks')->comment('in case assessment has remarks')->nullable();

            $table->integer('tcm_id')->comment('when tax credit amount>0 Ref-Table: cto_tax_credit_management.id');
            $table->integer('tax_credit_gl_id')->comment('when tax credit amount>0');
            $table->integer('tax_credit_sl_id')->comment('when tax credit amount>0');
            $table->double('tax_credit_amount',14,2);
            $table->double('net_tax_due_amount',14,2)->comment('');
            $table->integer('tax_credit_is_useup')->length(1)->comment('1 = Used, 0 = Not Used')->default('0');
            $table->integer('previous_cashier_id')->comment('Credit amount applied cashier id');
            $table->integer('ocr_id')->default('0')->comment('Cancellation Reason Ref-Table: cto_payment_or_cancel_reason.id');
            $table->text('cancellation_reason')->nullable();
            $table->text('document_json')->nullable();
            $table->integer('status')->comment('Active/Cancelled')->default('0');
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
        Schema::dropIfExists('cto_cashier');
    }
}
