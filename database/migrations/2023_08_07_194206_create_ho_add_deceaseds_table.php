<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoAddDeceasedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_add_deceaseds', function (Blueprint $table) {
            $table->id();
            $table->integer('deceased_cert_id')->length(11)->comment('ref-table: ho_deceased_cert.id');
            $table->integer('cit_id')->length(11)->comment('citizens.id get fullname');
            $table->datetime('death_date');
            $table->integer('status')->length(1)->comment('0 = Inactive, 1 = Active');
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
        Schema::dropIfExists('ho_add_deceaseds');
    }
}
