<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcoCemeteryApplicationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_cemetery_application_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cemetery_application_id')->unsigned()->comment('cto_top_transaction_type');
            $table->integer('citizen_id')->unsigned()->comment('citizens');
            $table->string('or_no', 20)->nullable();
            $table->date('or_date')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('remaining_balance')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('eco_cemetery_application_payments');
    }
}
