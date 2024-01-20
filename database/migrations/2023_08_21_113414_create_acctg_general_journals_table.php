<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgGeneralJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_general_journals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes');
            $table->integer('payee_id')->unsigned()->comment('cbo_payee');
            $table->integer('fixed_asset_id')->unsigned()->comment('gso_property_accountabilities');
            $table->integer('division_id')->unsigned()->comment('acctg_departments_divisions');
            $table->string('general_journal_no', 40);
            $table->date('transaction_date')->nullable();
            $table->text('particulars')->nullable();
            $table->double('total_amount')->nullable();
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
        Schema::dropIfExists('acctg_general_journals');
    }
}
