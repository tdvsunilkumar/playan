<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_code',100);
            $table->string('bank_branch_code',100)->nullable();
            $table->string('bank_desc',100)->nullable();
            $table->string('bank_address',100)->nullable();
            $table->integer('bank_is_active')->length(1)->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('cto_payment_banks');
    }
}
