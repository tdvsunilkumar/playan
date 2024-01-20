<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfarePwdCauseOfDisabilityAcquire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_pwd_cause_of_disability_acquire', function (Blueprint $table) {
            $table->id();
			$table->string('wpcoda_description');
            $table->integer('wpcoda_is_active');
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
        Schema::dropIfExists('welfare_pwd_cause_of_disability_acquire');
    }
}
