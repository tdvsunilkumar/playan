<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPurchaseRequestsLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_purchase_requests_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_request_id')->unsigned()->comment('gso_purchase_requests');
            $table->text('item_description')->nullable()->comment('item description');
            $table->integer('uom_id')->unsigned()->comment('gso_unit_of_measurements');
            $table->text('remarks')->nullable();            
            $table->double('quantity_pr')->default(0);
            $table->double('request_unit_price')->default(0);
            $table->double('request_total_price')->default(0);
            $table->string('status', 40)->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
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
        Schema::dropIfExists('gso_purchase_requests_lines');
    }
}
