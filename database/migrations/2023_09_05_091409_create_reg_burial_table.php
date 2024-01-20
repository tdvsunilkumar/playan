<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegBurialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reg_burial', function (Blueprint $table) {
            $table->id();
            $table->integer('expired_id')->comment('Ref-table: citizens.id');
            $table->string('expired_name')->comment('Full name coming from table: citizens');
            $table->text('death_caused')->comment('Cause of Death');
            $table->date('death_date')->comment('Date of Death'); 
            $table->integer('cm_id')->comment('Ref-Table: eco_cemeteries.id');
            $table->integer('is_infectious')->comment('In case of disinterment 1 = Infectious, 2 = Non-infectious');
            $table->integer('is_embalmed')->comment('1 = Embalmed, 2 = Not Embalmed');
            $table->date('disposition_date')->comment('Disposition of Remains');
            $table->integer('cashierd_id')->comment('Ref-Table: cto_cashier_details.id');
            $table->integer('cashier_id')->comment('Ref-Table:cto_cashier.id'); 
            $table->string('or_no')->comment('Ref-Table: cto_cashier.or_no'); 
            $table->date('or_date')->comment('Ref-Table: cto_cashier.cashier_or_date');
            $table->double('or_amount',10,2)->comment('Ref-Table: cto_cashier_details.tfc_amount');
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
        Schema::dropIfExists('reg_burial');
    }
}
