<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessPermitIssuanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('bplo_business_permit_issuance', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->length(11)->comment('Ref-Table:bplo_business.id');
            $table->integer('bpi_year')->length(4)->default('0');
            $table->integer('bpi_month')->length(2)->default('0');
            $table->integer('bpi_no')->length(11)->default('0');
            $table->string('bpi_permit_no')->length(14)->nullable();
            $table->integer('app_type_id')->length(1)->default('0')->comment('1-New, 2-Renew, 3-Retire');
            $table->integer('client_id')->length(11)->default('0');
            $table->integer('pm_id')->length(11)->default('0');
            $table->string('business_plate_no')->length(30)->nullable();
            $table->string('bpi_remarks')->length(150)->nullable();
            $table->string('bpi_upload_signed_permit')->length(150)->nullable();
            $table->datetime('bpi_issued_date');
            $table->date('bpi_date_expired');
            $table->integer('bpi_issued_by')->length(11)->nullable();
            $table->integer('bpi_issued_status')->length(11)->default('0');
            $table->string('bpi_issued_position')->length(100)->nullable();
            $table->integer('brgy_id')->length(11)->default('0')->comment('Ref-Table: bplo_business.busn_office_barangay_id');
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
        Schema::dropIfExists('bplo_business_permit_issuance');
    }
}
