<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfarePwdOrganization extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_pwd_organization', function (Blueprint $table) {
            $table->id();
            $table->integer('wpaf_id')->comment('ref-Table: welfare_pwd_application_form');
            $table->string('wpo_organization')->nullable();
            $table->string('wpo_contact_person')->nullable();
            $table->string('wpo_office_address')->nullable();
            $table->string('wpo_contact_number')->nullable();

            $table->integer('wpo_is_active')->length(1)->comment('if the status of the application type is active or not');
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
        Schema::dropIfExists('welfare_pwd_organization');
    }
}
