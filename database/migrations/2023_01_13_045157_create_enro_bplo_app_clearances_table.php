<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnroBploAppClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enro_bplo_app_clearances', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->length(11)->comment('Ref-Table: bplo_business.id');
			$table->integer('bend_id')->length(11)->comment('Ref-Table:bplo_business_endorsement.id');
			$table->date('ebac_date');
            $table->string('ebac_app_code')->length(5)->comment('can be [ENRO]');
			$table->integer('ebac_app_year')->length(4)->comment('like year 2022');
            $table->integer('ebac_app_no')->length(11)->comment('xxxxxx application number representing incremental values from the system 1. written into 000001');
			$table->date('ebac_issuance_date')->comment('Format: [yyyy-mm-dd]. Can be custom date by the user');
			$table->string('ebac_remarks')->length(200)->comment('Remarks or Additional Instruction');
            $table->integer('ebac_status')->length(1)->comment('1=Closed, 0=Open');
            $table->integer('ebac_approved_by')->length(14)->comment('foreign key profile.p_code who generate the group details');
			$table->integer('ebac_approved_by_status')->length(1)->comment('When teh approver approve the clearance');
            $table->string('ebac_approver_position')->length(70)->comment('Apporver Position');
			$table->date('ebac_approved_date');
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
        Schema::dropIfExists('enro_bplo_app_clearances');
    }
}
