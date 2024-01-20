<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoWaterPotabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_water_potabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->comment('bplo_business.cit_id');
            $table->integer('brgy_id')->comment('barangays.id');
            $table->string('certificate_no', 11);
            $table->integer('cashierd_id')->comment('cto_cashier_details.id');
            $table->integer('cashier_id')->comment('cto_cashier.id');
            $table->string('or_no', 100)->comment('cto_cashier.or_no');
            $table->date('or_date')->comment('cto_cashier.cashier_or_date');
            $table->double('or_amount')->comment('cto_cashier_details.tfc_amount');
            $table->date('date_start');
            $table->date('date_end');
            $table->date('date_issued');
            $table->integer('inspected_by')->comment('hr_employee.id');
            $table->string('inspector_position',200);
            $table->integer('approved_by')->comment('hr_employee.id');
            $table->string('approver_position',200);
            $table->integer('is_approved')->comment('0 = Pending, 1 = Approved');
            $table->integer('is_free');
            $table->integer('status');
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
        Schema::dropIfExists('ho_water_potabilities');
    }
}
