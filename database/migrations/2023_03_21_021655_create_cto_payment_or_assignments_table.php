<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentOrAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_or_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('ortype_id')->comment('cto_payment_or_type.ortype_id');
            $table->integer('ora_from');
            $table->integer('ora_to');
            $table->integer('ora_is_completed')->length(1)->default('0');
            $table->datetime('ora_completed_date')->nullable();
            $table->text('ora_remarks')->nullable();
            $table->integer('ora_is_active')->length(1)->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
            $table->integer('latestusedor')->nullable();
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
        Schema::dropIfExists('cto_payment_or_assignments');
    }
}
