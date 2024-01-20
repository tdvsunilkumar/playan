CREATE TABLE `cto_check_disbursements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fund_code_id` int(11) NOT NULL COMMENT 'table-ref: acctg_fund_codes',
  `payee` VARCHAR(225) NULL,
  `disbursement_no` VARCHAR(225) NOT NULL,
  `date` date NOT NULL,
  `voucher_no` VARCHAR(50) NULL,
  `amount` float(11, 5) NOT NULL,
  `particulars` VARCHAR(225) NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

ALTER TABLE `cto_cash_reciepts` CHANGE `gl_id` `gl_id` INT(11) NULL COMMENT 'table-ref: acctg_account_general_ledgers';