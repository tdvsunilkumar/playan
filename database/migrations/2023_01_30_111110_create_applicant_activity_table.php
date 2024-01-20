<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_activity', function (Blueprint $table) {
            $table->id();
            $table->integer('applicantid')->comment('foreign key allaplicants reference');
            $table->integer('natureofbussiness')->comment('Nature Of Business Relation id');
            $table->string('noofunits')->comment('No of Units');
            $table->string('capitalization')->comment('capitalization');
            $table->string('essestial')->comment('Essestial');
            $table->string('nonessestial')->comment('Non Essestial');
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
        Schema::dropIfExists('applicant_activity');
    }
}
