<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPropertyAccountabilitiesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_property_accountabilities_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('property_id')->unsigned()->comment('gso_property_accountabilities');
            $table->date('acquired_date')->nullable();            
            $table->integer('acquired_by')->nullable();          
            $table->integer('issued_by')->nullable();
            $table->date('returned_date')->nullable();           
            $table->integer('returned_by')->nullable();           
            $table->integer('received_by')->nullable();
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
        Schema::dropIfExists('gso_property_accountabilities_history');
    }
}
