<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfpApplicationRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('bfp_application_requirements', function (Blueprint $table) {
            $table->id();
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id');
            $table->integer('client_id')->comment('Ref-Table: clients.id');
            $table->integer('bff_id')->comment('Ref-Table: bfp_application_form');
            $table->string('bfr_document_file',250);
            $table->integer('req_id')->comment('Ref-Table: requirements WHERE req_dept_bfp=1');
            $table->integer('bfr_status')->comment('0=InActive,1=Active');
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
        Schema::dropIfExists('bfp_application_requirements');
    }
}
