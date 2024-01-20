<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoDepartmentalRequestsDisapprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_departmental_requests_disapprovals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('departmental_request_id')->unsigned();
            $table->string('disapproved_from', 40)->nullable();
            $table->timestamp('disapproved_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('disapproved_by')->unsigned();
            $table->text('disapproved_remarks')->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gso_departmental_requests_disapprovals');
    }
}
