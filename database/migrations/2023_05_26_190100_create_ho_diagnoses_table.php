<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->string('diag_name',100)->default(0);
            $table->string('icd10_details',50);
            $table->string('diag_remarks',255);
            $table->integer('hd_is_active')->default(1);
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
        Schema::dropIfExists('ho_diagnoses');
    }
}
