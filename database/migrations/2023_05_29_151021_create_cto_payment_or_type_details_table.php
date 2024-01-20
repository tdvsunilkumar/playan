<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentOrTypeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_or_type_details', function (Blueprint $table) {
            $table->id();
			$table->integer('ortype_id')->length(1)->default('0')->comment('Ref-Table: cto_payment_or_type.id');
			$table->integer('pcs_id')->length(1)->default('0')->comment('Ref-Table: cto_payment_cashier_system.id');
			$table->integer('created_by')->default('0');
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
        Schema::dropIfExists('cto_payment_or_type_details');
    }
}
