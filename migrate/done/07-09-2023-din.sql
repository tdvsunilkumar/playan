ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `pr_status` VARCHAR(40) NOT NULL DEFAULT 'draft' AFTER `status`;

CREATE TABLE IF NOT EXISTS `gso_purchase_requests_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_purchase_requests',
  `item_description` text DEFAULT NULL COMMENT 'item description',
  `uom_id` int(10) UNSIGNED NOT NULL COMMENT 'gso_unit_of_measurements',
  `remarks` text DEFAULT NULL,
  `quantity_pr` double NOT NULL DEFAULT 0,
  `request_unit_price` double NOT NULL DEFAULT 0,
  `request_total_price` double NOT NULL DEFAULT 0,
  `status` varchar(40) NOT NULL DEFAULT 'draft',
  `sent_at` timestamp NULL DEFAULT NULL,
  `sent_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `gso_purchase_requests_lines`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `gso_purchase_requests_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;