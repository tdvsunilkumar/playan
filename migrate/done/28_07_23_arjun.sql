ALTER TABLE `ho_hematology` ADD `doc_json` TEXT NULL AFTER `health_officer_position`;
ALTER TABLE `ho_serology` ADD `doc_json` TEXT NULL AFTER `health_officer_position`;
ALTER TABLE `ho_urinalysis` ADD `doc_json` TEXT NULL AFTER `health_officer_position`;
ALTER TABLE `ho_fecalysis` ADD `doc_json` TEXT NULL AFTER `health_officer_position`;
ALTER TABLE `ho_pregnancy` ADD `doc_json` TEXT NULL AFTER `health_officer_position`;