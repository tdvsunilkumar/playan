<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payee_id')->unsigned()->comment('cbo_payee');
            $table->string('vat_exempt', 10)->default('No');
            $table->string('voucher_no', 40);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('acctg_vouchers');
    }
}
