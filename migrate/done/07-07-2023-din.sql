ALTER TABLE `gso_purchase_requests` CHANGE `departmental_request_id` `departmental_request_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'gso_departmental_requests';
ALTER TABLE `gso_purchase_requests` ADD COLUMN IF NOT EXISTS `allotment_id` INT NULL DEFAULT NULL COMMENT 'cbo_allotment_obligations' AFTER `departmental_request_id`;

ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `employee_id` INT NULL DEFAULT NULL COMMENT 'hr_employees' AFTER `division_id`, ADD COLUMN IF NOT EXISTS `designation_id` INT NULL DEFAULT NULL COMMENT 'hr_designations' AFTER `employee_id`;