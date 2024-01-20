<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        
        Schema::create('bfp_application_type', function (Blueprint $table) {
            $table->id();
            $table->string('btype_name',50);
            $table->string('btype_description',200);
            $table->integer('btype_status')->comment('0=InActive,1=Active');
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
        Schema::dropIfExists('bfp_application_type');
    }
}
