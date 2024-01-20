<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoApplicationSanitaryReqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_application_sanitary_req', function (Blueprint $table) {
            $table->id();
            $table->integer('has_id')->comment('foreign details ho_application_sanitary.has_code');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->nullable();
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id')->nullable();
            $table->integer('req_id')->comment('Ref-Table: requirements.id WHERE req_dept_health_office=1');
            $table->string('hasr_document')->comment('document');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ho_application_sanitary_req');
    }
}
