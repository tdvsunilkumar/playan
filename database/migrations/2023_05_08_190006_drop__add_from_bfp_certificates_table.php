<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAddFromBfpCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bfp_certificates', function (Blueprint $table) {
            $table->dropColumn('bfpas_code');
            $table->dropColumn('bio_code');
            $table->dropColumn('p_code');
            $table->dropColumn('brgy_code');
            $table->dropColumn('ba_code');
            $table->dropColumn('ba_business_account_no');
            $table->dropColumn('bio_remarks');

            $table->integer('bend_id')->comment('Ref-Table:bplo_business_endorsement.id')->nullable()->after('id');
            $table->integer('bff_id')->comment('Ref-Table: bfp_application_form.id')->nullable()->after('bend_id');
            $table->integer('bfpas_id')->comment('foreign key bfp_application_assessment.id')->nullable()->after('bff_id');
            $table->integer('bio_id')->comment('foreign key bfp_inspection_order.id reference')->nullable()->after('bfpas_id');
            $table->integer('client_id')->comment('Ref-Table: clients.id')->nullable()->after('bio_id');
            $table->integer('bgy_id')->comment('Ref-Table: barangays.id')->nullable()->after('client_id');
            $table->integer('busn_id')->comment('Ref-Table: bplo_business.id')->nullable()->after('bgy_id');
            $table->integer('bfpcert_year')->comment('Year')->nullable()->after('busn_id');
            $table->integer('bfpcert_no')->comment('FSIC No.')->nullable()->after('bfpcert_year');
            $table->date('bfpcert_date')->comment('Date Applied')->nullable()->after('bfpcert_no');
            $table->string('bfpcert_remarks',200)->comment('remarks')->nullable()->after('bfpcert_date_expired');
            $table->string('bfpcert_approved_recommending_position',250)->comment('Position')->nullable()->after('bfpcert_approved_recommending');
            $table->date('bfpcert_approved_recommending_date')->comment('date')->nullable()->after('bfpcert_approved_recommending_position');
            $table->string('bfpcert_approved_position',250)->comment('Position')->nullable()->after('bfpcert_approved');
            $table->date('bfpcert_approved_date')->comment('date')->nullable()->after('bfpcert_approved_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bfp_certificates', function (Blueprint $table) {
            //
        });
    }
}
