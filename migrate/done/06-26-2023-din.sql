ALTER TABLE `cbo_budget_breakdowns` ADD COLUMN IF NOT EXISTS `is_ppmp` BOOLEAN NOT NULL DEFAULT FALSE AFTER `quarterly_budget`;

CREATE TABLE IF NOT EXISTS `gso_project_procurement_management_plans_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ppmp_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_project_procurement_management_plans',
  `division_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_departments_divisions',
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_project_procurement_management_plans_status`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_project_procurement_management_plans_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;