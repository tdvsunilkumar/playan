<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoCemeteryApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_cemetery_application', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('top_transaction_type_id')->unsigned()->comment('cto_top_transaction_type');
            $table->integer('tfoc_id')->unsigned()->comment('cto_tfocs');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->integer('sl_account_id')->unsigned()->comment('acctg_account_subsidiary_ledgers');
            $table->integer('requestor_id')->unsigned()->comment('citizens');
            $table->integer('expired_id')->unsigned()->comment('citizens');
            $table->string('transaction_no', 40);
            $table->date('transaction_date');
            $table->string('contact_no', 20)->nullable();
            $table->text('full_address')->nullable();
            $table->integer('location_id')->unsigned()->comment('eco_data_cemeteries.brgy_id');
            $table->integer('cemetery_id')->unsigned()->comment('eco_data_cemeteries.id');
            $table->integer('cemetery_style_id')->unsigned()->comment('eco_cemeteries_style');
            $table->integer('cemetery_lot_id')->unsigned()->comment('eco_cemeteries_list_details');
            $table->integer('service_id')->unsigned()->comment('eco_services');
            $table->double('total_amount')->nullable();
            $table->string('or_no', 20)->nullable();
            $table->date('or_date')->nullable();
            $table->string('status', 40)->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->integer('sent_by')->unsigned()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('approved_by')->unsigned()->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->integer('disapproved_by')->unsigned()->nullable();
            $table->text('disapproved_remarks')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eco_cemetery_application');
    }
}
