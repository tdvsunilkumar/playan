CREATE TABLE IF NOT EXISTS `sms_maskings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sms_maskings` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'LGU SMS', 'LGU SMS', 'LGU SMS', '2023-11-17 00:50:32', 1, NULL, NULL, 1),
(2, 'PALAYAN SMS', 'PALAYAN SMS', 'PALAYAN SMS', '2023-11-17 00:50:32', 1, NULL, NULL, 1);

ALTER TABLE `sms_maskings`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `sms_maskings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

CREATE TABLE IF NOT EXISTS `sms_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sms_actions` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'Application Login', 'Application Login', 'Application Login', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(2, 'Verify Application', 'Verify Application', 'Verify Application', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(3, 'Permit Issuance', 'Permit Issuance', 'Permit Issuance', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(4, 'Mark As Completed', 'Mark As Completed', 'Mark As Completed', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(5, 'Approve for Verification', 'Approve for Verification', 'Approve for Verification', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(6, 'Approve for Certification', 'Approve for Certification', 'Approve for Certification', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(7, 'Recommending Approval', 'Recommending Approval', 'Recommending Approval', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(8, 'Approved By', 'Approved By', 'Approved By', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(9, 'Zoning Officer Approval', 'Zoning Officer Approval', 'Zoning Officer Approval', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(10, 'Tax Order of Payment', 'Tax Order of Payment', 'Tax Order of Payment', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(11, 'Inspected By Approval', 'Inspected By Approval', 'Inspected By Approval', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(12, 'Prepared By', 'Prepared By', 'Prepared By', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(13, 'Final Assessment', 'Final Assessment', 'Final Assessment', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(14, 'Bill Now', 'Bill Now', 'Bill Now', '2023-11-16 08:41:38', 1, NULL, NULL, 1),
(15, 'Payment', 'Payment', 'Payment', '2023-11-16 08:41:38', 1, NULL, NULL, 1);

ALTER TABLE `sms_actions`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `sms_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

CREATE TABLE IF NOT EXISTS `sms_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL COMMENT 'menu_groups',
  `module_id` int(10) UNSIGNED NOT NULL COMMENT 'menu_modules',
  `sub_module_id` int(10) UNSIGNED NOT NULL COMMENT 'menu_sub_modules',
  `action_id` int(10) UNSIGNED NOT NULL COMMENT 'sms_actions',
  `type_id` int(10) UNSIGNED NOT NULL COMMENT 'sms_types',
  `application` text NOT NULL,
  `template` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `sms_templates`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `sms_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

CREATE TABLE IF NOT EXISTS `sms_server_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `masking_id` int(10) UNSIGNED NOT NULL COMMENT 'sms_maskings',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sms_server_settings` (`id`, `masking_id`, `is_enabled`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 1, 1, '2023-11-17 02:36:07', 1, NULL, NULL, 1);

ALTER TABLE `sms_server_settings`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `sms_server_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

CREATE TABLE IF NOT EXISTS `sms_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sms_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'Broadcast', 'Broadcast', 'Broadcast', '2023-11-16 08:46:07', 1, NULL, NULL, 1),
(2, 'OTP', 'OTP', 'OTP', '2023-11-16 08:46:07', 1, NULL, NULL, 1);

ALTER TABLE `sms_types`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `sms_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

ALTER TABLE `sms_settings` ADD COLUMN IF NOT EXISTS `type_id` INT NOT NULL COMMENT 'sms_types' AFTER `id`;