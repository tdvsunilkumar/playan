<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoIssuancesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_issuances_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('issuance_id')->unsigned()->comment('gso_issuances');
            $table->integer('category_id')->unsigned()->comment('gso_issuances_categories');
            $table->integer('issuance_type_id')->unsigned()->comment('gso_issuances_types');
            $table->integer('item_id')->unsigned()->comment('gso_items');
            $table->integer('uom_id')->unsigned()->comment('gso_unit_of_measurements');
            $table->double('quantity')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('gso_issuances_details');
    }
}
