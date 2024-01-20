ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `fixed_asset_no` VARCHAR(40) NULL DEFAULT NULL AFTER `property_type_id`;

ALTER TABLE `gso_property_accountabilities` CHANGE `pr_id` `pr_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'gso_purchase_request_types';

CREATE TABLE IF NOT EXISTS `gso_pre_repair_inspection_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_property_accountabilities',
  `requested_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'hr_employees',
  `requested_date` date DEFAULT NULL,
  `repair_no` varchar(40) DEFAULT NULL,
  `issues` text DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_counter` int(11) NOT NULL DEFAULT 1,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` text DEFAULT NULL,
  `disapproved_at` timestamp NULL DEFAULT NULL,
  `disapproved_by` int(10) UNSIGNED DEFAULT NULL,
  `disapproved_remarks` text DEFAULT NULL,
  `is_inspected` tinyint(1) NOT NULL DEFAULT 0,
  `inspected_at` timestamp NULL DEFAULT NULL,
  `inspected_by` text DEFAULT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT 0,
  `checked_at` timestamp NULL DEFAULT NULL,
  `checked_by` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_pre_repair_inspection_requests`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_pre_repair_inspection_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `gso_pre_repair_inspection_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `repair_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_pre_repair_inspection_requests',
  `item_id` int(11) DEFAULT NULL COMMENT 'gso_items',
  `uom_id` int(11) DEFAULT NULL COMMENT 'gso_unit_of_measurements',
  `quantity` double NOT NULL DEFAULT 0,
  `amount` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_pre_repair_inspection_items`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_pre_repair_inspection_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `gso_pre_repair_inspection_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `repair_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_pre_repair_inspection_requests',
  `requested_date` date DEFAULT NULL,
  `concerns` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_pre_repair_inspection_history`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_pre_repair_inspection_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;