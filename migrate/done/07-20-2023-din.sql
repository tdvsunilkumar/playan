ALTER TABLE `cbo_allotment_obligations_requests` 
ADD COLUMN IF NOT EXISTS `budget_officer_id` INT NULL DEFAULT NULL AFTER `status`, 
ADD COLUMN IF NOT EXISTS `budget_officer_designation` TEXT NULL DEFAULT NULL AFTER `bugdet_officer_id`, 
ADD COLUMN IF NOT EXISTS `treasurer_id` INT NULL DEFAULT NULL AFTER `budget_officer_designation`, 
ADD COLUMN IF NOT EXISTS `treasurer_designation` TEXT NULL DEFAULT NULL AFTER `treasurer_id`, 
ADD COLUMN IF NOT EXISTS `mayor_id` INT NULL DEFAULT NULL AFTER `treasurer_designation`, 
ADD COLUMN IF NOT EXISTS `mayor_designation` INT NULL DEFAULT NULL AFTER `mayor_id`;

ALTER TABLE `acctg_disbursements` ADD COLUMN IF NOT EXISTS `disburse_type_id` INT NOT NULL DEFAULT '1' AFTER `payment_type_id`;

CREATE TABLE IF NOT EXISTS `acctg_disburse_types` (
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

INSERT INTO `acctg_disburse_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'Procurement', 'Procurement', 'Procurement', '2023-07-20 06:00:44', 1, NULL, NULL, 1),
(2, 'Disbursement', 'Disbursement', 'Disbursement', '2023-07-20 06:00:44', 1, NULL, NULL, 1),
(3, 'Replenishment', 'Replenishment', 'Replenishment', '2023-07-20 06:00:44', 1, NULL, NULL, 1);

ALTER TABLE `acctg_disburse_types`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `acctg_disburse_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;