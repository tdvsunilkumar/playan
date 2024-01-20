ALTER TABLE ho_hematology ADD COLUMN IF NOT EXISTS `hema_is_posted` INT NULL AFTER `hema_remarks`;
ALTER TABLE ho_pregnancy ADD COLUMN IF NOT EXISTS `pt_is_posted` INT NULL AFTER `pt_remarks`;

ALTER TABLE ho_lab_fees ADD COLUMN IF NOT EXISTS `tfoc_id` VARCHAR(20) NULL AFTER `service_id`;
ALTER TABLE ho_lab_fees ADD COLUMN IF NOT EXISTS `agl_account_id` VARCHAR(20) NULL AFTER `tfoc_id`;
ALTER TABLE ho_lab_fees ADD COLUMN IF NOT EXISTS `sl_id` VARCHAR(20) NULL AFTER `agl_account_id`;
ALTER TABLE ho_lab_fees ADD COLUMN IF NOT EXISTS `top_transaction_type_id` VARCHAR(20) NULL AFTER `sl_id`;