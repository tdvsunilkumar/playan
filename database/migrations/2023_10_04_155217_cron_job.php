<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CronJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_job', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->string('description');
            $table->string('remarks');
            $table->integer('schedule_type');
            $table->integer('schedule_value');
            $table->string('url');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('cron_job');
    }
}
