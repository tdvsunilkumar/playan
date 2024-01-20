<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cho_registration', function (Blueprint $table) {
            $table->id();
			$table->integer('cit_id')->length(11)->default('0')->comment('ref-table:citizens. cit_id.');
			$table->integer('reg_year')->length(4)->default('0')->comment('Current Year');
			$table->integer('is_opd')->length(1)->default('0')->comment('0 = No, 1 = Yes');
			$table->integer('is_lab')->length(1)->default('0')->comment('0 = No, 1 = Yes');
			$table->integer('is_family_planning')->length(1)->default('0')->comment('0 = No, 1 = Yes');
			$table->integer('is_sanitary')->length(1)->default('0')->comment('0 = No, 1 = Yes');
			$table->date('reg_date')->comment('Format: [yyyy-mm-dd].');
            $table->integer('reg_status')->length(1)->default('0')->comment('0 = Inactive, 1 = Active');
			$table->string('reg_remarks')->length(100);
            $table->integer('created_by')->length(14)->default('0');
            $table->integer('updated_by')->length(14)->default('0');
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
        Schema::dropIfExists('cho_registration');
    }
}
