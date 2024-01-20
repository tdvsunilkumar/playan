<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgAccountGeneralLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_account_general_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('acctg_account_group_id')->unsigned();
            $table->integer('acctg_account_group_major_id')->unsigned();
            $table->integer('acctg_account_group_submajor_id')->unsigned();
            $table->integer('acctg_fund_code_id')->nullable();
            $table->string('prefix', 40);
            $table->string('code', 40);
            $table->text('description');
            $table->string('mother_code', 40)->nullable();
            $table->boolean('is_with_sl')->default(0);
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
        Schema::dropIfExists('acctg_account_general_ledgers');
    }
}