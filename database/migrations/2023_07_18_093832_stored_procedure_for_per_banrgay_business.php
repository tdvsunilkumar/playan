<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoredProcedureForPerBanrgayBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "
            DROP PROCEDURE IF EXISTS `REPORT_BPLO_PER_BRGY`;

            CREATE PROCEDURE `REPORT_BPLO_PER_BRGY` (IN p_year int, IN p_offset int, IN p_limit int, IN p_search VARCHAR(100),IN p_order_by VARCHAR(50), OUT p_total_count INT(11))
            BEGIN
                SET @subSql='';
                SET @searchSql='';
                SET @subSqlNew='';
                SET @subSqlReNew='';

                IF p_year>0 THEN
                    SET @subSql = CONCAT(' AND bpi.bpi_year=',p_year,@subSql);
                    SET @subSqlNew = CONCAT(' AND nbpi.bpi_year=',p_year);
                    SET @subSqlReNew = CONCAT(' AND rbpi.bpi_year=',p_year);
                END IF;

                SET @subSql2 = CONCAT(' SELECT 
                    brgy_name,brgy_id,

                    (SELECT COUNT(id) FROM bplo_business_permit_issuance AS nbpi WHERE nbpi.brgy_id=br.id AND app_type_id=1 AND nbpi.bpi_issued_status=1 ',@subSqlNew,') AS new_application,

                    (SELECT COUNT(id) FROM bplo_business_permit_issuance AS rbpi WHERE rbpi.brgy_id=br.id AND app_type_id=2 AND rbpi.bpi_issued_status=1 ',@subSqlReNew,') AS renewal_application

                     FROM bplo_business_permit_issuance AS bpi 
                     INNER JOIN barangays AS br 
                     ON bpi.brgy_id=br.id 
                     WHERE bpi.bpi_issued_status=1 ',@subSql);
                

                /***********Search Data************/
                IF p_search IS NOT NULL THEN
                    SET @searchSql =  CONCAT(' AND (brgy_name LIKE \'%',p_search,'%\')');    
                END IF;
                
                SET @SQLQuery = CONCAT(@subSql2,@searchSql);

                SET @SQLQuery =CONCAT(@SQLQuery,' GROUP BY brgy_id');
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
