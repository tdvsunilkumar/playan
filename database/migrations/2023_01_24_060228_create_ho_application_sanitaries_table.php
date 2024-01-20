<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoApplicationSanitariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_application_sanitaries', function (Blueprint $table) {
            $table->id();
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->nullable();
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id')->nullable();
            $table->integer('has_app_year')->comment('like year 2022');
            $table->string('has_app_no')->comment('xxxxxx application number representing incremental values from the system 1. written into 000001');
            $table->string('has_transaction_no')->comment('Combination of  [hahc_app_code + hahc_app_year + hahc_app_no] can be in the format [HOHCERT-2022-000001]');
            $table->string('has_type_of_establishment')->comment('User need to define this one. Every department has its own interpretation on the Line/Nature Of Business');
            $table->date('has_issuance_date')->comment('Issuance Date');

            $table->date('has_expired_date')->comment('Expiration Date');
            $table->string('has_remarks')->comment('Remarks or Additional Instruction');
            $table->integer('has_status')->nullable()->default('0')->comment('Status Release of the Health Certificate');
            $table->string('has_permit_no')->nullable()->comment('Sanitary Permit No.');
            $table->integer('has_recommending_approver')->nullable()->comment('foreign key profile.p_code recommending approval');
            $table->integer('has_recommending_approver_status')->nullable()->default('0');
            $table->string('has_recommending_approver_position')->nullable()->comment('Position');
            
            $table->integer('has_approver')->nullable()->comment('foreign key profile.p_code approver');
            $table->integer('has_approver_status')->nullable()->default('0');
            $table->string('has_approver_position')->nullable()->comment('Position');
            $table->datetime('has_approved_date')->nullable()->comment('foreign key profile.p_code approver');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ho_application_sanitaries');
    }
}
