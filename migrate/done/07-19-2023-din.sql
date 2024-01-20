CREATE TABLE IF NOT EXISTS `gso_property_categories` (
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

INSERT INTO `gso_property_categories` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'Procurement', 'Procurement', 'Depreciate', '2023-07-19 01:29:28', 1, NULL, NULL, 1),
(2, 'Donations', 'Donations', 'Depreciate', '2023-07-19 01:29:28', 1, NULL, NULL, 1),
(3, 'Land', 'Land', 'Appreciate', '2023-07-19 01:29:28', 1, NULL, NULL, 1);

ALTER TABLE `gso_property_categories`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_property_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

ALTER TABLE `gso_property_accountabilities` ADD COLUMN IF NOT EXISTS `property_category_id` INT NOT NULL DEFAULT '1' COMMENT 'gso_property_categories' AFTER `id`;

INSERT INTO `gso_purchase_request_types` (`id`, `code`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES (NULL, 'RM', 'Repairs & Maintenance', '2023-02-23 10:33:56', '1', NULL, NULL, '1');

ALTER TABLE `gso_purchase_request_types` ADD COLUMN IF NOT EXISTS `is_hidden` BOOLEAN NOT NULL DEFAULT FALSE AFTER `description`;

UPDATE `gso_purchase_request_types` SET `is_hidden` = '1' WHERE `gso_purchase_request_types`.`id` = 3;

ALTER TABLE `gso_unit_of_measurements` ADD COLUMN IF NOT EXISTS `codex` VARCHAR(40) NULL DEFAULT NULL AFTER `code`;

ALTER TABLE `cto_disburse` ADD COLUMN IF NOT EXISTS `disburse_no` VARCHAR(40) NULL DEFAULT NULL AFTER `control_no`;

ALTER TABLE `acctg_disbursements` ADD COLUMN IF NOT EXISTS `disburse_no` VARCHAR(40) NULL DEFAULT NULL AFTER `payment_type_id`;