<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoChargeDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_charge_descriptions', function (Blueprint $table) {
            $table->id();
            $table->string('charge_desc')->length(100);
            $table->integer('req_formula')->length(1)->default('0')->nullable();
            $table->integer('req_measure_pax')->length(1)->default('0')->nullable();
            $table->text('charge_remarks')->nullable();
            $table->integer('is_active')->length(1)->default('0');
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
        Schema::dropIfExists('cto_charge_descriptions');
    }
}
