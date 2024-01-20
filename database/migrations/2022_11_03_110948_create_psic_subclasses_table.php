<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsicSubclassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psic_subclasses', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->integer('division_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->string('subclass_code')->nullable();
            $table->text('subclass_description')->nullable();
            $table->integer('subclass_status')->default('0');
            $table->integer('subclass_generated_by')->default('0');
            $table->datetime('subclass_generated_date')->nullable();
            $table->integer('subclass_modified_by')->default('0');
            $table->datetime('subclass_modified_date')->nullable();
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('psic_subclasses');
    }
}
