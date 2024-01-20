<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoInventoryUtilizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_inventory_utilizations', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id')->nullable();
            $table->string('util_rep_name')->nullable();
            $table->string('util_rep_range');
            $table->string('util_rep_year');
            $table->string('util_rep_type')->nullable();
            $table->decimal('util_rep_size', 10, 2)->nullable();
            $table->string('util_rep_path')->nullable();
            $table->string('util_rep_remarks');
            $table->tinyInteger('util_rep_status');
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
        Schema::dropIfExists('ho_inventory_utilizations');
    }
}
