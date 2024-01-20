<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('hrh_date')->comment('date');
            $table->string('hrh_description')->comment('Description');
            $table->integer('hrht_id')->comment('ref-Table: hr_holiday_types.hrht_id');
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
        Schema::dropIfExists('hr_holidays');
    }
}
