<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrAppointmentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_appointment_status', function (Blueprint $table) {
            $table->id();
            $table->string('hras_description');
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->length(11);
            $table->integer('updated_by')->nullable()->length(11);
            $table->timestamps(); // This adds `created_at` and `updated_at` columns automatically
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_appointment_status');
    }
}
