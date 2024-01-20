<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBfpApplicationFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bfp_application_forms', function (Blueprint $table) {
            
            $table->dropColumn('p_code');
            $table->dropColumn('brgy_code');
            $table->dropColumn('bff_representative_code');
            $table->dropColumn('subclass_code');
            $table->dropColumn('bot_code');
            $table->dropColumn('bot_occupancy_type');
            $table->dropColumn('ba_building_total_area_occupied');
            $table->integer('bend_id')->after('id')->comment('Ref-Table:bplo_business_endorsement.id');
            $table->integer('busn_id')->after('bend_id')->comment('Ref-Table: bplo_business.id');
            $table->integer('client_id')->after('busn_id')->comment('Ref-Table: clients.id');
            $table->integer('barangay_id')->after('client_id')->comment('Ref-Table: barangays.id');
            $table->integer('bff_representative_id')->after('barangay_id')->comment('Ref-Table: citizens.id');
            $table->integer('bot_id')->after('bff_representative_id')->comment('Ref-Table: bfp_occupancy_types.id..... Can be [1=Industrial,2=Educational,3=Detention and Correctional,4=Mercantile,5=Business,6=Health Care,7=Storage,8=Single and Two-Family Dwelling,9=Miscellaneous,10=Theater,11=Small General Business Establishment,12=Residential,13=Gasoline Service Station,14=Places of Assembly]');
            $table->integer('busn_bldg_area')->after('bot_id')->comment('Ref-Table: bplo_business.busn_bldg_area');
            $table->integer('busn_bldg_total_floor_area')->after('busn_bldg_area')->comment('Ref-Table: citizens.id');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bfp_application_forms', function (Blueprint $table) {
            $table->integer('p_code');
            $table->string('brgy_code');
            $table->integer('bff_representative_code');
            $table->integer('subclass_code');
            $table->integer('bot_code');
            $table->string('bot_occupancy_type');
            $table->string('ba_building_total_area_occupied'); 
            
            $table->dropColumn('bend_id')->after('id')->comment('Ref-Table:bplo_business_endorsement.id');
            $table->dropColumn('busn_id')->after('bend_id')->comment('Ref-Table: bplo_business.id');
            $table->dropColumn('client_id')->after('busn_id')->comment('Ref-Table: clients.id');
            $table->dropColumn('barangay_id')->after('client_id')->comment('Ref-Table: barangays.id');
            $table->dropColumn('bff_representative_id')->after('barangay_id')->comment('Ref-Table: citizens.id');
            $table->dropColumn('bot_id')->after('bff_representative_id')->comment('Ref-Table: bfp_occupancy_types.id..... Can be [1=Industrial,2=Educational,3=Detention and Correctional,4=Mercantile,5=Business,6=Health Care,7=Storage,8=Single and Two-Family Dwelling,9=Miscellaneous,10=Theater,11=Small General Business Establishment,12=Residential,13=Gasoline Service Station,14=Places of Assembly]');
            $table->dropColumn('busn_bldg_area')->after('bot_id')->comment('Ref-Table: bplo_business.busn_bldg_area');
            $table->dropColumn('busn_bldg_total_floor_area')->after('busn_bldg_area')->comment('Ref-Table: citizens.id');
        });
    }
}
