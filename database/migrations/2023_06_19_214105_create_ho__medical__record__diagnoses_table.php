<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoMedicalRecordDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_medical_record_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->integer('med_rec_id')->comment('table:ho_medical_record. med_rec_id');
            $table->integer('disease_id')->comment('ref-table:ho_diseases.id');
            $table->string('is_specified',100);
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
        Schema::dropIfExists('ho_medical_record_diagnoses');
    }
}
