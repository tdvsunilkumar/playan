<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_treatments', function (Blueprint $table) {
            $table->id();
            $table->integer('treat_id');
            $table->integer('med_rec_id')->comment('ref-table:ho_medical_record.med_rec_id')->default(0);
            $table->string('treat_medication',60)->default(0);
            $table->string('treat_management',70)->default(0);
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
        Schema::dropIfExists('ho_treatments');
    }
}
