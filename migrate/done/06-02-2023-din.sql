DROP TABLE IF EXISTS `acctg_payables`;
DROP TABLE IF EXISTS `acctg_vouchers`;
DROP TABLE IF EXISTS `acctg_disbursements`;

CREATE TABLE IF NOT EXISTS `acctg_disbursements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` int(11) DEFAULT NULL COMMENT 'acctg_vouchers',
  `gl_account_id` int(11) DEFAULT NULL COMMENT 'acctg_account_general_ledgers',
  `sl_account_id` int(11) DEFAULT NULL COMMENT '	acctg_account_subsidiary_ledgers',
  `payment_type_id` int(11) DEFAULT NULL COMMENT 'acctg_payment_types',
  `payment_date` date NOT NULL,
  `amount` double DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `bank_account_name` varchar(100) DEFAULT NULL,
  `cheque_no` varchar(100) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `reference_no` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acctg_disbursements`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acctg_disbursements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `acctg_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payee_id` int(10) UNSIGNED NOT NULL COMMENT 'cbo_payee',
  `voucher_no` varchar(40) NOT NULL,
  `remarks` text DEFAULT NULL,
  `total_payables` double DEFAULT NULL,
  `total_ewt` double DEFAULT NULL,
  `total_evat` double DEFAULT NULL,
  `total_disbursement` double DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acctg_vouchers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acctg_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `acctg_payables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` int(11) DEFAULT NULL COMMENT 'acctg_vouchers',
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
  `vat_type` varchar(10) NOT NULL DEFAULT 'Vatable',
  `ewt_id` int(11) DEFAULT NULL COMMENT 'expanded_withholding_taxes',
  `ewt_amount` double DEFAULT NULL,
  `evat_id` int(11) DEFAULT NULL COMMENT 'expanded_vatable_taxes',
  `evat_amount` double DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acctg_payables`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acctg_payables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `acctg_payables` ADD COLUMN IF NOT EXISTS `responsibility_center` TEXT NULL DEFAULT NULL AFTER `trans_id`;
ALTER TABLE `acctg_account_general_ledgers` ADD COLUMN IF NOT EXISTS `is_payable` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_with_sl`;
ALTER TABLE `bac_rfqs` ADD COLUMN IF NOT EXISTS `fund_code_id` INT NULL DEFAULT NULL AFTER `control_no`;
ALTER TABLE `bac_rfqs` CHANGE `fund_code_id` `fund_code_id` INT(11) NULL DEFAULT NULL COMMENT 'acctg_fund_codes';
ALTER TABLE `acctg_payables` ADD COLUMN IF NOT EXISTS `fund_code_id` INT NULL DEFAULT NULL AFTER `voucher_id`;
ALTER TABLE `acctg_payables` CHANGE `fund_code_id` `fund_code_id` INT(11) NULL DEFAULT NULL COMMENT 'acctg_fund_codes';
ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `fund_code_id` INT NULL DEFAULT NULL COMMENT 'acctg_fund_codes' AFTER `payee_id`;