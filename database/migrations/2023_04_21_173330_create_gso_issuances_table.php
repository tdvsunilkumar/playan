<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoIssuancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_issuances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('issuance_type', 40)->nullable();
            $table->integer('issuance_type_id')->unsigned()->comment('gso_issuances_types');
            $table->string('control_no', 40);
            $table->integer('requested_by')->unsigned();
            $table->timestamp('requested_date')->nullable();
            $table->integer('issued_by')->unsigned();
            $table->timestamp('issued_date')->nullable();
            $table->text('remarks')->nullable();
            $table->double('total_amount')->nullable();
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
        Schema::dropIfExists('gso_issuances');
    }
}
