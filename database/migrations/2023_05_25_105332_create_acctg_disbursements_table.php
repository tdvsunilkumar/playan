<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_disbursements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voucher_id')->nullable()->comment('acctg_vouchers');
            $table->integer('gl_account_id')->nullable()->comment('acctg_account_general_ledgers');
            $table->integer('sl_account_id')->nullable()->comment('	acctg_account_subsidiary_ledgers');
            $table->integer('payment_type_id')->nullable()->comment('acctg_payment_types');
            $table->dateTime('payment_date', $precision = 0);
            $table->double('amount')->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_account_no', 100)->nullable();
            $table->string('bank_account_name', 100)->nullable();
            $table->string('cheque_no', 100)->nullable();
            $table->string('cheque_date', 100)->nullable();
            $table->text('reference_no')->nullable();
            $table->text('attachment')->nullable();
            $table->string('status', 40)->default('draft');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('acctg_disbursements');
    }
}
