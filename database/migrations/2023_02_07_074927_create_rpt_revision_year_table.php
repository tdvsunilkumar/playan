<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptRevisionYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_revision_year', function (Blueprint $table) {
            $table->id();
            $table->integer('rvy_revision_year');
            $table->string('rvy_revision_code');
            $table->unsignedBigInteger('rvy_city_assessor_assistant_code')->comment('foreign key profile.p_code');
            $table->unsignedBigInteger('rvy_city_assessor_code')->comment('foreign key profile.p_code');
            $table->integer('display_for_bplo')->nullable();
            $table->integer('display_for_rpt')->nullable();
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
        Schema::dropIfExists('rpt_revision_year');
    }
}
