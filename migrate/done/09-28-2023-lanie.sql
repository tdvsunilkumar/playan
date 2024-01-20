CREATE TABLE `eco_housing_application_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `housing_application_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
    PRIMARY KEY(id)
);
ALTER TABLE `eco_housing_application` ADD `terms_condition` VARCHAR(225) NULL AFTER `initial_monthly`;
ALTER TABLE `eco_housing_application` ADD `penalty` int NULL AFTER `initial_monthly`;