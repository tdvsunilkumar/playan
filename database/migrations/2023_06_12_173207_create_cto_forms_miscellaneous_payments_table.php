<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoFormsMiscellaneousPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_forms_miscellaneous_payments', function (Blueprint $table) {
            $table->id();
			$table->string('fpayment_app_name')->length(50);
			$table->string('fpayment_module_name')->length(50)->nullable();
			$table->integer('tfoc_id')->length(11)->nullable();
			$table->string('fpayment_remarks')->length(50)->nullable();
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
        Schema::dropIfExists('cto_forms_miscellaneous_payments');
    }
}
