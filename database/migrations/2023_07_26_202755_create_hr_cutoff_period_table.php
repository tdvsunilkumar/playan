<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrCutoffPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_cutoff_period', function (Blueprint $table) {
            $table->id();
			$table->string('hrcp_description')->comment('hrcp_description');
			$table->date('hrcp_date_from');
			$table->date('hrcp_date_to');
			$table->integer('hrcp_status')->default('0');
			$table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('hr_cutoff_period');
    }
}
