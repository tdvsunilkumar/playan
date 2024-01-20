<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptCtoBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_cto_billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rp_property_code');
            $table->unsignedBigInteger('rp_code');
            $table->string('pk_code',1);
            $table->unsignedBigInteger('rvy_revision_year');
            $table->string('rvy_revision_code',10);
            $table->unsignedBigInteger('brgy_code');
            $table->string('brgy_no',5);
            $table->integer('rp_td_no')->nullable();
            $table->string('rp_suffix',5);
            $table->string('rp_tax_declaration_no',20)->nullable();
            $table->integer('cb_billing_mode')->comment('0=for Single Property Billing,1=for Multiplie Property Billing');
            $table->integer('cb_control_year');
            $table->integer('cb_control_no');
            $table->unsignedBigInteger('rpo_code');
            $table->integer('cb_covered_from_year');
            $table->integer('cb_covered_to_year');
            $table->decimal('cb_assessed_value',20,3);
            $table->date('cb_billing_date');
            $table->tinyInteger('cb_penalty_is_waived')->comment('Waived Penalty');
            $table->integer('cb_is_paid')->comment('0=Not Paid,1=Paid');
            $table->integer('cb_all_quarter_paid')->comment('0=if not all quarter has not beed paid, 1=if all quarter has beed paid');
            $table->text('cb_billing_notes')->nullable();
            $table->unsignedBigInteger('cb_certified_by');
            $table->string('cb_certified_by_position',30);
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
        Schema::dropIfExists('rpt_cto_billings');
    }
}
