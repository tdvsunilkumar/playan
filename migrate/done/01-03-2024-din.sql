ALTER TABLE `eco_rental_application` ADD COLUMN IF NOT EXISTS `discount_id` INT NULL DEFAULT NULL COMMENT 'eco_rental_discounts' AFTER `reception_class_value`;

CREATE TABLE IF NOT EXISTS `eco_rental_discounts` (
  `id` int(11) NOT NULL,
  `code` varchar(40) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `eco_rental_discounts` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, '.10', '10%', '10%', '2024-01-03 07:41:48', 1, NULL, NULL, 1),
(2, '.20', '20%', '20%', '2024-01-03 07:41:48', 1, NULL, NULL, 1),
(3, '.30', '30%', '30%', '2024-01-03 07:41:48', 1, NULL, NULL, 1),
(4, '.40', '40%', '40%', '2024-01-03 07:41:48', 1, NULL, NULL, 1),
(5, '.50', '50%', '50%', '2024-01-03 07:41:48', 1, NULL, NULL, 1),
(6, '.60', '60%', '60%', '2024-01-03 07:41:48', 1, NULL, NULL, 1),
(7, '.70', '70%', '70%', '2024-01-03 07:41:48', 1, NULL, NULL, 1);

ALTER TABLE `eco_rental_discounts`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `eco_rental_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;