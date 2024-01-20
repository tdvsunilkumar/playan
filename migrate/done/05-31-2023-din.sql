ALTER TABLE `gso_items` CHANGE `latest_cost_date` `latest_cost_date` DATE NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `gso_property_types` (
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


INSERT INTO `gso_property_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'FA', 'Fixed Assets', 'FA - Fixed Assets', '2023-05-31 01:28:14', 1, NULL, NULL, 1);


ALTER TABLE `gso_property_types`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `gso_property_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

CREATE TABLE IF NOT EXISTS `gso_property_accountabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_type_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_property_types',
  `property_no` varchar(40) NOT NULL,
  `pr_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'gso_item_types',
  `issuance_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_issuances',
  `issued_by` int(10) UNSIGNED NOT NULL COMMENT 'hr_employees',
  `received_by` int(10) UNSIGNED NOT NULL COMMENT 'hr_employees',
  `received_date` date DEFAULT NULL,
  `gl_account_id` int(10) UNSIGNED NOT NULL COMMENT 'acctg_account_general_ledgers',
  `item_type_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_item_types',
  `item_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_items',
  `uom_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_unit_of_measurements',
  `quantity` double DEFAULT NULL,
  `unit_cost` double DEFAULT NULL,
  `estimated_life_span` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'acquired',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `gso_property_accountabilities`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `gso_property_accountabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;


CREATE TABLE IF NOT EXISTS `gso_items_weighted` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `posting_line_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_purchase_orders_posting_lines',
  `item_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_items',
  `weighted_cost` double DEFAULT NULL,
  `weighted_cost_date` date DEFAULT NULL,
  `latest_cost` double DEFAULT NULL,
  `latest_cost_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_items_weighted`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `gso_items_weighted`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `acctg_account_subsidiary_ledgers` ADD `bank_id` INT NULL DEFAULT NULL AFTER `gl_account_id`;

CREATE TABLE `acctg_banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_no` varchar(40) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_account_name` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `acctg_banks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `acctg_banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;