<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_clearances', function (Blueprint $table) {
            $table->id();
            $table->string('businessname')->comment('Business Name');
            $table->integer('year')->comment('Year');
            $table->integer('client_id')->comment('Ref table clients.id');
            $table->text('completeaddress')->comment('Complete Address');
            $table->date('date')->comment('Date');
            $table->integer('preparedby')->comment('Prepared By');
            $table->string('position')->comment('Position');
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('location_clearances');
    }
}
