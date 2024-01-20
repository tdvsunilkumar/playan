SET FOREIGN_KEY_CHECKS=0;
SET innodb_strict_mode=0;
ALTER TABLE `clients`
ADD `client_year` INT(4) NULL AFTER `is_synced`,
ADD `client_no` INT(11) NULL AFTER `client_year`,
ADD `account_no` BIGINT(20) AS (CAST(CONCAT(LPAD(client_year, 4, '0'), LPAD(client_no, 6, '0')) AS UNSIGNED)) STORED AFTER `client_no`;
