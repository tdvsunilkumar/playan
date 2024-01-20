<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfarePwdTypeOfDisabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_pwd_type_of_disability', function (Blueprint $table) {
            $table->id();
			$table->string('wptod_description');
            $table->integer('wptod_is_active');
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
        Schema::dropIfExists('welfare_pwd_type_of_disability');
    }
}
