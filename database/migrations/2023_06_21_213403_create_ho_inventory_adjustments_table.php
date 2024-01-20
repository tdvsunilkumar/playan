<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoInventoryAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_inventory_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('hia_year');
            $table->integer('hia_no');
            $table->string('hia_series');
            $table->text('hia_remarks');
            $table->integer('hia_status');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('ho_inventory_adjustments');
    }
}
