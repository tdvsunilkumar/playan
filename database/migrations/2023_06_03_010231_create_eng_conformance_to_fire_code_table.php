<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngConformanceToFireCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_conformance_to_fire_code', function (Blueprint $table) {
            $table->id();
			$table->string('ectfc_description')->length(30);
            $table->integer('ectfc_is_active')->length(1)->default('0');
            $table->integer('ectfc_created_by')->length(14)->default('0');
            $table->integer('ectfc_modified_by')->length(14)->default('0');
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
        Schema::dropIfExists('eng_conformance_to_fire_code');
    }
}
