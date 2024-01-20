ALTER TABLE `acctg_account_general_ledgers` ADD COLUMN IF NOT EXISTS `is_payment` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_with_sl`;

UPDATE acctg_account_general_ledgers SET is_payment = 1 WHERE code IN('10101010', '10101020', '10102010', '10102020', '10103010', '10103020', '10201010', '10201020');

CREATE TABLE IF NOT EXISTS `acctg_trial_balance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_vouchers',
  `payee_id` int(10) UNSIGNED NOT NULL COMMENT 'cbo_payee',
  `fund_code_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_fund_codes',
  `gl_account_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_account_general_ledgers',
  `sl_account_id` int(10) DEFAULT NULL COMMENT 'acctg_account_subsidiary_ledgers',
  `debit` double DEFAULT NULL,
  `credit` double DEFAULT NULL,
  `entity` varchar(40) DEFAULT NULL,
  `entity_id` text DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `posted_by` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acctg_trial_balance`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `acctg_trial_balance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;