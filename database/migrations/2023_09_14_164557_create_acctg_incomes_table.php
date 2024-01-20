<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voucher_id')->unsigned()->comment('acctg_vouchers');
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->integer('sl_account_id')->unsigned()->comment('acctg_account_subsidiary_ledgers');
            $table->string('trans_no', 40);
            $table->string('trans_type', 40);
            $table->integer('trans_id')->nullable();
            $table->text('responsibility_center')->nullable();
            $table->text('items')->nullable();
            $table->double('quantity')->nullable();
            $table->integer('uom_id')->nullable()->comment('gso_unit_of_measurements');
            $table->double('amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->text('remarks')->nullable();
            $table->date('due_date')->nullable();
            $table->string('vat_type', 40)->default('Non-Vatable');
            $table->integer('ewt_id')->nullable()->comment('expanded_withholding_taxes');
            $table->double('ewt_amount')->nullable();
            $table->integer('evat_id')->nullable()->comment('expanded_vatable_taxes');
            $table->double('evat_amount')->nullable();            
            $table->string('status', 40)->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->integer('disapproved_by')->unsigned()->nullable();
            $table->text('disapproved_remarks')->nullable();
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
        Schema::dropIfExists('acctg_incomes');
    }
}
