ALTER TABLE `eco_housing_application` CHANGE `terms_date_from` `terms_date_from` DATE NULL;
ALTER TABLE `eco_housing_application` CHANGE `month_terms` `month_terms` INT(11) NULL, CHANGE `terms_date_to` `terms_date_to` DATE NULL;
ALTER TABLE `eco_housing_application` ADD `civil_status` INT NOT NULL AFTER `gender`;