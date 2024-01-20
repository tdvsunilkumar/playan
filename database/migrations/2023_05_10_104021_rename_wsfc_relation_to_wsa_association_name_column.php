<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameWsfcRelationToWsaAssociationNameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('welfare_sca_association', function(Blueprint $table) {
            $table->renameColumn('wsfc_relation', 'wsa_association_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('welfare_sca_association', function(Blueprint $table) {
            $table->renameColumn('wsa_association_name', 'wsfc_relation');
        });
    }
}
