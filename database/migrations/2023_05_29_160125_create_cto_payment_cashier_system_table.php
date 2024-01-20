<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentCashierSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_cashier_system', function (Blueprint $table) {
            $table->id();
			$table->string('pcs_name')->length(50)->default('0')->comment('Ref-Table: cto_payment_or_type.id');
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
        Schema::dropIfExists('cto_payment_cashier_system');
    }
}
