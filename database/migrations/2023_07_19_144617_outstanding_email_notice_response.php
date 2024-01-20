<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OutstandingEmailNoticeResponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_outstanding_email_response', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->comment('Current Year');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->default(0);
            $table->integer('app_code')->comment('Application Type')->default(0);
            $table->integer('pm_id')->comment('Payment Mode')->default(0);
            $table->integer('is_read')->default('0')->comment('This flag will update from user through email');
            $table->datetime('acknowledged_date')->nullable()->comment('This date will update from user through email');
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
        Schema::dropIfExists('bplo_business_outstanding_email_response');
    }
}
