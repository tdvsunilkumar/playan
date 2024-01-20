<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GsoItemHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_item_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id')->unsigned();
            $table->string('trans_type',100)->nullable();
            $table->date('trans_date');
            $table->integer('trans_by')->unsigned();
            $table->integer('rcv_by')->unsigned()->nullable();
            $table->integer('based_qty')->unsigned();
            $table->integer('posted_qty')->unsigned();
            $table->integer('balance_qty')->unsigned();
            $table->integer('reserved_qty')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gso_item_history');
    }
}
