<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBploBusinessRetirementIssuanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_retirement_issuance', function (Blueprint $table) {
            $table->id();
            $table->Integer('busn_id')->length(11)->default('0')->comment('Ref-Table:bplo_business.id');
            $table->Integer('retire_id')->length(11)->default('0')->comment('Ref-Table:bplo_business_retirement.id');
            $table->Integer('bri_year')->length(4)->comment('current year');
            $table->string('bri_month')->length(2)->default('0')->comment('Covered Month');
            $table->Integer('bri_no')->default('0')->length(11)->comment('Incremental Value of the Business Permit Number based on the current year');
            $table->date('retire_date_closed')->comment('Date Closed');
            $table->date('bri_issued_date')->comment('Issued Date');
            $table->text('bri_remarks')->comment('Remarks')->nullable();
            $table->text('bri_upload_documents_json')->comment('permit document')->nullable();
            $table->integer('bri_issued_by')->default(0)->comment('Signed Business Permit');
            $table->string('bri_issued_position')->comment('Position');
            $table->integer('client_id')->default(0);
            $table->integer('pm_id')->default(0);
            $table->integer('status')->default(0)->comment('Position');
            $table->Integer('created_by')->default('0')->length(11);
            $table->Integer('updated_by')->default('0')->length(11);
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
        Schema::dropIfExists('bplo_business_retirement_issuance');
    }
}
