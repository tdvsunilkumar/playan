ALTER TABLE `hr_employees` ADD `user_id` INT NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `users` ADD `updated_by` INT NULL DEFAULT NULL AFTER `updated_at`;