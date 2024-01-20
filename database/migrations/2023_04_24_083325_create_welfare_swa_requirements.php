<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareSwaRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_swa_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('wsr_description');
            $table->integer('wsr_is_active')->length(1)->comment('if the status of the application type is active or not');
            $table->integer('created_by')->unsigned()->comment('reference profile.reg_code of the system who create the application type');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Format: [yyyy-mm-dd hh:mm:ss]. ');
            $table->integer('modified_by')->unsigned()->nullable()->comment('reference profile.reg_code of the system who modified  the application type');
            $table->timestamp('modified_date')->nullable()->comment('Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_swa_requirements');
    }
}
