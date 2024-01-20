<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoCashierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_cashier_details', function (Blueprint $table) {
            $table->id();
            $table->integer('cashier_year')->comment('Current Year');
            $table->integer('cashier_month')->comment('month');
            $table->integer('cashier_issue_no')->comment('Reset Every Year');
            $table->string('cashier_batch_no')->comment('Combination of table fields [cashier_year-cashier_issue_no]'); 
            $table->integer('cashier_id')->comment('Raf-Table: cto_cashier.id');
            $table->integer('top_transaction_id')->comment('Ref-Table: cto_top_transactions.id');
            $table->integer('tfoc_is_applicable')->comment('Make a default in saving of details in the system. 1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->integer('payee_type')->comment('1=Client(table:clients), 2=Citizen(table:citizens)');
            $table->integer('client_citizen_id')->comment('Taxpayers and Citizen ID reference number');

            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->default(0);
            $table->integer('app_code')->comment('Application Type')->default(0);
            $table->integer('pm_id')->comment('Payment Mode')->default(0);
            $table->integer('pap_id')->comment('Assessment Period')->default(0);


            $table->integer('tfoc_id')->comment('Ref-Table:cto_bplo_assessment.tfoc_id');
            $table->integer('agl_account_id')->comment('Ref-Table:cto_bplo_assessment.agl_account_id');
            $table->integer('sl_id')->comment('Ref-Table:cto_bplo_assessment.sl_id');
            $table->double('ctc_taxable_amount',10,2)->comment('taxable Amount');
            $table->double('tfc_amount',10,2)->comment('Ref-Table:cto_bplo_assessment.tfoc_amount');

            $table->integer('surcharge_sl_id')->comment('Ref-Table:cto_bplo_assessment.surcharge_sl_id')->default(0);
            $table->double('surcharge_fee',10,2)->comment('Ref-Table:cto_bplo_assessment.surcharge_fee');

            $table->integer('interest_sl_id')->comment('Ref-Table:cto_bplo_assessment.interest_sl_id')->default(0);
            $table->double('interest_fee',10,2)->comment('Ref-Table:cto_bplo_assessment.interest_fee');

            $table->integer('section_id')->comment('Ref-Table:psic_section.id')->default(0);
            $table->integer('division_id')->comment('Ref-Table:psic_division.id')->default(0);
            $table->integer('group_id')->comment('Ref-Table:psic_group.id')->default(0);
            $table->integer('class_id')->comment('Ref-Table:psic_class.id')->default(0);
            $table->integer('subclass_id')->comment('Ref-Table:psic_subclasses.id')->default(0);

            $table->string('or_no')->comment('OR Number')->default(0);
            $table->integer('ortype_id')->comment('Ref-Table: cto_payment_or_type.id');
            $table->text('cashier_remarks')->comment('in case assessment has remarks')->nullable();
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
        Schema::dropIfExists('cto_cashier_details');
    }
}
