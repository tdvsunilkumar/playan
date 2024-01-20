<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareSoloParentApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_solo_parent_application', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id');
            $table->string('wspa_occupation')->nullable();
            $table->decimal('wspa_monthly_income',14,3)->nullable();
            $table->decimal('wspa_total_income',14,3)->nullable();
            $table->string('wspa_classification')->nullable();
            $table->string('wspa_needs_problem')->nullable();
            $table->string('wspa_family_resources')->nullable();
            $table->integer('wspa_is_active')->length(1)->comment('if the status of the application type is active or not');
            $table->integer('created_by')->unsigned()->comment('reference profile.reg_code of the system who create the application type');
            $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Format: [yyyy-mm-dd hh:mm:ss]. ');
            $table->integer('updated_by')->unsigned()->nullable()->comment('reference profile.reg_code of the system who modified  the application type');
            $table->timestamp('updated_at')->nullable()->comment('Format: [yyyy-mm-dd hh:mm:ss]. default is 1000-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('welfare_solo_parent_application');
    }
}