ALTER TABLE `bplo_business_endorsement` ADD `force_mark_complete` INT(1) NOT NULL DEFAULT '0' AFTER `endorsing_dept_id`;
ALTER TABLE `bplo_endorsing_dept` ADD `force_mark_complete` INT(1) NOT NULL DEFAULT '0' AFTER `edept_status`;
