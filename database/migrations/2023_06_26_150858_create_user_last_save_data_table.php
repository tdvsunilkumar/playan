<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLastSaveDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_last_save_data', function (Blueprint $table) {
            $table->id();
            $table->integer('form_id')->length(11)->default('0')->comment('Ref-Table: user_forms.id');
            $table->integer('user_id')->length(11)->default('0')->comment('Raf-Table: users.id');
            $table->text('is_data')->nullable();
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
        Schema::dropIfExists('user_last_save_data');
    }
}
