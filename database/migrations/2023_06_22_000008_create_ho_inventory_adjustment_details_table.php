<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoInventoryAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_inventory_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('hia_id');
            $table->integer('ho_inv_posting_id');
            $table->integer('inv_cat_id');
            $table->integer('item_id');
            $table->string('hiad_series');
            $table->integer('hiad_qty');
            $table->integer('hiad_uom');
            $table->integer('hiad_status');
            $table->text('hiad_remarks');
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
        Schema::dropIfExists('ho_inventory_adjustment_details');
    }
}
