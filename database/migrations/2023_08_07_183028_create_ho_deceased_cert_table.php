<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoDeceasedCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_deceased_cert', function (Blueprint $table) {
            $table->id();
            $table->integer('requester_id')->length(11)->comment('citizens.id get fullname');
            $table->integer('brgy_id')->length(11)->comment('citizens.id get fullname');
            $table->integer('relation_type')->length(11);
            $table->integer('form_type')->length(11);
            $table->datetime('issue_date');
            $table->integer('health_officer_id')->length(11)->comment('hr_employees.id. get fullname');
            $table->string('health_officer_position')->length(100)->comment('hr_employees.hr_designation_id, get hr_designation.description');
            $table->integer('is_approved')->length(11)->comment('0 = Pending, 1 - Approved');
            $table->integer('deceased_id')->length(11)->comment('citizens.id get fullname');
            $table->integer('place_of_death_id')->length(11)->comment('barangays.id get brgy_name, mun_desc, prov_desc');
            $table->datetime('death_date');
            $table->string('transfer_location')->length(255);
            $table->integer('transfer_add_id')->length(11)->comment('barangays.id get brgy_name, mun_desc');
            $table->integer('cashierd_id')->length(11)->comment('cto_cashier_details.id');
            $table->integer('cashier_id')->length(11)->comment('cto_cashier.id');
            $table->string('or_no')->length(100)->comment('cto_cashier.or_no');
            $table->date('or_date')->comment('cto_cashier.cashier_or_date');
            $table->double('or_amount')->length(8,3)->comment('Ref-Table: cto_cashier_details.tfc_amount');
            $table->integer('is_free')->length(1)->comment('0 = not free, 1 = free');
            $table->integer('status')->length(1)->comment('0 = Inactive, 1 = Active');
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
        Schema::dropIfExists('ho_deceased_cert');
    }
}
