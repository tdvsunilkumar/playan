<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoMedicalCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_medical_certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id');
            $table->integer('cit_age');
            $table->integer('cashierd_id');
            $table->integer('cashier_id');
            $table->string('or_no');
            $table->date('or_date');
            $table->decimal('or_amount', 10, 2);
            $table->integer('med_cert_is_free');
            $table->integer('med_officer_id');
            $table->string('med_officer_position');
            $table->tinyInteger('med_officer_approved_status');
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
        Schema::dropIfExists('ho_medical_certificates');
    }
}
