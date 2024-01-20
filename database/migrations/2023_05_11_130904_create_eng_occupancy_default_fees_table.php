<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngOccupancyDefaultFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_occupancy_default_fees', function (Blueprint $table) {
            $table->id();
            $table->text('fees_description')->comment('Fee Description');
            $table->integer('status')->default('1');
            $table->integer('created_by')->length(14)->default('0');
            $table->integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('eng_occupancy_default_fees');
    }
}
