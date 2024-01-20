<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitFeeAreamaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_fee_areamaster', function (Blueprint $table) {
            $table->id();
            $table->integer('bussiness_classifiaction_id');
            $table->integer('bussiness_activities_id');
            $table->double('area_minimum', 12, 2)->comment('Area Minimun');
            $table->double('area_maximum', 12, 2)->comment('Area Maximum');
            $table->double('fee_amount', 8, 2)->comment('Fee Amount');
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
        Schema::dropIfExists('permit_fee_areamaster');
    }
}
