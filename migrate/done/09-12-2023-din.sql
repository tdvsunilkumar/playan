CREATE TABLE IF NOT EXISTS `sms_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` int(10) UNSIGNED NOT NULL COMMENT 'sms_messages',
  `cron_job_id` int(10) UNSIGNED NOT NULL COMMENT 'cron_jobs',
  `schedule` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `sms_schedules`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `sms_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE TABLE IF NOT EXISTS `cron_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `slugs` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `response` text DEFAULT NULL,
  `is_repeated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY IF NOT EXISTS (`id`);

ALTER TABLE `cron_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;