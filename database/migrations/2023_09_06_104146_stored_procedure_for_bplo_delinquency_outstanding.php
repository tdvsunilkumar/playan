<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForBploDelinquencyOutstanding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
            DROP PROCEDURE IF EXISTS `BPLO_DLINQUENCY_OUTSTANDING_PAYMENTS`;

            CREATE PROCEDURE `BPLO_DLINQUENCY_OUTSTANDING_PAYMENTS` (IN p_busn_id int, IN p_client_id int, IN p_brgy_id int, IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';
                SET @prevYear = YEAR(CURDATE())-1;
                SET @currentYear = YEAR(CURDATE());

                IF p_busn_id>0 THEN
                    SET @subSql = CONCAT(' AND busn_id=',p_busn_id,@subSql);
                END IF;

                SET @subSql2 = CONCAT('SELECT
                    busn_id AS id, bb.pm_id, full_name,p_email_address,busn_name,busns_id_no,app_code,fa.out_sub_amount,fa.out_interest_fee,fa.out_surcharge_fee,fa.out_total_amount,brgy_name,del_sub_amount,del_interest_fee,del_surcharge_fee,del_total_amount,(COALESCE(fa.out_total_amount, 0) + COALESCE(NULLIF(del_total_amount, \'\'), 0)) AS total_del_out_smt,cashier_or_date,total_paid_amount,or_no
                FROM
                    bplo_business AS bb JOIN barangays AS bg ON bb.busn_office_barangay_id=bg.id

                JOIN(SELECT
                    busn_id,
                    SUM(sub_amount) As out_sub_amount,
                    SUM(interest_fee) As out_interest_fee,
                    SUM(surcharge_fee) As out_surcharge_fee,
                    SUM(total_amount) As out_total_amount
                    FROM cto_bplo_final_assessment_details 
                    WHERE  payment_status=0 AND app_code IN(1,2) AND assess_year = ',@currentYear,@subSql);

                SET @SQLQuery =CONCAT(@subSql2,'
                    GROUP BY busn_id HAVING out_total_amount > 0
                ) AS fa ON fa.busn_id = bb.id 

                LEFT JOIN(SELECT
                    busn_id AS del_busn_id,
                    SUM(sub_amount) As del_sub_amount,
                    SUM(interest_fee) As del_interest_fee,
                    SUM(surcharge_fee) As del_surcharge_fee,
                    SUM(total_amount) As del_total_amount
                    FROM bplo_business_delinquents AS bd  
                    WHERE bd.app_code IN(1,2) AND year <=',@prevYear,' GROUP BY del_busn_id HAVING del_total_amount > 0
                ) AS dlin ON dlin.del_busn_id = bb.id

                LEFT JOIN(SELECT 
                    cc.busn_id AS csh_busn_id, 
                    or_no, 
                    cashier_or_date, 
                    total_paid_amount
                    FROM cto_cashier cc WHERE cc.id = ( SELECT MAX(id) FROM cto_cashier mcc 
                    WHERE mcc.busn_id=cc.busn_id ) 
                ) AS csh ON csh.csh_busn_id = bb.id

                INNER JOIN clients AS c ON c.id=bb.client_id AND bb.app_code IN(1,2) WHERE bb.id>0 AND busn_tax_year=',@currentYear);

                IF p_brgy_id>0 THEN
                    SET @SQLQuery = CONCAT(@SQLQuery,' AND bg.id=',p_brgy_id);
                END IF;

                IF p_client_id>0 THEN
                    SET @SQLQuery = CONCAT(@SQLQuery,' AND bb.client_id=',p_client_id);
                END IF;

                /***********Search Data************/
                IF p_search IS NOT NULL THEN
                    SET @searchSql =  CONCAT(' AND (busn_name LIKE \'%',p_search,'%\' OR busns_id_no LIKE \'%',p_search,'%\' OR busns_id_no LIKE \'%',p_search,'%\' OR p_email_address LIKE \'%',p_search,'%\' OR full_name LIKE \'%',p_search,'%\')');    
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
