<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrOffsetHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_offset_hours', function (Blueprint $table) {
            $table->id();
            $table->integer('hr_employeesid')->comment('ref-Table: hr_employees.id');
            $table->integer('hroh_total_offset_hours')->comment('Total Offset');
            $table->integer('hroh_used_offset_hours')->comment('Total Used');
            $table->integer('hroh_balance_offset_hours')->comment('Total Balance Offset');
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
        Schema::dropIfExists('hr_offset_hours');
    }
}
