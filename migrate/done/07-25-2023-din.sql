ALTER TABLE `gso_property_accountabilities` ADD `is_depreciative` BOOLEAN NOT NULL DEFAULT FALSE AFTER `remarks`;

TRUNCATE TABLE gso_property_types;

INSERT INTO `gso_property_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'EQ', 'Equipment', 'EQ - Equipment Fixed Asset', '2023-05-30 23:28:14', 1, NULL, NULL, 1),
(2, 'LAND', 'Land', 'LAND - Land Fixed Asset', '2023-05-30 23:28:14', 1, NULL, NULL, 1),
(3, 'BDLG', 'Building', 'BDLG - Building Fixed Asset', '2023-05-30 23:28:14', 1, NULL, NULL, 1),
(4, 'VEH', 'Vehicle', 'VEH - Vehicle Fixed Asset', '2023-05-30 23:28:14', 1, NULL, NULL, 1),
(5, 'MACH', 'Machine', 'MACH - Machine Fixed Asset', '2023-05-30 23:28:14', 1, NULL, NULL, 1);

CREATE TABLE IF NOT EXISTS `gso_depreciation_types` (
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

INSERT INTO `gso_depreciation_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'Straight', 'Straight Depreciation', 'Straight Depreciation', '2023-07-25 03:18:34', 1, NULL, NULL, 1),
(2, 'Deminishing', 'Deminishing Balance', 'Deminishing Balance Depreciation', '2023-07-25 03:18:34', 1, NULL, NULL, 1),
(3, 'Double Deminishing', 'Double Deminishing Balance', 'Double Deminishing Balance Depreciation', '2023-07-25 03:18:34', 1, NULL, NULL, 1),
(4, 'SYD', 'SYD Depreciation', 'SYD Depreciation', '2023-07-25 03:18:34', 1, NULL, NULL, 1),
(5, 'UPM', 'Units of Production Method', 'Units of Production Method Depreciation', '2023-07-25 03:18:34', 1, NULL, NULL, 1);

ALTER TABLE `gso_depreciation_types`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_depreciation_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

ALTER TABLE `gso_property_accountabilities` 
ADD COLUMN IF NOT EXISTS `model` VARCHAR(100) NULL DEFAULT NULL AFTER `remarks`, 
ADD COLUMN IF NOT EXISTS `engine_no` VARCHAR(100) NULL DEFAULT NULL AFTER `model`, 
ADD COLUMN IF NOT EXISTS `mv_file_no` VARCHAR(100) NULL DEFAULT NULL AFTER `engine_no`, 
ADD COLUMN IF NOT EXISTS `chasis_no` VARCHAR(100) NULL DEFAULT NULL AFTER `mv_file_no`, 
ADD COLUMN IF NOT EXISTS `plate_no` VARCHAR(100) NULL DEFAULT NULL AFTER `chasis_no`, 
ADD COLUMN IF NOT EXISTS `depreciation_type_id` INT NULL DEFAULT NULL AFTER `plate_no`, 
ADD COLUMN IF NOT EXISTS `salvage_value` DOUBLE NULL DEFAULT NULL AFTER `depreciation_type_id`, 
ADD COLUMN IF NOT EXISTS `monthly_depreciation` DOUBLE NULL DEFAULT NULL AFTER `salvage_value`;


ALTER TABLE `gso_departmental_requests` ADD COLUMN IF NOT EXISTS `approved_counter` INT NOT NULL DEFAULT '1' AFTER `sent_by`;