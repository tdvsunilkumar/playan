<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrGsisTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_gsis_table', function (Blueprint $table) {
            $table->id();
			$table->string('hrgt_description')->comment('hrgt_description');
			$table->float('hrgt_amount_from', 8, 2);
			$table->float('hrgt_amount_to', 8, 2);
			$table->integer('hrgt_percentage');
			$table->integer('hrgt_status')->default('0');
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
        Schema::dropIfExists('hr_gsis_table');
    }
}
