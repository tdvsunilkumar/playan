<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fund_code_id')->unsigned()->comment('acctg_fund_codes');
            $table->integer('officer_id')->unsigned()->comment('users');
            $table->string('trans_no', 40);
            $table->date('trans_date')->nullable();
            $table->double('total_amount')->nullable();
            $table->text('data_collections')->nullable();
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
        Schema::dropIfExists('cto_collections');
    }
}
