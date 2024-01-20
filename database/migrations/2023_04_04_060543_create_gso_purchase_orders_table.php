<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_order_type_id')->unsigned()->comment('gso_purchase_order_types');
            $table->integer('rfq_id')->unsigned()->comment('bac_rfqs');
            $table->integer('supplier_id')->unsigned()->comment('gso_suppliers');
            $table->integer('payment_term_id')->unsigned()->comment('gso_payment_terms');
            $table->integer('procurement_mode_id')->unsigned()->comment('bac_procurement_modes');
            $table->integer('delivery_term_id')->unsigned()->comment('gso_delivery_terms');
            $table->string('purchase_order_no', 40)->nullable();
            $table->date('purchase_order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('delivery_place')->nullable();
            $table->text('remarks')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('status', 40)->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->integer('disapproved_by')->unsigned()->nullable();
            $table->text('disapproved_remarks')->nullable();
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
        Schema::dropIfExists('gso_purchase_orders');
    }
}
