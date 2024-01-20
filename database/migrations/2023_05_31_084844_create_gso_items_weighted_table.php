<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoItemsWeightedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_items_weighted', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('posting_line_id')->unsigned()->comment('gso_purchase_orders_posting_lines');
            $table->integer('item_id')->unsigned()->comment('gso_items');
            $table->double('weighted_cost')->nullable();
            $table->date('weighted_cost_date')->nullable();
            $table->double('latest_cost')->nullable();
            $table->date('latest_cost_date')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
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
        Schema::dropIfExists('gso_items_weighted');
    }
}
