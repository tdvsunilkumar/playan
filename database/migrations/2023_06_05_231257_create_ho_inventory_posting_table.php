<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoInventoryPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_inventory_posting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id');
            $table->integer('inv_cat_id');
            $table->integer('sup_id');
            $table->integer('cip_receiving');
            $table->string('cip_control_no', 20);
            $table->dateTime('cip_date_received')->nullable();
            $table->string('cip_item_code', 100);
            $table->string('cip_item_name', 100);
            $table->decimal('cip_unit_cost', 14, 2);
            $table->decimal('cip_total_cost', 14, 2);
            $table->integer('cip_qty_posted');
            $table->integer('cip_issued_qty');
            $table->integer('cip_balance_qty');
            $table->integer('cip_adjust_qty');
            $table->string('cip_uom', 20);
            $table->dateTime('cip_expiry_date')->nullable();
            $table->tinyInteger('cip_status');
            $table->text('cip_remarks')->nullable();
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
        Schema::dropIfExists('ho_inventory_posting');
    }
}
