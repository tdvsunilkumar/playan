ALTER TABLE `ho_serology` ADD `med_tech` INT(11) NOT NULL DEFAULT '0' AFTER `hp_code`;
ALTER TABLE `ho_serology` ADD `health_officer` INT(11) NOT NULL DEFAULT '0' AFTER `med_tech`;

ALTER TABLE `ho_urinalysis` ADD `med_tech` INT(11) NULL DEFAULT '0' AFTER `hp_code`;
ALTER TABLE `ho_urinalysis` ADD `health_officer` INT(11) NOT NULL DEFAULT '0' AFTER `med_tech`;

ALTER TABLE `ho_fecalysis` ADD `med_tech` INT(11) NOT NULL DEFAULT '0' AFTER `hp_code`;
ALTER TABLE `ho_fecalysis` ADD `health_officer` INT(11) NOT NULL DEFAULT '0' AFTER `med_tech`;

ALTER TABLE `ho_pregnancy` ADD `med_tech` INT(11) NOT NULL DEFAULT '0' AFTER `hp_code`;
ALTER TABLE `ho_pregnancy` ADD `health_officer` INT(11) NOT NULL DEFAULT '0' AFTER `med_tech`;