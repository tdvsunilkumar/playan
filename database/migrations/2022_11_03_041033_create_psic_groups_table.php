<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsicGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psic_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id');
            $table->integer('division_id')->nullable();
            $table->string('group_code')->nullable();
            $table->text('group_description')->nullable();
            $table->integer('group_status')->default('0');
            $table->integer('group_generated_by')->default('0');
            $table->datetime('group_generated_date')->nullable();
            $table->integer('group_modified_by')->default('0');
            $table->datetime('group_modified_date')->nullable();
            $table->integer('is_active')->default('0');
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
        Schema::dropIfExists('psic_groups');
    }
}
