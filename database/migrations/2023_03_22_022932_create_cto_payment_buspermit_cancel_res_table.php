<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentBuspermitCancelResTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_buspermit_cancel_res', function (Blueprint $table) {
            $table->id();
            $table->string('bcr_reason',100);
            $table->text('bcr_remarks')->nullable();
            $table->integer('bcr_is_active')->length(1)->default('0');
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
        Schema::dropIfExists('cto_payment_buspermit_cancel_res');
    }
}