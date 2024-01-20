<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBussinessPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_bussiness_permits', function (Blueprint $table) {
            $table->id();
            $table->integer('ba_code')->comment('foreign key bplo_application.ba_code reference');
            $table->integer('bbp_year')->comment('current year');
            $table->integer('bbp_record_no')->comment('Auto-generated');
            $table->string('bbp_permit_no')->comment('Business/Mayors Permit number bbp_yearr + bbp_record_no');
            $table->string('bbp_remarks')->comment('in any ase remarks needed');
            $table->date('bbp_date_expired')->comment('Format: [yyyy-mm-dd].');
            $table->integer('bbp_approved_by')->comment('profile.p_code');
            $table->datetime('bbp_approved_date')->comment('Format: [yyyy-mm-dd hh:mm:ss].');
            $table->integer('bbp_issue_to_client')->comment('default = 0');
            $table->datetime('ba_registered_date')->comment('Format: [yyyy-mm-dd hh:mm:ss].');
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
        Schema::dropIfExists('bplo_bussiness_permits');
    }
}
