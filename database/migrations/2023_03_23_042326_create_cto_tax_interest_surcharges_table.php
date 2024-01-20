<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoTaxInterestSurchargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_tax_interest_surcharges', function (Blueprint $table) {
            $table->id();
            $table->double('tis_interest_amount',14,2)->nullable();
            $table->integer('tis_interest_rate_type')->length(1)->default(0);
            $table->integer('tis_interest_schedule')->default(0);
            $table->integer('tis_interest_max_month')->length(2)->default(0);
            $table->integer('tis_interest_formula');
            $table->integer('tis_interest_compute_mode');

            $table->double('tis_surcharge_amount',14,2)->nullable();
            $table->integer('tis_surcharge_rate_type')->length(1)->default(0);
            $table->integer('tis_surcharge_schedule')->default(0);
            $table->integer('tis_surcharge_formula')->default(0);
            $table->integer('tis_surcharge_compute_mode')->default(0);
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
        Schema::dropIfExists('cto_tax_interest_surcharges');
    }
}
