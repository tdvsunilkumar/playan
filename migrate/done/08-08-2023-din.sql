CREATE TABLE IF NOT EXISTS `messages_types` (
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

INSERT INTO `messages_types` (`id`, `code`, `name`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'messaging', 'Messaging', 'Messaging', '2023-08-08 03:12:37', 1, NULL, NULL, 1);

ALTER TABLE `messages_types`
  ADD PRIMARY KEY KEY IF NOT EXISTS (`id`);

ALTER TABLE `messages_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_type_id` int(10) UNSIGNED NOT NULL COMMENT 'messages_types',
  `messages` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `messages`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `outbox` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` int(10) UNSIGNED NOT NULL COMMENT 'messages',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'users',
  `msisdn` varchar(20) NOT NULL,
  `smsc` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `outbox`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `outbox`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `prefixes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `access` text DEFAULT NULL,
  `network` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `prefixes` (`id`, `access`, `network`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, '0895,0896,0897,0898,0991,0992,0993,0994', 'dito', '2011-09-07 12:50:08', 1, NULL, NULL, 1),
(2, '0817,0905,0906,0915,0917,0926,0927,0935,0936,0937,0945,0953,0954,0955,0956,0965,0966,0967,0975,0976,0977,0978,0979,0994,0995,0996,0997,0916', 'globe', '2021-01-07 20:47:47', 1, NULL, NULL, 1),
(3, '0922,0923,0924,0925,0931,0932,0933,0934,0940,0941,0942,0943,0944,0973,0974', 'sun', '2021-01-07 20:47:47', 1, NULL, NULL, 1),
(4, '0813,0907,0908,0909,0910,0911,0912,0913,0914,0918,0919,0920,0921,0928,0929,0930,0938,0939,0946,0947,0948,0949,0950,0951,0961,0963,0968,0970,0981,0989,0992,0998,0999', 'smart', '2021-01-07 20:47:47', 1, NULL, NULL, 1);

ALTER TABLE `prefixes`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `prefixes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
