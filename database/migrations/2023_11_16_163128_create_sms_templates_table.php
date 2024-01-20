<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_id')->unsigned()->comment('menu_groups');
            $table->integer('module_id')->unsigned()->comment('menu_modules');
            $table->integer('sub_module_id')->unsigned()->comment('menu_sub_modules');
            $table->integer('action_id')->unsigned()->comment('sms_actions');
            $table->integer('type_id')->unsigned()->comment('sms_types');
            $table->text('application');
            $table->text('template');
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
        Schema::dropIfExists('sms_templates');
    }
}
