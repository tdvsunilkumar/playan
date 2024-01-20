ALTER TABLE ho_lab_requests ADD COLUMN IF NOT EXISTS `top_transaction_no` VARCHAR(20) NULL AFTER `cit_id`;

ALTER TABLE ho_serology ADD COLUMN IF NOT EXISTS `ser_is_posted` INT NULL AFTER `ser_remarks`;
ALTER TABLE ho_fecalysis ADD COLUMN IF NOT EXISTS `fec_is_posted` INT NULL AFTER `fec_others`;
ALTER TABLE ho_urinalysis ADD COLUMN IF NOT EXISTS `urin_is_posted` INT NULL AFTER `urin_remarks`;
