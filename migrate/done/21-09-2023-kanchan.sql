ALTER TABLE `ho_medical_certificates` ADD `med_cert_type` INT(1) NOT NULL DEFAULT '0' AFTER `cit_age`;
ALTER TABLE `ho_medical_certificates` ADD `incedent_nature` VARCHAR(200) NULL DEFAULT NULL AFTER `med_cert_type`;
ALTER TABLE `ho_medical_certificates` ADD `incedent_place` INT(11) NULL DEFAULT NULL AFTER `incedent_nature`;
ALTER TABLE `ho_medical_certificates` ADD `incedent_datetime` DATE NULL DEFAULT NULL AFTER `incedent_place`;