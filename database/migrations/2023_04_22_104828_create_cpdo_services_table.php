<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpdoServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpdo_services', function (Blueprint $table) {
            $table->id();
            $table->integer('tfoc_id')->comment('ref-Table: cto_tfoc.tfoc_id');
            $table->integer('cm_id')->comment('ref-Table: cpdo_module.cm_id');
            $table->integer('top_transaction_type_id')->comment('Ref-Table: cto_top_transaction_type.id');
            $table->integer('cs_is_active')->default('0');
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
        Schema::dropIfExists('cpdo_services');
    }
}
