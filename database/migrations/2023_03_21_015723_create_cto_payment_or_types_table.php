<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtoPaymentOrTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cto_payment_or_types', function (Blueprint $table) {
            $table->id();
            $table->string('ortype_name')->length(50);
			$table->integer('ora_status')->length(1)->default('0');
            $table->integer('or_is_applicable')->length(11)->comment('1=Business Permit, 2=Real Property, 3=Engineering, 4=Occupancy,5=Planning & Devt., 6=Health & Safety, 7=Community Tax, 8=Burial Permit, 9=Miscellaneous');
            $table->string('ora_remarks')->nullable()->length(150);
            $table->integer('created_by')->length(11)->default('0');
            $table->integer('updated_by')->length(11)->default('0');
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
        Schema::dropIfExists('cto_payment_or_types');
    }
}
