ALTER TABLE `welfare_pwd_application_form` ADD COLUMN IF NOT EXISTS `loc_local_code` VARCHAR(10) NULL COMMENT 'Ref-Table: rpt_locality.loc_local_code WHERE department=3' AFTER `wpaf_application_type`;

ALTER TABLE `welfare_pwd_application_form` ADD COLUMN IF NOT EXISTS `barangay_uacs_code` VARCHAR(5) NULL COMMENT 'Ref-Table: barangay.uacs_code WHERE id=wpaf_brgy_id' AFTER `loc_local_code`;

ALTER TABLE `welfare_pwd_application_form` ADD COLUMN IF NOT EXISTS `barangay_pwd_no` VARCHAR(10) NULL COMMENT 'N= Series No(Incremental)' AFTER `barangay_uacs_code`;

