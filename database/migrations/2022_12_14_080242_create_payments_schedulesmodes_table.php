<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsSchedulesmodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_schedulesmodes', function (Blueprint $table) {
            $table->id();
            $table->integer('mode')->comment('Mode');
            $table->string('psched_description')->comment('Description');
            $table->string('psched_short_desc')->comment('Short Description');
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
        Schema::dropIfExists('payments_schedulesmodes');
    }
}
