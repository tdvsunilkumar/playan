<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForBploTaxFeesOtherCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
            DROP PROCEDURE IF EXISTS `REPORT_BPLO_TAX_FEE_OTHER_CHARGES`;

            CREATE PROCEDURE `REPORT_BPLO_TAX_FEE_OTHER_CHARGES` (IN p_fromdate date,IN p_todate date, IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';
                SET @subSqlNew='';
                SET @subSqlReNew='';

                IF p_fromdate IS NOT NULL THEN
                    SET @subSql = CONCAT(' AND cd.cashier_or_date >=',p_fromdate,@subSql);
                END IF;

                IF p_todate IS NOT NULL AND p_todate != '' THEN
                    SET @subSql = CONCAT(' AND cd.cashier_or_date <=',p_todate,@subSql);
                END IF;

                SET @subSql2 = CONCAT('SELECT 
                   aas.description as accdesc,

                    (SELECT sum(tfc_amount) FROM cto_cashier_details AS ccd INNER JOIN cto_cashier AS cd ON ccd.cashier_id = cd.id WHERE ct.id=ccd.tfoc_id AND ccd.tfoc_is_applicable=1 ',@subSql,')  AS totalpaidamount,

                    (SELECT sum(surcharge_fee) FROM cto_cashier_details AS ccd INNER JOIN cto_cashier AS cd ON ccd.cashier_id = cd.id WHERE ct.id=ccd.tfoc_id AND ccd.tfoc_is_applicable=1 ',@subSql,')  AS totalpenalty

                     FROM cto_tfocs AS ct 
                     INNER JOIN acctg_account_general_ledgers AS aal ON aal.id = ct.gl_account_id
                     INNER JOIN acctg_account_subsidiary_ledgers AS aas ON aas.id = ct.sl_id
                     WHERE ct.tfoc_is_applicable = 1 ',@searchSql);
                

                /***********Search Data************/
                
                SET @SQLQuery = CONCAT(@subSql2,@searchSql);

                SET @SQLQuery =CONCAT(@SQLQuery,' GROUP BY ct.id');
                /***********Order By************/
                IF p_order_by IS NOT NULL THEN
                    SET @SQLOrder = CONCAT(' ORDER BY ',p_order_by);
                END IF;

                SET @SQLQuery = CONCAT(@SQLQuery,@SQLOrder);
                
                /***********Get Count Without Limit************/
                SET @totalCnt = CONCAT('Select Count(*) INTO @p_total_count FROM (',@SQLQuery,') AS tmp');

                PREPARE cnt FROM @totalCnt;
                EXECUTE cnt;

                SET p_total_count=@p_total_count; 

                IF p_limit IS NOT NULL AND p_limit != '' THEN
                SET @SQLQuery = CONCAT(@SQLQuery,' LIMIT ',p_offset,', ',p_limit);
                END IF;

                PREPARE stmt FROM @SQLQuery;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;

            END";
        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
