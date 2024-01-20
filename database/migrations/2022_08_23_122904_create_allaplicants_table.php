<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllaplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allaplicants', function (Blueprint $table) {
            $table->id();
            $table->integer('isnew')->default(0);
            $table->string('modeofpayment');
            $table->date('applicationdate');
            $table->longText('tinno');
            $table->longText('registartionno');
            $table->date('registrationdate');
            $table->longText('typeofbussiness');
            $table->longText('amendmentfrom');
            $table->longText('amendmentto');
            $table->integer('enjoyingtax');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->longText('bussinessname');
            $table->longText('tradename')->nullable();
            $table->string('bussinessaddress')->nullable();
            $table->string('billing_postalcode')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_telephone')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->string('owneraddress')->nullable();
            $table->string('owner_postalcode')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_telephone')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->longText('contactname')->nullable();
            $table->string('conactmobileno')->nullable();
            $table->string('contactemail')->nullable();
            $table->string('bussinessarea')->nullable();
            $table->integer('noofempestablish')->nullable();
            $table->integer('noofempewithlgu')->nullable()->default(0);
            $table->longText('lessor_fullname')->nullable();
            $table->longText('lessor_address')->nullable();
            $table->string('lessor_mobile')->nullable();
            $table->string('lessor_email')->nullable();
            $table->integer('monthlyrental')->nullable()->default(0);
            $table->string('lineofbussiness')->nullable();
            $table->string('noofunits')->nullable();
            $table->string('capitalization')->nullable();
            $table->string('essential')->nullable();
            $table->string('non_essential')->nullable();
            $table->integer('updated_by')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('is_approve')->default(0);
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
        Schema::dropIfExists('allaplicants');
    }
}
