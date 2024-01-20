<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasurerIncomeAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasurer_income_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('tia_fund_code')->comment('Fund Code');
            $table->string('tia_account_code')->comment('Account Code');
            $table->string('tia_account_description')->comment('Description');
            $table->string('tia_account_short_name')->comment('Short Name');
            $table->double('tia_initial_amount', 8, 2)->comment('Initial Amount');
            $table->string('tia_group_code')->comment('Group Code');
            $table->string('tia_group_description')->comment('Group Description');
            $table->integer('is_active')->default(1); 
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
        Schema::dropIfExists('treasurer_income_accounts');
    }
}
