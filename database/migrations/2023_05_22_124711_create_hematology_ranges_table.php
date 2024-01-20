<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHematologyRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hematology_ranges', function (Blueprint $table) {
            $table->id();
            $table->integer('chc_id')->length(14)->default('0');
            $table->integer('chp_id')->length(14)->default('0');
            $table->text('chr_range');
            $table->integer('hr_is_active')->default(1);
            $table->integer('created_by')->length(14)->default('0');
            $table->integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('hematology_ranges');
    }
}
