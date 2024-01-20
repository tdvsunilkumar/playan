<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoMedicalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_medical_records', function (Blueprint $table) {
            $table->id();
            $table->integer('med_rec_id');
            $table->integer('rec_card_id')->comment('ref-table:ho_record_card. rec_card_id');
            $table->integer('hp_code')->comment('ref-table:hr_profile. hp_code. Get Doctor/Nurse Name');
            $table->string('med_rec_nurse_note',100)->default(0);
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
        Schema::dropIfExists('ho_medical_records');
    }
}
