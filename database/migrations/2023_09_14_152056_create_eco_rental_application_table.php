<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoRentalApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_rental_application', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('top_transaction_type_id')->unsigned()->comment('cto_top_transaction_type');
            $table->integer('tfoc_id')->unsigned()->comment('cto_tfocs');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->integer('sl_account_id')->unsigned()->comment('acctg_account_subsidiary_ledgers');
            $table->integer('requestor_id')->unsigned()->comment('citizens');
            $table->string('transaction_no', 40);
            $table->date('transaction_date');
            $table->text('particulars')->nullable();
            $table->text('business_employer_name')->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->text('full_address')->nullable();
            $table->dateTime('event_start');
            $table->dateTime('event_end');
            $table->integer('service_id')->unsigned()->comment('eco_services');
            $table->integer('location_id')->unsigned()->comment('econ_data_receptions.brgy_id');
            $table->integer('reception_id')->unsigned()->comment('econ_data_receptions.id');
            $table->integer('reception_class_id')->unsigned()->comment('eco_receptions_lists_details.eatd_process_type');
            $table->string('reception_class_text', 20)->nullable();
            $table->double('reception_class_value')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('or_no', 20)->nullable();
            $table->date('or_date')->nullable();
            $table->boolean('is_free')->default(0);
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
        Schema::dropIfExists('eco_rental_application');
    }
}
