<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrTaxTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_tax_table', function (Blueprint $table) {
            $table->id();
			$table->string('hrtt_description')->comment('hrtt_description');
			$table->float('hrtt_amount_from', 8, 2);
			$table->float('hrtt_amount_to', 8, 2);
			$table->integer('hrtt_percentage');
			$table->integer('hrtt_status')->default('0');
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
        Schema::dropIfExists('hr_tax_table');
    }
}
