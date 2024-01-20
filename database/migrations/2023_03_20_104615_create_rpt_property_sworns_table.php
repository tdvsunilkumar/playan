<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertySwornsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_property_sworns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_code')->nullable();
            $table->string('pk_code',1)->nullable();
            $table->decimal('rps_improvement_value',20,2)->nullable();
            $table->string('rps_person_taking_oath_custom',50)->nullable();
            $table->unsignedBigInteger('rps_person_taking_oath_code')->nullable();
            $table->date('rps_date')->nullable();
            $table->string('rps_ctc_no',20)->nullable();
            $table->date('rps_ctc_issued_date')->nullable();
            $table->string('rps_ctc_issued_place',30)->nullable();
            $table->string('rps_administer_official1',75)->nullable();
            $table->string('rps_administer_official_title1',50)->nullable();
            $table->string('rps_administer_official2',75)->nullable();
            $table->string('rps_administer_official_title2',50)->nullable();
            $table->integer('rps_registered_by');
            $table->integer('rps_modified_by');
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
        Schema::dropIfExists('rpt_property_sworns');
    }
}
