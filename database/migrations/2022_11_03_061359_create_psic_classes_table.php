<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsicClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psic_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->integer('division_id');
            $table->integer('group_id');
            $table->string('class_code');
            $table->text('class_description')->nullable();
            $table->integer('is_active')->default('0');
            $table->integer('generated_by')->default('0');
            $table->datetime('generated_date')->nullable();
            $table->integer('modified_by')->default('0');
            $table->datetime('modified_date')->nullable();
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
        Schema::dropIfExists('psic_classes');
    }
}
