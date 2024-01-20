ALTER TABLE `ho_hematology` ADD `med_tech_position` VARCHAR(100) NOT NULL AFTER `hema_is_active`, ADD `health_officer_position` VARCHAR(100) NOT NULL AFTER `med_tech_position`;
ALTER TABLE `ho_serology` ADD `med_tech_position` VARCHAR(100) NOT NULL AFTER `ser_is_posted`, ADD `health_officer_position` VARCHAR(100) NOT NULL AFTER `med_tech_position`;
ALTER TABLE `ho_fecalysis` ADD `med_tech_position` VARCHAR(100) NOT NULL AFTER `fec_others`, ADD `health_officer_position` VARCHAR(100) NOT NULL AFTER `med_tech_position`;
ALTER TABLE `ho_urinalysis` ADD `med_tech_position` VARCHAR(100) NOT NULL AFTER `urin_remarks`, ADD `health_officer_position` VARCHAR(100) NOT NULL AFTER `med_tech_position`;
ALTER TABLE `ho_pregnancy` ADD `med_tech_position` VARCHAR(100) NOT NULL AFTER `pt_remarks`, ADD `health_officer_position` VARCHAR(100) NOT NULL AFTER `med_tech_position`;
