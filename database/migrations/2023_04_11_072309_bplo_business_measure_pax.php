<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessMeasurePax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_measure_pax', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('busn_id');
            $table->integer('busn_psic_id')->default(0);
            $table->integer('subclass_id')->default(0);
            $table->integer('tfoc_id')->default(0);
            $table->integer('buspx_charge_id')->default(0);
            $table->integer('buspx_capacity')->default(0)->nullable();
            $table->integer('buspx_no_units')->default(0)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_synced')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bplo_business_measure_pax');
    }
}
