<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LegalHousingApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eco_housing_application', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->comment('unique code and primary key of the table');
            $table->integer('type_of_transaction_id')->comment('ref-Table : eco_type_of_transaction . Id');
            $table->date('app_date');
			$table->integer('client_id')->comment('ref-Table citizen . Id');
            $table->string('contact_no')->length(20)->nullable();
            $table->integer('gender');
            $table->integer('status');
            $table->string('email_address')->length(255)->nullable();
            $table->string('current Address')->length(255)->nullable();
            $table->integer('barangay_id')->comment('ref-Table : barangay . Id');
            $table->integer('month_terms');
            $table->date('terms_date_from');
            $table->date('terms_date_to');
            $table->double('total_amount', 14, 2);
            $table->double('initial_monthly', 14, 2);
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('eco_housing_application');
    }
}
