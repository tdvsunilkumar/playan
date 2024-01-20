<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnroBploInspectionReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enro_bplo_inspection_report', function (Blueprint $table) {
            $table->id();
			$table->integer('busn_id')->length(11)->comment('Ref-Table: bplo_business.id');
            $table->integer('bend_id')->length(11)->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('ebir_year')->length(11)->comment('Current Year');
            $table->integer('ebir_no')->comment('Incremental Number. Reset Every year');
            $table->integer('ebir_control_no')->length(20)->comment('Combination of (ebir_year - ebir_no) WHERE ebir_no=incremental value');
			$table->date('ebir_date');
			$table->date('ebir_inspection_date');
            $table->string('ebir_recommendation')->length(200);
			$table->integer('ebir_inspected_by')->length(14)->comment('Ref-Table: hr_employees.id');
            $table->date('ebir_inspected_date');
			$table->integer('ebir_inspected_status')->length(1);
			$table->string('ebir_inspector_position')->length(70);
            $table->integer('ebir_approved_by')->length(14)->comment('Ref-Table: hr_employees.id');
            $table->date('ebir_approved_date');
            $table->integer('ebir_approved_status')->length(1)->comment('Approver Position');
			$table->string('ebir_approver_position')->length(70)->comment('Approver Position');
			$table->integer('ebir_status')->length(1)->comment('0=InActive, 1=Active');
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
        Schema::dropIfExists('enro_bplo_inspection_report');
    }
}
