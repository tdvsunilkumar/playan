CREATE TABLE IF NOT EXISTS `gso_items_conversions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_items',
  `based_uom` int(10) UNSIGNED NOT NULL COMMENT 'gso_unit_of_measurements',
  `based_quantity` double DEFAULT NULL,
  `conversion_uom` int(10) UNSIGNED NOT NULL COMMENT 'gso_unit_of_measurements',
  `conversion_quantity` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_items_conversions`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_items_conversions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;