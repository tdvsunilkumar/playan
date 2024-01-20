CREATE TABLE IF NOT EXISTS `ho_medical_item_categories` (
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

INSERT INTO `ho_medical_item_categories` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'Medicine', 'Medicine', 'Medicine', '2023-11-16 03:47:00', 1, NULL, NULL, 1),
(2, 'Medical Supplies', 'Medical Supplies', 'Medical Supplies', '2023-11-16 03:47:00', 1, NULL, NULL, 1),
(3, 'Medical Equipment', 'Medical Equipment', 'Medical Equipment', '2023-11-16 03:47:00', 1, NULL, NULL, 1);

ALTER TABLE `ho_medical_item_categories`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `ho_medical_item_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

ALTER TABLE `gso_items` ADD COLUMN IF NOT EXISTS `medical_category_id` INT NULL DEFAULT NULL AFTER `purchase_type_id`;