ALTER TABLE `cto_disburse` ADD COLUMN IF NOT EXISTS `approved_counter` INT NOT NULL DEFAULT '1' AFTER `sent_by`;
ALTER TABLE `cto_replenish` ADD COLUMN IF NOT EXISTS `approved_counter` INT NOT NULL DEFAULT '1' AFTER `sent_by`;
ALTER TABLE `cto_disburse` ADD COLUMN IF NOT EXISTS `department_id` INT NULL DEFAULT NULL COMMENT 'acctg_departments' AFTER `payee_id`;
ALTER TABLE `cto_replenish` ADD COLUMN IF NOT EXISTS `department_id` INT NULL DEFAULT NULL COMMENT 'acctg_departments' AFTER `id`;