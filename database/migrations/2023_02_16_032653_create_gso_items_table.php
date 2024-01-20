<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->integer('item_type_id')->unsigned()->comment('gso_item_types');
            $table->integer('item_category_id')->unsigned()->comment('gso_item_categories');
            $table->integer('uom_id')->unsigned()->comment('gso_unit_of_measurements');
            $table->integer('purchase_type_id')->unsigned()->comment('gso_purchase_types');
            $table->string('code', 40);
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->double('weighted_cost')->comment('unit cost')->default(0);
            $table->double('latest_cost')->default(0);
            $table->double('latest_cost_date')->nullable();
            $table->double('life_span')->default(0);
            $table->double('quantity_inventory')->default(0);
            $table->double('quantity_reserved')->default(0);
            $table->double('quantity_hold')->default(0);
            $table->double('minimum_order_quantity')->default(0);
            $table->text('avatar')->nullable();
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
        Schema::dropIfExists('gso_items');
    }
}
