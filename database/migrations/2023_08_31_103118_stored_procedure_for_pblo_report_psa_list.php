<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForPbloReportPsaList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         $procedure = "
            DROP PROCEDURE IF EXISTS `REPORT_BPLO_PSA_LIST`;

            CREATE PROCEDURE `REPORT_BPLO_PSA_LIST` (IN fromdate date,IN p_todate date, IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';
                SET @subSqlNew='';
                SET @subSqlNew2='';
                SET @subSqlReNew='';

               

                SET @subSql2 = CONCAT('SELECT 
                    bb.busn_name,bb.busn_id,bb.created_at,cl.full_name,cl.rpo_first_name As rpo_first_name,cl.rpo_middle_name As rpo_middle_name,cl.rpo_custom_last_name As rpo_custom_last_name,cl.suffix As suffix,bb.busn_employee_total_no,bb.app_code,

                    (SELECT sum(busp_capital_investment) FROM bplo_business_psic AS bbp WHERE bbp.busn_id=bb.busn_id AND bb.app_code=1)  AS capitalinvestment,

                    (SELECT sum(busp_total_gross) FROM bplo_business_psic AS bbp WHERE bbp.busn_id=bb.busn_id AND bb.app_code=2)  AS grosssale

                     FROM bplo_business_history AS bb 
                     INNER JOIN clients AS cl ON cl.id = bb.client_id
                     INNER JOIN bplo_business_permit_issuance AS bbpi
                     ON bbpi.busn_id=bb.busn_id WHERE bb.id > 0 ',@subSql);
                

                /***********Search Data************/
                IF fromdate IS NOT NULL AND p_todate IS NOT NULL AND p_todate != '' THEN
                    SET @subSqlNew2 = CONCAT(
                        @subSqlNew2, 
                        ' AND (DATE(bb.created_at) BETWEEN ''', DATE(fromdate), ''' AND ''', DATE(p_todate), ''')'
                    );
                END IF;
                IF p_search IS NOT NULL THEN
                SET @searchSql =  CONCAT(@searchSql, ' AND (busn_name LIKE \'%', p_search, '%\' OR full_name LIKE \'%', p_search, '%\')');  
                END IF;

                SET @SQLQuery = CONCAT(@subSql2, @searchSql, @subSqlNew2, ' GROUP BY bb.busn_id');

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

                SET @SQLQuery = CONCAT(@SQLQuery,' LIMIT ',p_offset,', ',p_limit);

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
