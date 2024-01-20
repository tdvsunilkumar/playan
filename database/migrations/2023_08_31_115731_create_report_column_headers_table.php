<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportColumnHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_column_headers', function (Blueprint $table) {
            $table->id();
            $table->integer('pcs_id')->comment('Ref-Table: cto_payment_cashier_system.id');
            $table->string('rep_header_name')->comment('Name of the Report Header');
            $table->integer('tfoc_id')->comment('Ref-Table: cto_tfocs.id');
            $table->string('description')->comment('Description');
            $table->string('remark')->comment('Description');
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
        Schema::dropIfExists('report_column_headers');
    }
}
