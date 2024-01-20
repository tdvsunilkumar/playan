<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcctgGeneralJournalsEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acctg_general_journals_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('general_journal_id')->unsigned()->comment('acctg_general_journals');
            $table->integer('gl_account_id')->unsigned()->comment('acctg_account_general_ledgers');            
            $table->double('debit_amount')->nullable();
            $table->double('credit_amount')->nullable();
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
        Schema::dropIfExists('acctg_general_journals_entries');
    }
}
