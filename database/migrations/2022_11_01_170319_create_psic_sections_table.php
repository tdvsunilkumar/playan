<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsicSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psic_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_code');
            $table->text('section_description')->nullable();
            $table->integer('section_status')->default('0');
            $table->integer('section_generated_by')->default('0');
            $table->datetime('section_generated_date')->nullable();
            $table->integer('section_modified_by')->default('0');
            $table->datetime('section_modified_date')->nullable();
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
        //Schema::dropIfExists('psic_sections');
    }
}
