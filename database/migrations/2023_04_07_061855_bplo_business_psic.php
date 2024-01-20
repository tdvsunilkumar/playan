<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BploBusinessPsic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bplo_business_psic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('busn_id');
            $table->integer('subclass_id')->default(0);
            $table->integer('busp_no_units')->default(0)->nullable();
            $table->double('busp_capital_investment', 10, 2)->default(0.00)->nullable();
            $table->double('busp_essential', 10, 2)->default(0.00)->nullable();
            $table->double('busp_non_essential', 10, 2)->default(0.00)->nullable();
            $table->double('busp_total_gross', 10, 2)->default(0.00)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('created_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->boolean('is_synced')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bplo_business_psic');
    }
}
