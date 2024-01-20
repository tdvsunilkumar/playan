<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsoPropertyAccountabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gso_property_accountabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('property_type_id')->unsigned()->comment('gso_property_types');
            $table->string('property_no', 40);
            $table->integer('pr_id')->unsigned()->comment('gso_item_types')->nullable();
            $table->integer('issuance_id')->unsigned()->comment('gso_issuances');
            $table->integer('issued_by')->unsigned()->comment('hr_employees');
            $table->integer('received_by')->unsigned()->comment('hr_employees');
            $table->date('received_date')->nullable();
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');
            $table->integer('item_type_id')->unsigned()->comment('gso_item_types');
            $table->integer('item_id')->unsigned()->comment('gso_items');
            $table->integer('uom_id')->unsigned()->comment('gso_unit_of_measurements');
            $table->double('quantity')->nullable();
            $table->double('unit_cost')->nullable();
            $table->text('estimated_life_span')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status', 40)->default('acquired');
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
        Schema::dropIfExists('gso_property_accountabilities');
    }
}
