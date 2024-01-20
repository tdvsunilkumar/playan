<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateRptPropertyOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('rpo_custom_last_name',90);
            $table->string('rpo_first_name',90);
            $table->string('rpo_middle_name',90)->nullable();
            $table->integer('p_code')->comment('foreign key profile.p_code.In case the user decided to selected the standard name');
            $table->string('rpo_address_house_lot_no',50);
            $table->string('rpo_address_street_name',50);
            $table->string('rpo_address_subdivision',50);
            $table->string('brgy_code',20)->comment('foreign key barangay.brgy_code')->nullable();
            $table->string('p_barangay_id_no',20)->nullable();
            $table->string('p_telephone_no',20)->nullable();
            $table->string('p_mobile_no',20)->nullable();
            $table->string('p_fax_no',20)->nullable();
            $table->string('p_tin_no',20)->nullable();
            $table->string('p_email_address',20)->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('clients');
    }
}
