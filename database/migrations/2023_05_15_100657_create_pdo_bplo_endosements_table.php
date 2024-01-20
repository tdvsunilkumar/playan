<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdoBploEndosementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdo_bplo_endosements', function (Blueprint $table) {
            $table->id();
            $table->integer('pend_year')->length(4)->comment('Year');
            $table->date('pend_date')->comment('Date');
            $table->integer('client_id')->length(11)->comment('Ref table clients.id');
            $table->integer('busn_id')->length(11)->comment('Ref-Table: bplo_business.id');
            $table->integer('bend_id')->length(11)->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('pend_status')->length(1)->default(1);
            $table->text('pend_remarks')->length(200)->comment('Remarks or Additional Instructio');
            $table->integer('pend_approved_by')->length(11)->comment('Ref-Table: hr_employees.id');
            $table->integer('pend_approved_status')->length(1)->default(0);
            $table->string('pend_officer_position')->length(70)->comment('Officer Position');
            $table->integer('created_by')->length(14)->default('0');
            $table->integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('pdo_bplo_endosements');
    }
}
