ALTER TABLE `ho_lab_requests` ADD `req_phys` VARCHAR(255) NOT NULL AFTER `hp_code`;

ALTER TABLE `ho_hematology` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor Name';

ALTER TABLE `ho_serology` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor Name';

ALTER TABLE `ho_urinalysis` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor Name';

ALTER TABLE `ho_fecalysis` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor Name';

ALTER TABLE `ho_pregnancy` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'ref-table:hr_profile. hp_code. Get Doctor Name';

ALTER TABLE `ho_blood_sugar_tests` CHANGE `hp_code` `hp_code` INT(11) NULL DEFAULT NULL COMMENT 'hr_employees.id = Fetch fullname';