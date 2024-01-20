ALTER TABLE `gso_pre_repair_inspection_requests` ADD COLUMN IF NOT EXISTS `inspected_remarks` TEXT NULL DEFAULT NULL AFTER `inspected_by`;

ALTER TABLE `gso_pre_repair_inspection_items` ADD COLUMN IF NOT EXISTS `remarks` TEXT NULL DEFAULT NULL AFTER `total_amount`;