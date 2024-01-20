<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('p_code');
            $table->string('p_first_name')->length(50)->nullable();
            $table->string('p_middle_name')->length(50)->nullable();
            $table->string('p_family_name')->length(50)->nullable();
            $table->string('p_complete_name_v1')->length(90)->nullable();
            $table->string('p_complete_name_v2')->length(90)->nullable();
            $table->string('p_address_house_lot_no')->length(50)->nullable();
            $table->string('p_address_street_name')->length(50)->nullable();
            $table->string('p_address_subdivision')->length(50)->nullable();
            $table->integer('brgy_code');  
            $table->string('brgy_name')->length(30)->nullable(); 
            $table->string('p_telephone_no')->length(22)->nullable(); 
            $table->string('p_mobile_no')->length(20)->nullable(); 
            $table->string('p_fax_no')->length(20)->nullable();
            $table->string('p_tin_no')->length(15)->nullable();
            $table->string('p_email_address')->length(50)->nullable();
            $table->string('p_job_position')->length(100)->nullable();
            $table->string('c_code')->length(50)->nullable();
            $table->string('p_gender')->length(10)->nullable();
            $table->date('p_date_of_birth');
            $table->integer('ba_code')->nullable();
            $table->string('ba_business_name')->length(150)->nullable();
            $table->string('p_place_of_work')->length(150)->nullable();
            $table->integer('p_registered_by');
            $table->datetime('p_registered_date');
            $table->integer('p_modified_by');
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
        Schema::dropIfExists('profiles');
    }
}
