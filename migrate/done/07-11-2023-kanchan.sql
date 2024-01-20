ALTER TABLE `ho_hematology` ADD `esign_is_approved` INT(11) NOT NULL AFTER `health_officer_position`;

ALTER TABLE `ho_serology` ADD `esign_is_approved` INT(11) NOT NULL AFTER `health_officer_position`;

ALTER TABLE `ho_fecalysis` ADD `esign_is_approved` INT(11) NOT NULL AFTER `health_officer_position`;

ALTER TABLE `ho_urinalysis` ADD `esign_is_approved` INT(11) NOT NULL AFTER `health_officer`;

ALTER TABLE `ho_pregnancy` ADD `esign_is_approved` INT(11) NOT NULL AFTER `health_officer_position`;

ALTER TABLE `ho_lab_requests` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor Name ';