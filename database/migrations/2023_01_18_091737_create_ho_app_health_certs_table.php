<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoAppHealthCertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ho_app_health_certs', function (Blueprint $table) {
            $table->id();
            $table->integer('brgy_id')->comment('foreign key barangay.brgy_code of the business');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id');
            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id)');

            $table->string('hahc_app_code')->default('CHO')->nullable()->comment('can be [CHO] which mean Health Office Health Certificate');
            $table->integer('hahc_app_year')->comment('like year 2022');
            $table->string('hahc_app_no')->comment('xxxxxx application number representing incremental values from the system 1. written into 000001');
            $table->string('hahc_transaction_no')->comment('Combination of  [hahc_app_code + hahc_app_year + hahc_app_no] can be in the format [HOHCERT-2022-000001]');
            $table->integer('hahc_registration_no')->nullable()->comment('Registration No. Rest every year');
            
            $table->date('hahc_issuance_date')->comment('Issuance Date');
            $table->date('hahc_expired_date')->comment('Expiration Date');
            $table->integer('citizen_id')->comment('Ref-Table: citizens.id');
            $table->string('employee_occupation');
            $table->string('hahc_place_of_work')->comment('Address of the Workplace');
            $table->integer('hahc_status')->default(0)->nullable()->comment('Status Release of the Health Certificate');
            $table->integer('hahc_recommending_approver')->nullable()->comment('foreign key profile.p_code recommending approval');
            $table->string('hahc_recommending_approver_position')->nullable()->comment('Position');
            $table->boolean('hahc_recommending_approver_status')->default(0)->nullable();
            $table->integer('hahc_approver')->nullable()->comment('foreign key profile.p_code approver');
            $table->string('hahc_approver_position')->nullable()->comment('Position');
            $table->boolean('hahc_approver_status')->nullable()->default('0');
            $table->text('hahc_document_json')->nullable()->comment('document');
            $table->string('hahc_remarks')->nullable()->comment('Remarks or Additional Instruction');
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
        Schema::dropIfExists('ho_app_health_certs');
    }
}
