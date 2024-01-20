<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfarePwdCauseOfDisabilityInbornTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_pwd_cause_of_disability_inborn', function (Blueprint $table) {
            $table->id();
			$table->string('wpcodi_description');
            $table->integer('wpcodi_is_active');
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
        Schema::dropIfExists('welfare_pwd_cause_of_disability_inborn');
    }
}
