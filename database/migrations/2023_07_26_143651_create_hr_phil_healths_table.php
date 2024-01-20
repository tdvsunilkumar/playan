<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrPhilHealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_phil_healths', function (Blueprint $table) {
            $table->id();
            $table->string('hrpt_description')->comment('Description');
            $table->float('hrpt_amount_from',8,2)->comment('From');
            $table->float('hrpt_amount_to',8,2)->comment('To');
            $table->integer('hrpt_percentage')->comment('Percentage');
            $table->integer('is_active')->default(0);
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
        Schema::dropIfExists('hr_phil_healths');
    }
}
