<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DivisionPsic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('psic_divisions', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->string('division_code')->nullable();
            $table->text('division_description')->nullable();
            $table->integer('division_status')->default('0');
            $table->integer('division_generated_by')->default('0');
            $table->datetime('division_generated_date')->nullable();
            $table->integer('division_modified_by')->default('0');
            $table->datetime('division_modified_date')->nullable();
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
        Schema::dropIfExists('psic_divisions');
    }
}
