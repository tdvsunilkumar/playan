CREATE TABLE `cto_cash_reciepts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fund_code_id` int(11) NOT NULL COMMENT 'table-ref: acctg_fund_codes',
  `type_of_charge_id` int(11) NOT NULL COMMENT 'table-ref: cto_type_of_charges',
  `date` date NOT NULL,
  `gl_id` int(11) NOT NULL COMMENT 'table-ref: acctg_account_general_ledgers',
  `amount` float(11, 5) NOT NULL,
  `or_no` VARCHAR(50) NOT NULL,
  `particulars` VARCHAR(225) NOT NULL,
  `is_income` boolean NOT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;