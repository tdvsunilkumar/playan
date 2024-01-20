<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        
        Schema::create('bfp_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('btype_id')->comment('Ref-Table: bfp_application_type.id');
            $table->integer('bap_id')->comment('Ref-Table: bfp_application_purpose.id');
            $table->integer('bac_id')->comment('Ref-Table: bfp_application_category.id');
            $table->integer('req_id')->comment('Ref-Table: requirements WHERE req_dept_bfp=1');
            $table->integer('bac_status')->comment('0=InActive,1=Active');
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
        Schema::dropIfExists('bfp_requirements');
    }
}
