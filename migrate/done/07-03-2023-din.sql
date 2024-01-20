ALTER TABLE `gso_project_procurement_management_plans` CHANGE `approved_by` `approved_by` TEXT NULL DEFAULT NULL;
ALTER TABLE `gso_project_procurement_management_plans` ADD COLUMN IF NOT EXISTS `approved_counter` INT NOT NULL DEFAULT '1' AFTER `sent_by`;
ALTER TABLE `user_access_approval_settings` ADD COLUMN IF NOT EXISTS `sub_module_id` INT NULL DEFAULT NULL COMMENT 'menu_sub_modules' AFTER `module_id`;