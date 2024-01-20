<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccessApprovalApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_access_approval_approvers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('setting_id')->unsigned()->comment('user_access_approval_settings');
            $table->integer('department_id')->unsigned()->comment('acctg_departments');
            $table->text('primary_approvers')->nullable();
            $table->text('secondary_approvers')->nullable();
            $table->text('tertiary_approvers')->nullable();
            $table->text('quaternary_approvers')->nullable();
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
        Schema::dropIfExists('user_access_approval_approvers');
    }
}
