<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_services', function (Blueprint $table) {
            $table->id();
            $table->integer('tfoc_id')->nullable()->comment('ref-Table: cto_tfoc.tfoc_id');
            $table->string('ho_service_name')->length(30);
            $table->string('ho_service_description')->nullable()->length(50);
            $table->integer('ho_service_department')->nullable()->length(20);
            $table->integer('top_transaction_type_id')->nullable()->length(30)->comment('ref-table:cto_top_transaction_type');
            $table->double('ho_service_amount',14,2)->nullable();
            $table->integer('ho_service_form')->nullable()->comment('1 - Hematology, 2 - Serology');
            $table->integer('ho_is_active')->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('ho_services');
    }
}
