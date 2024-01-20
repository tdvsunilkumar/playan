ALTER TABLE `acctg_deductions` ADD COLUMN IF NOT EXISTS  `is_payroll` INT NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `acctg_deductions` CHANGE `sl_account_id` `sl_account_id` INT(10) NULL COMMENT 'acctg_account_subsidiary_ledgers';

ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `is_debit_memo` INT NOT NULL DEFAULT '0' AFTER `is_active`;

CREATE TABLE `acctg_debit_memos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) DEFAULT NULL COMMENT 'acctg_vouchers',
  `payee_id` int(11) DEFAULT NULL COMMENT 'cbo_payee',
  `fund_code_id` int(11) DEFAULT NULL COMMENT 'acctg_fund_codes',
  `gl_account_id` int(11) DEFAULT NULL COMMENT 'acctg_account_general_ledgers',
  `sl_account_id` int(11) DEFAULT NULL COMMENT '	acctg_account_subsidiary_ledgers',
  `trans_no` varchar(40) DEFAULT NULL,
  `trans_type` varchar(40) NOT NULL DEFAULT 'Purchase Order',
  `trans_id` int(11) DEFAULT NULL,
  `responsibility_center` text DEFAULT NULL,
  `items` text DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL COMMENT 'gso_unit_of_measurements',
  `amount` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `vat_type` varchar(20) NOT NULL DEFAULT 'Vatable',
  `ewt_id` int(11) DEFAULT NULL COMMENT 'expanded_withholding_taxes',
  `ewt_amount` double DEFAULT NULL,
  `evat_id` int(11) DEFAULT NULL COMMENT 'expanded_vatable_taxes',
  `evat_amount` double DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `disapproved_at` timestamp NULL DEFAULT NULL,
  `disapproved_by` int(11) DEFAULT NULL,
  `disapproved_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY(id)
);

ALTER TABLE `hr_income_deduction_type` CHANGE `hridt_type` `hridt_type` INT(11) NULL DEFAULT NULL COMMENT '0 = deduction, 1 = income, 2 = gov share, 3 = gov deduction';
ALTER TABLE `hr_income_and_deduction` CHANGE `hridt_type` `hridt_type` INT(11) NULL DEFAULT NULL COMMENT '0 = deduction, 1 = income, 2 = gov share, 3 = gov deduction';