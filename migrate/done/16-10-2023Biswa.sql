ALTER TABLE `ip_registration` CHANGE `remarks` `remarks` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Remarks';
ALTER TABLE `ip_exclusion` ADD `status` BOOLEAN NULL DEFAULT TRUE AFTER `remarks`;
ALTER TABLE `ip_exclusion` CHANGE `remarks` `remarks` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Remarks';