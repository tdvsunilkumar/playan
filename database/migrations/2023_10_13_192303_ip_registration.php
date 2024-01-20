<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IpRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ip_registration', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('local_name')->comment('Ref-Table: acctg_departments.id');
            $table->text('remarks')->comment('Remarks');
            $table->integer('status')->default('1');
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
        Schema::dropIfExists('ip_registration');
    }
}
