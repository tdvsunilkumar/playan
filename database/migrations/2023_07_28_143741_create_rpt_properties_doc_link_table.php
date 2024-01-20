<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRptPropertiesDocLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpt_properties_doc_link', function (Blueprint $table) {
            $table->id();
            $table->integer('rp_property_code')->comment('permanent code domainate thought out the record, regardles of how many tax declaration number has been pass or revisions has been made');
            $table->integer('type')->comment('1=document, 2=link');
            $table->string('Remark')->comment('Remark');
            $table->text('doc_link')->comment('link,document attachment');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('rpt_properties_doc_link');
    }
}
