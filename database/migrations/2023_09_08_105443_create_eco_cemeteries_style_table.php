<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoCemeteriesStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_cemeteries_style', function (Blueprint $table) {
            $table->id();
			$table->string('eco_cemetery_style',100);
            $table->integer('ecs_status')->length(1)->default('0');
            $table->integer('created_by')->length(11)->default('0');
            $table->integer('updated_by')->length(11)->default('0');
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
        Schema::dropIfExists('eco_cemeteries_style');
    }
}
