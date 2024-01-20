<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertyAnnotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_annotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code')->nullable();
            $table->string('pk_code',1)->nullable();
            $table->date('rpa_annotation_date_time')->nullable();
            $table->integer('rpa_annotation_by_code')->nullable();
            $table->text('rpa_annotation_desc')->nullable();
            $table->integer('rpa_registered_by');
            $table->integer('rpa_modified_by');
            $table->timestamps();

            $table->foreign('rp_code')
              ->references('id')->on('rpt_properties')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpt_property_annotations');
    }
}
