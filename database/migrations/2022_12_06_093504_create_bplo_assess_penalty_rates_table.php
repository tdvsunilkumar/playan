<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploAssessPenaltyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_assess_penalty_rates', function (Blueprint $table) {
            $table->id();
            $table->double('prate_surcharge_percent', 8, 2)->default(0);
            $table->double('prate_annual_interest_percentage', 8, 2)->default(0);
            $table->double('prate_max_penalty_years', 8, 2)->default(0);
            $table->double('prate_discount_rate', 8, 2)->default(0);
            $table->integer('is_active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('bplo_assess_penalty_rates');
    }
}
