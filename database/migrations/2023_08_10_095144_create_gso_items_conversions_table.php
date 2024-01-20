<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoItemsConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_items_conversions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id')->unsigned()->comment('gso_items');
            $table->integer('based_uom')->unsigned()->comment('gso_unit_of_measurements');
            $table->double('based_quantity')->nullable();
            $table->integer('conversion_uom')->unsigned()->comment('gso_unit_of_measurements');
            $table->double('conversion_quantity')->nullable();
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
        Schema::dropIfExists('gso_items_conversions');
    }
}
