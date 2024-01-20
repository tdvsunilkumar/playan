CREATE TABLE IF NOT EXISTS `cbo_obligation_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(40) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cbo_obligation_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'PROC', 'Procurement', 'PROC - Procurement', '2023-07-05 13:08:05', 1, NULL, NULL, 1),
(2, 'REIM', 'Reimbursement', 'REIM - Reimbursement', '2023-07-05 13:08:05', 1, NULL, NULL, 1),
(3, 'CA', 'Cash Advance', 'CA - Cash Advance', '2023-07-05 13:08:05', 1, NULL, NULL, 1),
(4, 'SAL', 'Payroll', 'SAL - Payroll', '2023-07-05 13:08:05', 1, NULL, NULL, 1),
(5, 'AICS', 'Social Welfare', 'AICS - Social Welfare', '2023-07-05 13:08:05', '1', NULL, NULL, '1');

ALTER TABLE `cbo_obligation_types`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `cbo_obligation_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `obligation_type_id` INT NULL DEFAULT NULL COMMENT 'cbo_obligation_types' AFTER `id`;

ALTER TABLE `cbo_obligation_types` ADD COLUMN IF NOT EXISTS `fund_code_id` INT NULL DEFAULT NULL COMMENT 'acctg_fund_codes' AFTER `id`, ADD COLUMN IF NOT EXISTS `gl_account_id` INT NULL DEFAULT NULL COMMENT 'acctg_account_general_ledgers' AFTER `fund_code_id`;