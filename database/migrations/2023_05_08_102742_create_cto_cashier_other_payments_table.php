<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoCashierOtherPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_cashier_other_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('opayment_year');
            $table->integer('opayment_month');
            $table->integer('check_type_id');
            $table->integer('cashier_id');
            $table->date('opayment_date');
            $table->integer('payment_terms')->comment('1=Cash, 2=Bank, 3=Check, 4= Credit Card, 5=Money Order, 6=Online Payment');
            $table->integer('fund_id')->comment('Ref-Table: acctg_fund_codes.id');
            $table->integer('bank_id')->comment('Ref-Table: cto_payment_bank.id');
            $table->integer('bank_account_no')->comment('Bank Account Number')->nullable();
            $table->string('opayment_transaction_no')->comment('Transaction No')->nullable();
            $table->string('opayment_check_no')->comment('Check No.')->nullable();
            $table->double('opayment_amount',12,2)->comment('Check Amount');
            $table->integer('status')->length(1)->default('0')->comment('0 = Inactive, 1 = Active');
            $table->integer('created_by')->length(14)->default('0');
            $table->integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('cto_cashier_other_payments');
    }
}
