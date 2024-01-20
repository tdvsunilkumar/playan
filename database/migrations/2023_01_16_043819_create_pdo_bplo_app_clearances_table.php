<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdoBploAppClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdo_bplo_app_clearances', function (Blueprint $table) {
            $table->id();
            $table->integer('p_code')->comment('foreign key profile.p_code');
            $table->integer('brgy_code')->comment('foreign key barangay.brgy_code');
            $table->integer('ba_code')->comment('foreign key bplo_application.ba_code reference');
            $table->integer('ba_business_account_no')->comment('foreign details bplo_application.ba_business_account_no. BAN. Business Account Number');
            $table->string('pbac_app_code')->comment('can be [PDO]');
            $table->integer('pbac_app_year')->comment('like year 2022');
            $table->integer('pbac_app_no')->comment('xxxxxx application number representing incremental values from the system 1. written into 000001');
            $table->string('pbac_transaction_no')->comment('Combination of  [pbac_app_code + pbac_app_year + pbac_app_no] can be in the format [PDO-2022-000001]');
            $table->integer('pbac_zoning_clearance_fee')->comment('Environmental Fee');
            $table->integer('pbac_is_paid')->comment('when make payment in CTO');
            $table->date('pbac_issuance_date')->comment('Issuance Date');
            $table->string('pbac_remarks')->comment('Remarks or Additional Instruction');
            $table->integer('pbac_status')->comment('Whether the locational clearance already release to the client');
            $table->integer('pbac_officer_code')->comment('Zoning Officer foreign key profile.p_code zoning officer');
            $table->string('pbac_officer_position')->comment('Officer Position');
            $table->integer('pbac_approved_by')->comment('Approver of the Clearance foreign key profile.p_code who approved the zonig, if necessary');
            $table->string('pbac_approver_position');
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
        Schema::dropIfExists('pdo_bplo_app_clearances');
    }
}
