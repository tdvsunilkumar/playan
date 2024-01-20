<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgVouchersSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_vouchers_series', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voucher_id')->nullable()->comment('acctg_vouchers');
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes');
            $table->string('series', 40);
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
        Schema::dropIfExists('acctg_vouchers_series');
    }
}
