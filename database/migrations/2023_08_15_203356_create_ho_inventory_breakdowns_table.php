<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoInventoryBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_inventory_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->integer('inv_posting_id');
            $table->integer('item_id');
            $table->date('hrb_date_received');
            $table->decimal('hrb_unit_cost')->length(10, 2);
            $table->decimal('hrb_total_cost')->length(10, 2);
            $table->integer('hrb_qty_posted');
            $table->integer('hrb_issued_qty')->nullable();
            $table->integer('hrb_balance_qty')->nullable();
            $table->integer('hrb_adjust_qty')->nullable();
            $table->string('hrb_uom');
            $table->date('hrb_expiry_date')->nullable();
            $table->string('hrb_status');
            $table->string('hrb_remarks');
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
        Schema::dropIfExists('ho_inventory_breakdowns');
    }
}
