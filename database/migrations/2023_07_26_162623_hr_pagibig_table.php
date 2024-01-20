<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrPagibigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_pagibig_table', function (Blueprint $table) {
            $table->id();
            $table->string('hrpit_description');
            $table->double('hrpit_amount_from', 10, 2);
            $table->double('hrpit_amount_to', 10, 2);
            $table->double('hrpit_percentage', 10, 2);
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
        Schema::dropIfExists('hr_pagibig_table');
    }
}
