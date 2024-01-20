<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptLandStrippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_land_strippings', function (Blueprint $table) {
            $table->id();
            $table->string('rls_code',10);
            $table->string('rls_description',50);
            $table->integer('rls_percent')->default(1);
            $table->integer('rls_is_active')->default(1);
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
        Schema::dropIfExists('rpt_land_strippings');
    }
}
