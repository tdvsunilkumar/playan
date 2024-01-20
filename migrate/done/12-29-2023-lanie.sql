CREATE TABLE `cto_type_of_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(225) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;

INSERT INTO `cto_type_of_charges` (`id`, `description`, `created_by`, `created_at`) VALUES (NULL, 'Internal Revenue Allotment', 1, CURRENT_TIMESTAMP());
INSERT INTO `cto_type_of_charges` (`id`, `description`, `created_by`, `created_at`) VALUES (NULL, 'Other Shares from National Tax Collection', 1, CURRENT_TIMESTAMP());
INSERT INTO `cto_type_of_charges` (`id`, `description`, `created_by`, `created_at`) VALUES (NULL, 'Grants and Donations', 1, CURRENT_TIMESTAMP());
INSERT INTO `cto_type_of_charges` (`id`, `description`, `created_by`, `created_at`) VALUES (NULL, 'Subsidy Income', 1, CURRENT_TIMESTAMP());
INSERT INTO `cto_type_of_charges` (`id`, `description`, `created_by`, `created_at`) VALUES (NULL, 'Extraordinary Gains and Premiums', 1, CURRENT_TIMESTAMP());
INSERT INTO `cto_type_of_charges` (`id`, `description`, `created_by`, `created_at`) VALUES (NULL, 'Inter - Local Transfers', 1, CURRENT_TIMESTAMP());