<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgTrialBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_trial_balance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voucher_id')->unsigned()->comment('acctg_vouchers');
            $table->integer('payee_id')->unsigned()->comment('cbo_payee');
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->double('total_amount')->nullable();
            $table->string('entity', 40)->nullable();
            $table->string('entity_id', 40)->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->integer('posted_by')->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acctg_trial_balance');
    }
}
