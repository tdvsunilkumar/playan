<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoApplicationAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_application_assessments', function (Blueprint $table) {
            $table->id();
            $table->integer('bplo_assessment_id');
            $table->string('bussiness_application_code')->length(30)->nullable();
            $table->integer('bussiness_application_id');
            $table->text('bussiness_application_desc')->nullable();

            $table->string('tax_type_code')->length(30)->nullable();
            $table->integer('tax_type_id');
            $table->text('tax_type_desc')->nullable();


            $table->string('classification_code')->length(30)->nullable();
            $table->integer('classification_id');
            $table->text('classification_desc')->nullable();

            $table->string('activity_code')->length(30)->nullable();
            $table->integer('activity_id');
            $table->text('activity_desc')->nullable();

            $table->integer('essential_commodities')->length(1)->default(0);
            $table->integer('no_of_perdays');

            
            $table->text('mayrol_permit_description')->nullable();
            $table->double('permit_amount', 8, 2);
            $table->double('final_permit_amount', 8, 2);
            $table->string('mayrol_permit_code')->length(30)->nullable();

            $table->text('garbage_description')->nullable();
            $table->double('garbage_amount', 8, 2);
            $table->double('final_garbage_amount', 8, 2);
            $table->string('garbage_code')->length(30)->nullable();

            $table->text('sanitary_description')->nullable();
            $table->double('sanitary_amount', 8, 2);
            $table->double('final_sanitary_amount', 8, 2);
            $table->string('sanitary_code')->length(30)->nullable();

            $table->string('capitalization')->length(50)->nullable();
            $table->double('gross_sale', 8, 2);
        
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
        Schema::dropIfExists('cto_application_assessments');
    }
}
