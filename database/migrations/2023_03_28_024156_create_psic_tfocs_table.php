<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsicTfocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psic_tfocs', function (Blueprint $table) {
            $table->id();
            $table->integer('ptfoc_access_type')->default('0')->length(1)->comment('1=Access By PSIC Major(or Section), 2=Access By PSIC Sub-class(or Nature Of Business)');
            $table->integer('section_id')->default('0')->comment('psic_section.psic_section')->nullable();
            $table->integer('subclass_id')->default('0')->comment('psic_subclass.subclass_id')->nullable();
            $table->integer('ctype_id')->default('0')->comment('cto_charge_types.ctype_id');
            $table->integer('tfoc_id')->default('0')->comment('cto_tfoc.tfoc_id');
            $table->integer('app_code')->default('0')->comment('bplo_application_type.app_code');
            $table->date('ptfoc_effectivity_date')->nullable();
            $table->integer('ptfoc_is_no_of_units')->default('0')->length(1)->nullable();
            $table->integer('ptfoc_is_distribute_per_barangay')->default('0')->length(1)->nullable();
            $table->integer('cctype_id')->default('0')->comment('cto_computation_type.cctype_id');
            $table->integer('ptfoc_basis_id')->default('0')->comment('cto_tfoc_basis display basis_name  WHERE LEN(basis_ref_table)>0 and LEN(basis_field_reference)>0')->nullable();
            $table->integer('ptfoc_gl_id')->default('0')->comment('acctg_account_general_ledger.agl_code')->nullable();
            $table->integer('ptfoc_sl_id')->default('0')->nullable();
            $table->double('ptfoc_constant_amount',14,2)->nullable();
            $table->json('ptfoc_json')->nullable();
            $table->text('ptfoc_remarks')->nullable();
            $table->integer('ptfoc_is_active')->default('0')->length(1);
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
        Schema::dropIfExists('psic_tfocs');
    }
}
