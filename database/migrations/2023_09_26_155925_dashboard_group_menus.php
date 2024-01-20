<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DashboardGroupMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_group_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('menu_group_id')->default('0')->nullable()->comment('menu_groups');
            $table->string('menu_name',100)->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('slug',100);
            $table->string('icon',100)->nullable();
            $table->integer('created_by')->default('0');
            $table->integer('updated_by')->default('0');
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
        Schema::dropIfExists('dashboard_group_menus');
    }
}
