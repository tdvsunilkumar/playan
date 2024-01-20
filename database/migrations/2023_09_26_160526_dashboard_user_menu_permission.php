<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DashboardUserMenuPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('dashboard_user_menu_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default('0')->nullable()->comment('Tbl Ref - users.id');
            $table->integer('menu_group_id')->default('0')->nullable()->comment('Tbl Ref - menu_groups.id');
            $table->text('menu_permissions')->nullable()->comment('JSON Format');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('dashboard_user_menu_permissions');
    }
}
