<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GsoIssuanceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_issuance_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('issue_id')->unsigned();
            $table->integer('inv_id')->unsigned()->nullable();;
            $table->integer('item_id')->unsigned();
            $table->string('item_agl_code', 20)->nullable();;
            $table->integer('item_type')->unsigned();
            $table->string('item_code',15);
            $table->string('item_name',150);
            $table->string('item_desc',150)->nullable();;
            $table->string('unit_code',10)->nullable();
            $table->decimal('inv_qty',14,3)->nullable();
            $table->decimal('inv_unit_cost',14,3)->nullable();
            $table->decimal('issued_qty',14,3)->nullable();
            $table->integer('issued_est_lifespan')->nullable();
            $table->integer('issue_type')->unsigned()->nullable();
            $table->string('issued_remarks',150)->nullable();
            $table->date('issued_date')->nullable();
            $table->integer('issued_year')->unsigned()->nullable();
            $table->integer('issued_no')->nullable();
            $table->string('issued_property_no',20)->nullable();
            $table->integer('issued_registered_by')->unsigned()->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->integer('issued_modified_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gso_issuance_details');
    }
}
