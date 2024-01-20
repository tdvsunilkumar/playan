<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpFeesMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bfp_fees_masters', function (Blueprint $table) {
            $table->id();
            $table->string('fmaster_code',200)->comment('Account Code');
            $table->string('fmaster_description',200)->comment('Account Code');
            $table->text('fmaster_subdetails_json')->comment('Description');
            $table->integer('fmaster_status')->comment('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('bfp_fees_masters');
    }
}
