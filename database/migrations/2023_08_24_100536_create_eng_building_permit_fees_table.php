<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngBuildingPermitFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_building_permit_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('ejr_id')->comment('job request id');
            $table->integer('ebpfd_id')->comment('eng division id');
            $table->double('ebpf_total_sqm')->length(10, 2);
            $table->double('ebpf_total_fees')->length(10, 2);
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
        Schema::dropIfExists('eng_building_permit_fees');
    }
}
