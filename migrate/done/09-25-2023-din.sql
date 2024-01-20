CREATE TABLE IF NOT EXISTS `acctg_incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_vouchers',
  `fund_code_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_fund_codes',
  `gl_account_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_account_general_ledgers',
  `sl_account_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_account_subsidiary_ledgers',
  `trans_no` varchar(40) NOT NULL,
  `trans_type` varchar(40) NOT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `responsibility_center` text DEFAULT NULL,
  `items` text DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL COMMENT 'gso_unit_of_measurements',
  `amount` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `vat_type` varchar(40) NOT NULL DEFAULT 'Non-Vatable',
  `ewt_id` int(11) DEFAULT NULL COMMENT 'expanded_withholding_taxes',
  `ewt_amount` double DEFAULT NULL,
  `evat_id` int(11) DEFAULT NULL COMMENT 'expanded_vatable_taxes',
  `evat_amount` double DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `disapproved_at` timestamp NULL DEFAULT NULL,
  `disapproved_by` int(10) UNSIGNED DEFAULT NULL,
  `disapproved_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acctg_incomes`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `acctg_incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `collection_voucher_date` DATE NULL DEFAULT NULL AFTER `status`, 
ADD COLUMN IF NOT EXISTS `payables_voucher_date` DATE NULL DEFAULT NULL AFTER `collection_voucher_date`, 
ADD COLUMN IF NOT EXISTS `cash_voucher_date` DATE NULL DEFAULT NULL AFTER `payables_voucher_date`, 
ADD COLUMN IF NOT EXISTS `cheque_voucher_date` DATE NULL DEFAULT NULL AFTER `cash_voucher_date`, 
ADD COLUMN IF NOT EXISTS `others_voucher_date` DATE NULL DEFAULT NULL AFTER `cheque_voucher_date`;


ALTER TABLE `eco_cemetery_application` ADD COLUMN IF NOT EXISTS `top_transaction_id` INT NULL DEFAULT NULL AFTER `remaining_amount`;