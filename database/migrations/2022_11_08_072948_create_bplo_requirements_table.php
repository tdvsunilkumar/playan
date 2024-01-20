<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->integer('division_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('subclass_id')->nullable();
            $table->integer('req_id')->nullable();
            $table->integer('apptype_id')->nullable();
            $table->string('req_code_abbreviation')->nullable();
            $table->string('req_description')->nullable();
            $table->string('br_remarks')->nullable();
            $table->integer('is_active')->default('0');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('bplo_requirements');
    }
}
