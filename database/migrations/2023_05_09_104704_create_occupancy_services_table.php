<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupancyServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupancy_services', function (Blueprint $table) {
            $table->id();
            $table->integer('tfoc_id')->unsigned()->comment('Service Fee ref-Table: cto_tfoc.tfoc_id');
            $table->string('eat_id')->comment('Application Type ref-Table: eng_application_type.eat_id');
            $table->integer('emf_id')->comment('ref-Table: eng_module_form.emf_id');
            $table->integer('top_transaction_type_id')->comment('ref-Table: cto_transactiontype_id');
            $table->integer('es_is_active')->default(0);
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
        Schema::dropIfExists('occupancy_services');
    }
}
