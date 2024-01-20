<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEngConsultantExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eng_consultant_externals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ept_id')->comment('ref-Table : eng_profession_type.ept_id');
            $table->unsignedBigInteger('esp_id')->comment('ref-Table : eng_sub_profession.esp_id');
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('fullname');
            $table->string('suffix',15)->nullable();
            $table->string('title',75)->nullable();
            $table->string('gender',50);
            $table->date('birthdate');
            $table->string('house_lot_no');
            $table->string('street_name');
            $table->string('subdivision');
            $table->string('brgy_code');
            $table->string('country');
            $table->string('email_address');
            $table->string('telephone_no');
            $table->string('mobile_no');
            $table->string('ptr_no');
            $table->date('ptr_date_issued');
            $table->string('prc_no');
            $table->date('prc_validity');
            $table->date('prc_date_issued');
            $table->string('tin_no');
            $table->string('iapoa_no');
            $table->string('iapoa_or_no');
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
			$table->integer('is_active')->length(1);
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
        Schema::dropIfExists('eng_consultant_externals');
    }
}
