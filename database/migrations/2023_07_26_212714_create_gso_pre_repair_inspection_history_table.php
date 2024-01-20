<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPreRepairInspectionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_pre_repair_inspection_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('repair_id')->unsigned()->comment('gso_pre_repair_inspection_requests');
            $table->date('requested_date')->nullable();
            $table->text('concerns')->nullable();
            $table->text('remarks')->nullable();
            $table->date('completion_date')->nullable();
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
        Schema::dropIfExists('gso_pre_repair_inspection_history');
    }
}
