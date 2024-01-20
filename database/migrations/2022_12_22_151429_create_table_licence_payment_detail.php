<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLicencePaymentDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licence_payment_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('tcp_id')->comment('foreign key of treasurer_cashier_pos table');
            $table->string('fund');
            $table->string('checknumber');
            $table->string('bankname');
            $table->date('date');
            $table->string('checktype');
            $table->string('amount');
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
        Schema::dropIfExists('licence_payment_detail');
    }
}
