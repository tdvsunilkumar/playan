ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `with_pr` BOOLEAN NOT NULL DEFAULT TRUE AFTER `approval_designation`;
ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `approved_counter` INT NOT NULL DEFAULT '1' AFTER `sent_by`;