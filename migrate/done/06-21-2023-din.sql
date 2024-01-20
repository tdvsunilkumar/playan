CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logs` varchar(100) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `entity` varchar(255) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `audit_logs`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `gso_project_procurement_management_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_departments',
  `control_no` varchar(40) NOT NULL,
  `budget_year` year(4) NOT NULL,
  `remarks` text DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
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

CREATE TABLE IF NOT EXISTS `gso_project_procurement_management_plans_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ppmp_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_departments',
  `gl_account_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_account_general_ledgers',
  `item_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_items',
  `uom_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_unit_of_measurements',
  `quantity` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_project_procurement_management_plans`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_project_procurement_management_plans_details`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_project_procurement_management_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `gso_project_procurement_management_plans_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `gso_project_procurement_management_plans` CHANGE `budget_year` `budget_year` YEAR(4) NULL DEFAULT NULL;

ALTER TABLE `gso_project_procurement_management_plans_details` CHANGE `gl_account_id` `gl_account_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'acctg_account_general_ledgers', CHANGE `item_id` `item_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'gso_items', CHANGE `uom_id` `uom_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'gso_unit_of_measurements';
