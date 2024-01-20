<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoIssuanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_issuance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('issuance_id')->comment('ho_issuances.id');
            $table->integer('ho_inv_posting_id')->comment('ho_inventory. ho_inv_posting_id');
            $table->integer('hp_code');
            $table->integer('item_id');
            $table->integer('issuance_uom');
            $table->integer('issuance_quantity');
            $table->integer('issuance_base_uom');
            $table->integer('issuance_base_quantity');
            $table->integer('current_uom');
            $table->integer('current_quantity');
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
        Schema::dropIfExists('ho_issuance_details');
    }
}
