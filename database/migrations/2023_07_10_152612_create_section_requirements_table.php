<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psic_section_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id')->default('0')->comment('Ref-Table: psic_section.id');
            $table->integer('apptype_id')->default('0')->comment('1-New, 2-Renew, 3-Retire');
            $table->text('requirement_json')->nullable()->comment('Requirement relation json');
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
        Schema::dropIfExists('psic_section_requirements');
    }
}
