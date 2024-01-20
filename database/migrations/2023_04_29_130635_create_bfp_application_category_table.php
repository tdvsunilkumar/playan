<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        
        
        Schema::create('bfp_application_category', function (Blueprint $table) {
            $table->id();
            $table->integer('btype_id')->comment('Ref-Table: bfp_application_type.id');
            $table->integer('bap_id')->comment('Ref-Table: bfp_application_purpose.id');
            $table->string('bac_desc',50);
            // $table->integer('app_type_id')->comment('Ref-Table: bplo_application_type.id');
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
        Schema::dropIfExists('bfp_application_category');
    }
}
