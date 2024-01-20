<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentOrSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_or_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('ortype_id')->unsigned()->comment('cto_payment_or_type.ortype_id');
            $table->json('setup_details')->nullable();
            $table->integer('ors_is_active')->length(1)->default('0');
            $table->text('ors_remarks')->nullable();
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
        Schema::dropIfExists('cto_payment_or_setups');
    }
}
