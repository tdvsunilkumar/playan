<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareTravelClearanceMinor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_travel_clearance_minor', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id')->comment('ref-Table: citizens.cit_id');
            $table->date('wtcm_date_interviewed');
            $table->integer('wtcm_child_status')->comment('Legitimate, Illegitimate, Adopted/ Adoption Degree');
            $table->string('wtcm_background_info')->nullable();
            $table->string('wtcm_present_situation')->nullable();
            $table->string('wtcm_travel_purpose')->nullable();
            $table->string('wtcm_companion_name')->nullable();
            $table->date('wtcm_companion_date_of_birth')->nullable();
            $table->string('wtcm_relation_to_minor')->nullable();
            $table->string('wtcm_companion_address')->nullable();
            $table->string('wtcm_recommendation')->nullable();
            $table->integer('top_transaction_type_id')->comment('Ref-Table: cto_top_transaction_type.id');
            $table->string('transaction_no')->comment('Ref-Table: cto_top_transaction.transaction_no')->nullable();
            $table->integer('wts_id')->comment('Ref-Table: welfare_tcm_service.wts_id');
            $table->string('or_no')->comment('Ref-Table: cashier')->nullable();
            $table->string('wtcm_prepared_by')->comment('ref-Table: hr_employees.id');
            $table->string('wtcm_reviewed_by')->comment('ref-Table: hr_employees.id')->nullable();
            $table->string('wtcm_approved_by')->comment('ref-Table: hr_employees.id')->nullable();
            $table->integer('wtcm_is_active')->length(1)->comment('if the status of the application type is active or not');
            $table->integer('created_by')->unsigned()->comment('reference profile.reg_code of the system who create the application type');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Format: [yyyy-mm-dd hh:mm:ss]. ');
            $table->integer('updated_by')->unsigned()->nullable()->comment('reference profile.reg_code of the system who modified  the application type');
            $table->timestamp('updated_at')->nullable()->comment('Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_travel_clearance_minor');
    }
}
