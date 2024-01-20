<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HrLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hrlog_description');
            $table->integer('created_by');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_log');
    }
}
