<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentBankDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_bank_description', function (Blueprint $table) {
            $table->id();
			$table->string('bdesc_code')->length(50);
			$table->string('bdesc_description')->length(100);
            $table->integer('bdesc_status')->length(1)->default('0');
			$table->text('bdesc_sample_doc')->nullable();
            $table->integer('created_by')->length(11)->default('0');
            $table->integer('updated_by')->length(11)->default('0');
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
        Schema::dropIfExists('cto_payment_bank_description');
    }
}
