<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForBploPaymentFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
            DROP PROCEDURE IF EXISTS `BPLO_PAYMENT_FILE`;

            CREATE PROCEDURE `BPLO_PAYMENT_FILE` (IN p_busn_id int,IN p_brgy_id int,IN p_status int, IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';

                IF p_busn_id>0 THEN
                    SET @subSql = CONCAT(' AND bb.id=',p_busn_id,@subSql);
                END IF;

                IF p_brgy_id>0 THEN
                    SET @subSql = CONCAT(' AND busn_office_barangay_id>=',p_brgy_id,@subSql);
                END IF;

              
                SET @subSql2 = CONCAT('SELECT bb.id,suffix,full_name,rpo_first_name,rpo_middle_name,rpo_custom_last_name,p_email_address,busn_name,busns_id_no,bb.app_code,brgy_name,or_no,cashier_or_date,cd.pm_id,pap_id,cashier_name,tax_credit_amount, total_amount, max_id 
                    FROM bplo_business AS bb JOIN barangays AS bg ON bb.busn_office_barangay_id=bg.id 
                    JOIN(SELECT MAX(cc.id) as max_id, busn_id, or_no, cashier_or_date, total_amount, pm_id, pap_id, usr.name AS cashier_name, tax_credit_amount 
                    FROM cto_cashier cc INNER JOIN users usr ON cc.created_by=usr.id WHERE cc.status=1 AND cc.id = ( SELECT MAX(id) FROM cto_cashier mcc 
                    WHERE mcc.busn_id=cc.busn_id ) GROUP BY busn_id ORDER BY cc.id DESC) AS cd ON cd.busn_id = bb.id INNER JOIN clients AS c ON c.id=bb.client_id WHERE bb.id>0 ');

                SET @SQLQuery = CONCAT(@subSql2,' AND bb.is_active=',p_status);

                SET @SQLQuery = CONCAT(@SQLQuery,@subSql);


                IF p_brgy_id>0 THEN
                    SET @SQLQuery = CONCAT(@SQLQuery,' AND bg.id=',p_brgy_id);
                END IF;

                /***********Search Data************/
                IF p_search IS NOT NULL THEN
                    SET @searchSql =  CONCAT(' AND (busn_name LIKE \'%',p_search,'%\' OR busns_id_no LIKE \'%',p_search,'%\' OR busns_id_no LIKE \'%',p_search,'%\' OR p_email_address LIKE \'%',p_search,'%\' OR full_name LIKE \'%',p_search,'%\'  OR rpo_first_name LIKE \'%',p_search,'%\'  OR rpo_middle_name LIKE \'%',p_search,'%\' OR rpo_custom_last_name LIKE \'%',p_search,'%\')');    
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
