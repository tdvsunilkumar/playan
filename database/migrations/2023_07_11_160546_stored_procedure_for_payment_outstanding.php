<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForPaymentOutstanding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $procedure = "
            DROP PROCEDURE IF EXISTS `BPLO_OUTSTANDING_PAYMENTS`;

            CREATE PROCEDURE `BPLO_OUTSTANDING_PAYMENTS` (IN p_year int, IN p_pm_id int, IN p_period int, IN p_brgy_id int, IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';

                IF p_pm_id>0 THEN
                    SET @subSql = CONCAT(' AND payment_mode=',p_pm_id,@subSql);
                END IF;

                IF p_period>0 THEN
                    SET @subSql = CONCAT(' AND assessment_period<=',p_period,@subSql);
                END IF;

                IF p_year>0 THEN
                    SET @subSql = CONCAT(' AND assess_year=',p_year,@subSql);
                END IF;
                
                SET @subSql2 = CONCAT('SELECT\n                    busn_id AS id,suffix,rpo_first_name,full_name,rpo_middle_name,rpo_custom_last_name,p_email_address,busn_name,busns_id_no,app_code,fa.sub_amount,fa.interest_fee,fa.surcharge_fee,fa.total_amount,brgy_name\n                FROM\n                    bplo_business AS bb JOIN barangays AS bg ON bb.busn_office_barangay_id=bg.id\n\n                JOIN(SELECT\n                    busn_id,\n                    SUM(sub_amount) As sub_amount,\n                    SUM(interest_fee) As interest_fee,\n                    SUM(surcharge_fee) As surcharge_fee,\n                    SUM(total_amount) As total_amount\n                    FROM cto_bplo_final_assessment_details \n                    WHERE  payment_status=0 AND app_code IN(1,2) ',@subSql);

                SET @SQLQuery =CONCAT(@subSql2,'\n                    GROUP BY busn_id HAVING total_amount > 0\n                ) AS fa ON fa.busn_id = bb.id\n                INNER JOIN clients AS c ON c.id=bb.client_id AND bb.app_code IN(1,2) WHERE bb.id>0 ');

                IF p_brgy_id>0 THEN
                    SET @SQLQuery = CONCAT(@SQLQuery,' AND bg.id=',p_brgy_id);
                END IF;

                /***********Search Data************/
                
                IF p_search IS NOT NULL THEN
                    IF p_search = 'New' THEN
                        SET @searchSql = CONCAT(@searchSql, ' AND app_code = 1');
                    ELSEIF p_search = 'Renew' THEN
                        SET @searchSql = CONCAT(@searchSql, ' AND app_code = 2');
                    ELSE
                        SET @searchSql = CONCAT(@searchSql, ' AND (busn_name LIKE \'%', p_search, '%\' OR full_name LIKE \'%', p_search, '%\' OR brgy_name LIKE \'%', p_search, '%\' OR p_email_address LIKE \'%', p_search, '%\' OR busns_id_no LIKE \'%', p_search, '%\' OR rpo_first_name LIKE \'%', p_search, '%\' OR rpo_middle_name LIKE \'%', p_search, '%\' OR rpo_custom_last_name LIKE \'%', p_search, '%\')');
                    END IF;
                END IF;
                
                SET @SQLQuery = CONCAT(@SQLQuery,@searchSql);

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
