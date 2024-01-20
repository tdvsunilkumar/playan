<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentDueDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_due_dates', function (Blueprint $table) {
            $table->id();
            $table->integer('app_type_id');
            $table->string('due_1st_payment',20)->nullable();;
            $table->string('due_semi_annual_2nd_sem',20);
            $table->string('due_quarterly_2nd',20);
            $table->string('due_quarterly_3rd',20);
            $table->string('due_quarterly_4th',20);
            $table->string('due_attached_docs',255)->nullable();;
            $table->integer('due_is_active')->length(1)->default('0');
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
        Schema::dropIfExists('cto_payment_due_dates');
    }
}
