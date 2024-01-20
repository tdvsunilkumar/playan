<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CboPayee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbo_payee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('paye_type')->unsigned();
            $table->integer('scp_id')->nullable()->unsigned();
            $table->integer('hr_employee_id')->nullable()->unsigned();
            $table->string('paye_name', 50);
            $table->string('paye_address_lotno', 50)->nullable();
            $table->string('paye_address_street', 50)->nullable();
            $table->string('paye_address_subdivision', 50)->nullable();
            $table->text('paye_full_address')->nullable();
            $table->string('brgy_code', 20);
            $table->string('paye_telephone_no', 30)->nullable();
            $table->string('paye_mobile_no', 22)->nullable();
            $table->string('paye_email_address', 50)->nullable();
            $table->string('paye_fax_no', 50)->nullable();
            $table->string('paye_tin_no', 50)->nullable();
            $table->string('paye_remarks', 255)->nullable();
            $table->boolean('paye_status')->default(1);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('paye_generated_by')->unsigned();
            $table->timestamp('updated_at')->nullable();
            $table->integer('paye_modified_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cbo_payee');
    }
}
