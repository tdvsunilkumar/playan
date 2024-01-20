<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_receivables', function (Blueprint $table) {
            $table->id();
            $table->string('category')->comment('cemetery, housing,  rental, real-property,All');
            $table->integer('application_id')->comment('cemetery, housing,  rental App id');
            $table->integer('fund_code_id')->comment('ref-Table : acctg_account_fund_code.id');
            $table->integer('gl_account_id')->comment('ref-Table : acctg_account_general_ledgers.id');
            $table->integer('sl_account_id')->comment('ref-Table : acctg_account_subsidiary_ledgers.id');
            $table->string('description', 255)->comment('cto_cashier.or_no');
            $table->integer('top_no');
            $table->date('due_date');
            $table->string('amount_type')->comment('penalty, interest, revenue, Monthly');
            $table->double('amount_due',14,2)->comment('Amount Due');
            $table->double('amount_basic',14,2)->comment('Basic Due');
            $table->double('amount_set',14,2)->comment('Amount Set');
            $table->double('amount_socialize',14,2)->comment('Amount Due');
            $table->double('amount_pay',14,2)->comment('ref-Table : cto_cashier_details.total_amount');
            $table->double('remaining_amount',14,2)->comment('Amount Remaining');
            $table->integer('cashier_id');
            $table->string('or_no')->comment('Or No');
            $table->date('or_date')->comment('Or Date');
            $table->integer('is_paid');
            $table->integer('is_active')->comment('0 = Inactive, 1 = Active');
            $table->integer('status');
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
        Schema::dropIfExists('cto_receivables');
    }
}
