<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBacRfqsSuppliersCanvassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bac_rfqs_suppliers_canvass', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rfq_id')->unsigned()->comment('bac_rfqs');
            $table->integer('request_item_id')->unsigned()->comment('gso_departmental_requests_items');
            $table->text('description')->nullable();
            $table->double('unit_cost')->nullable();
            $table->double('total_cost')->nullable();
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
        Schema::dropIfExists('bac_rfqs_suppliers_canvass');
    }
}
