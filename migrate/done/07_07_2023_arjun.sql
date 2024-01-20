ALTER TABLE `ho_hematology` CHANGE `hema_date` `hema_date` DATETIME NOT NULL;
ALTER TABLE `ho_serology` CHANGE `ser_date` `ser_date` DATETIME NOT NULL;
ALTER TABLE `ho_urinalysis` CHANGE `urin_date` `urin_date` DATETIME NOT NULL;
ALTER TABLE `ho_fecalysis` CHANGE `fec_date` `fec_date` DATETIME NOT NULL;
ALTER TABLE `ho_pregnancy` CHANGE `pt_date` `pt_date` DATETIME NOT NULL;