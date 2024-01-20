<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPurchaseOrdersPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_purchase_orders_posting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_order_id')->unsigned()->comment('gso_purchase_orders');
            $table->string('sequence_no', 20);
            $table->date('inspected_date')->nullable();
            $table->integer('inspected_by')->unsigned();
            $table->date('received_date')->nullable();
            $table->integer('received_by')->unsigned();
            $table->text('remarks')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gso_purchase_orders_posting');
    }
}
