<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploAssessPenaltyRateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_assess_penalty_rate_logs', function (Blueprint $table) {
            $table->id();
            $table->double('oldprate_surcharge_percent', 8, 2)->default(0);
            $table->double('oldprate_annual_interest_percentage', 8, 2)->default(0);
            $table->double('oldprate_max_penalty_years', 8, 2)->default(0);
            $table->double('oldprate_discount_rate', 8, 2)->default(0);
            $table->double('prate_surcharge_percent', 8, 2)->default(0);
            $table->double('prate_annual_interest_percentage', 8, 2)->default(0);
            $table->double('prate_max_penalty_years', 8, 2)->default(0);
            $table->double('prate_discount_rate', 8, 2)->default(0);
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
        Schema::dropIfExists('bplo_assess_penalty_rate_logs');
    }
}
