<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoAccountsReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_accounts_receivables', function (Blueprint $table) {
            $table->id();
            $table->integer('ar_year')->length(4)->comment('current year');
            $table->integer('ar_month')->length(2)->comment('Current Month');
            $table->integer('ar_no')->comment('ar no');
            $table->integer('ar_control_no')->comment('Combination of(ay_year-ar_no)');
            $table->date('ar_date')->comment('date');
            $table->integer('top_transaction_id')->length(11)->comment('Ref-Table: cto_top_transactions.id');
            $table->integer('payee_type')->length(1)->comment('1=Client(table:clients), 2=Citizen(table:citizens)');
            $table->integer('taxpayer_id')->length(11)->comment('Taxpayers and Citizen ID reference number');
            $table->integer('pcs_id')->length(11)->comment('Ref-Table: cto_payment_cashier_system.id)');
            $table->unsignedBigInteger('rp_property_code')->length(20)->comment('Ref-Table: rp_properties.rp_property_code. this is unique');
            $table->unsignedBigInteger('rp_code')->length(20)->comment('Ref-Table: rp_properties.id. this is dynamic and the most recent rpt_code');
            $table->integer('pk_id')->length(11)->comment('Ref-Table: rpt_property_kinds.id');
            $table->integer('rvy_revision_year_id')->length(11)->comment('Ref-Table: rpt_revision_year.id');
            $table->integer('brgy_code_id')->length(11)->comment('Ref-Table: barangays.id');
            $table->double('rp_assessed_value',14,5)->comment('Total Assessed Values for both the Land, Building & Properties');
            $table->double('rp_basic_amount',14,5)->comment('Tax, Fees & Other Charges');
            $table->double('rp_sef_amount',14,5)->comment('Tax, Fees & Other Charges');
            $table->double('rp_sht_amount',14,5)->comment('Tax, Fees & Other Charges');
            $table->integer('rp_last_cashier_id')->comment('the latest cashier ID');
            $table->integer('status')->length(1)->comment('when the amounts(like rp_basic_amount, rp_sef_amount and rp_sht_amount) already set to ZERO');
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
        Schema::dropIfExists('cto_accounts_receivables');
    }
}
