<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationPurposeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        
        
        Schema::create('bfp_application_purpose', function (Blueprint $table) {
            $table->id();
            $table->integer('btype_id')->comment('Ref-Table: bfp_application_type.id');
            $table->string('bap_desc',50);
            $table->integer('bap_status')->comment('0=InActive,1=Active');
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
        Schema::dropIfExists('bfp_application_purpose');
    }
}
