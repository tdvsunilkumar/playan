<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForBploBusinesslistPerBarangay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
            DROP PROCEDURE IF EXISTS `REPORT_BPLOLIST_PER_BRGY`;

            CREATE PROCEDURE `REPORT_BPLOLIST_PER_BRGY` (IN p_brgayid int,IN fromdate date,IN p_todate date,IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';
                SET @subSqlNew='';
                SET @subSqlNew2='';
                SET @subSqlReNew='';

                IF p_brgayid > 0 THEN
                    SET @subSql = CONCAT(' AND bb.busn_office_main_barangay_id=',p_brgayid,@subSql);
                END IF;

                

                SET @subSql2 = CONCAT('SELECT 
                    bb.busn_name, bbpi.bpi_remarks, bb.busn_office_main_barangay_id, bb.busns_id_no, bb.busn_id, cl.full_name, cl.rpo_first_name AS rpo_first_name, cl.rpo_middle_name AS rpo_middle_name, cl.rpo_custom_last_name AS rpo_custom_last_name, cl.suffix AS suffix, bb.busn_employee_total_no, bb.app_code, bbpi.bpi_issued_date, bb.pm_id,
                    (SELECT SUM(total_amount) FROM cto_bplo_final_assessment_details AS cfa WHERE cfa.busn_id = bb.busn_id ', @subSqlNew, ') AS totalassessment,
                    (SELECT SUM(total_paid_amount) FROM cto_cashier AS cc WHERE cc.busn_id = bb.busn_id ', @subSqlReNew, ') AS totalpaidamount
                FROM bplo_business_history AS bb 
                INNER JOIN clients AS cl ON cl.id = bb.client_id
                INNER JOIN bplo_business_permit_issuance AS bbpi ON bbpi.busn_id = bb.busn_id 
                WHERE bb.id > 0 ', @subSql);
                IF fromdate IS NOT NULL AND p_todate IS NOT NULL AND p_todate != '' THEN
                    SET @subSqlNew2 = CONCAT(
                        @subSqlNew2, 
                        ' AND (DATE(bpi_issued_date) BETWEEN ''', DATE(fromdate), ''' AND ''', DATE(p_todate), ''')'
                    );
                END IF;

                /***********Search Data************/
                IF p_search IS NOT NULL THEN
                SET @searchSql =  CONCAT(@searchSql, ' AND (busn_name LIKE \'%', p_search, '%\' OR full_name LIKE \'%', p_search, '%\')');  
                END IF;

                SET @SQLQuery = CONCAT(@subSql2, @searchSql, @subSqlNew2, ' GROUP BY bb.id');
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
