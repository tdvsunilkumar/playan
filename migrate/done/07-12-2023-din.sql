ALTER TABLE `gso_project_procurement_management_plans` ADD COLUMN IF NOT EXISTS `budget_status` VARCHAR(40) NOT NULL DEFAULT 'draft' AFTER `status`;

ALTER TABLE `gso_project_procurement_management_plans` ADD COLUMN IF NOT EXISTS `budget_is_adjusted` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_adjusted`;

ALTER TABLE `gso_project_procurement_management_plans_details` ADD COLUMN IF NOT EXISTS `budget_quantity` DOUBLE NULL DEFAULT NULL AFTER `total_amount`, 
ADD COLUMN IF NOT EXISTS `budget_amount` DOUBLE NULL DEFAULT NULL AFTER `budget_quantity`, 
ADD COLUMN IF NOT EXISTS `budget_total_amount` DOUBLE NULL DEFAULT NULL AFTER `budget_amount`;

UPDATE `gso_project_procurement_management_plans_details` SET `budget_quantity` = `quantity`, `budget_amount` = `amount`, `budget_total_amount` = `total_amount`;

ALTER TABLE `gso_project_procurement_management_plans` ADD COLUMN IF NOT EXISTS `budget_total_amount` DOUBLE NULL DEFAULT NULL AFTER `total_amount`;

UPDATE `gso_project_procurement_management_plans` SET `budget_total_amount` = `total_amount`;


CREATE TABLE IF NOT EXISTS `cto_petty_cash` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_vouchers',
  `payee_id` text DEFAULT NULL COMMENT 'cbo_payee',
  `control_no` varchar(40) NOT NULL,
  `particulars` text DEFAULT NULL,
  `total_amount` double NOT NULL DEFAULT 0,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `disbursement_date` date DEFAULT NULL,
  `replenishment_date` date DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_by` int(10) UNSIGNED DEFAULT NULL,
  `disbursement_approved_at` timestamp NULL DEFAULT NULL,
  `disbursement_approved_by` text DEFAULT NULL,
  `disbursement_disapproved_at` timestamp NULL DEFAULT NULL,
  `disbursement_disapproved_by` int(10) UNSIGNED DEFAULT NULL,
  `disbursement_disapproved_remarks` text DEFAULT NULL,
  `replenishment_approved_at` timestamp NULL DEFAULT NULL,
  `replenishment_approved_by` text DEFAULT NULL,
  `replenishment_disapproved_at` timestamp NULL DEFAULT NULL,
  `replenishment_disapproved_by` int(10) UNSIGNED DEFAULT NULL,
  `replenishment_disapproved_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cto_petty_cash`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `cto_petty_cash`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `cto_petty_cash_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `petty_cash_id` int(10) UNSIGNED NOT NULL COMMENT 'cto_petty_cash',
  `obligation_id` int(10) UNSIGNED NOT NULL COMMENT 'cbo_allotment_obligations',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cto_petty_cash_details`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `cto_petty_cash_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `cto_petty_cash` ADD COLUMN IF NOT EXISTS `is_disbursed` BOOLEAN NOT NULL DEFAULT FALSE AFTER `disbursement_disapproved_remarks`;
ALTER TABLE `cto_petty_cash` ADD COLUMN IF NOT EXISTS `is_replenished` BOOLEAN NOT NULL DEFAULT FALSE AFTER `replenishment_disapproved_remarks`;