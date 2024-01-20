<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelfareSeniorsCitizenApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welfare_seniors_citizen_application', function (Blueprint $table) {
            $table->id();
            $table->integer('cit_id')->comment('ref-Table: citizens.cit_id');
            $table->string('wsca_since_when',20)->nullable();
            $table->string('wsca_previous_address',200)->nullable();
            $table->integer('wstor_id')->nullable()->comment('ref-Table: welfare_sca_type_of_residency.wstor_id');
            $table->string('wsca_skill',200)->nullable();
            $table->string('wsca_occupation',200)->nullable();
            $table->decimal('wsca_monthly_income',14,3)->nullable();
            $table->decimal('wsca_pension_amount',14,3)->nullable();
            $table->integer('wsca_name_of_spouse')->comment('ref-Table: citizens.cit_id');
            $table->date('wsca_date_of_marriage')->nullable();
            $table->string('wsca_place_of_marriage',100)->nullable();
            $table->integer('wsca_existing_senior')->nullable()->length(1)->comment('Yes, No');
            $table->string('wsca_existing_id')->nullable();
            $table->string('wsca_existing_place_of_issue')->nullable();
            $table->date('wsca_existing_date_of_issue')->nullable();
            $table->string('wsca_remarks',200)->nullable();
            $table->string('wsca_new_osca_id_no',50)->nullable();
            $table->date('wsca_new_osca_id_no_date_issued')->nullable();
            $table->string('wsca_fscap_id_no',50)->nullable();
            $table->date('wsca_fscap_id_no_date_issued')->nullable();
            $table->string('wsca_philhealth_no',50)->nullable();

            $table->integer('wsca_is_active')->length(1)->comment('if the status of the application type is active or not');
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
        Schema::dropIfExists('welfare_seniors_citizen_application');
    }
}
