<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBacRfqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bac_rfqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('control_no', 40);
            $table->string('project_name', 255);
            $table->string('requesting_agency', 255);
            $table->double('total_budget')->nullable();
            $table->double('no_of_days_submitted')->nullable();
            $table->text('remarks')->nullable();
            $table->date('deadline_date')->nullable();
            $table->date('quotation_date')->nullable();
            $table->text('delivery_period')->nullable();
            $table->integer('warranty_exp_id')->unsigned()->comment('bac_expendable_warranties');
            $table->integer('warranty_non_exp_id')->unsigned()->comment('bac_non_expendable_warranties');
            $table->integer('price_validaty_id')->unsigned()->comment('bac_price_validities');
            $table->boolean('is_implemented')->default(1);
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
        Schema::dropIfExists('bac_rfqs');
    }
}
