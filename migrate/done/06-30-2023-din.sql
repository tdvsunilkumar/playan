CREATE TABLE IF NOT EXISTS `user_access_approval_approvers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_id` int(10) UNSIGNED NOT NULL COMMENT 'user_access_approval_settings',
  `department_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_departments',
  `primary_approvers` text DEFAULT NULL,
  `secondary_approvers` text DEFAULT NULL,
  `tertiary_approvers` text DEFAULT NULL,
  `quaternary_approvers` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `user_access_approval_approvers`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `user_access_approval_approvers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `user_access_approval_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL COMMENT 'menu_modules',
  `levels` double DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `user_access_approval_settings`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `user_access_approval_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
