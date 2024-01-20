ALTER TABLE `ho_lab_requests` MODIFY COLUMN IF EXISTS `lab_reg_date` DATE NULL AFTER `lab_req_year`;
ALTER TABLE `ho_lab_requests` ADD COLUMN IF NOT EXISTS `lab_is_posted` INT DEFAULT(0) AFTER `is_active`;
