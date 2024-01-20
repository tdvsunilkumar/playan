<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoIssuancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_issuances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('receiver_name');
            $table->string('receiver_age')->nullable();
            $table->string('receiver_brgy')->nullable();
            $table->integer('ho_inv_posting_id');
            $table->integer('hp_code');
            $table->string('issuance_item_name');
            $table->string('issuance_code');
            $table->integer('issuance_quantity');
            $table->string('issuance_uom');
            $table->integer('issuance_type');
            $table->integer('issuance_status');
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
        Schema::dropIfExists('ho_issuances');
    }
}
