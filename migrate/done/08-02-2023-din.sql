CREATE TABLE IF NOT EXISTS `gso_property_accountabilities_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_property_accountabilities',
  `acquired_date` date DEFAULT NULL,
  `acquired_by` int(11) DEFAULT NULL,
  `issued_by` int(11) DEFAULT NULL,
  `returned_date` date DEFAULT NULL,
  `returned_by` int(11) DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_property_accountabilities_history`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_property_accountabilities_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

ALTER TABLE `acctg_vouchers` ADD COLUMN IF NOT EXISTS `completed_at` TIMESTAMP NULL DEFAULT NULL AFTER `is_replenish`, 
ADD COLUMN IF NOT EXISTS `completed_by` INT NULL DEFAULT NULL AFTER `completed_at`;