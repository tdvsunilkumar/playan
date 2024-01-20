<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessPsicReq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_psic_req', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('busn_id');
            $table->integer('busn_psic_id')->default(0);
            $table->integer('subclass_id')->default(0);
            $table->string('attachment', 100);
            $table->integer('app_type_id')->default(0);
            $table->integer('busreq_year')->default(0);
            $table->integer('br_code')->default(0);
            $table->integer('req_code')->default(0);
            $table->integer('busreq_status')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_synced')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bplo_business_psic_req');
    }
}
