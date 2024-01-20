ALTER TABLE `cpdo_development_permits` ADD `auth_representative` VARCHAR(255) NULL AFTER `client_id`;
ALTER TABLE `cpdo_development_permits` CHANGE `barangay_id` `client_barangay_id` INT(11) NULL;
ALTER TABLE `cpdo_development_permits` CHANGE `cdp_email_address` `cdp_email_address` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'required email address, ref-table : client.client_id (if client have this details';
ALTER TABLE `cpdo_development_permits` CHANGE `porpose_of_application` `porpose_of_application` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `cpdo_development_permits` CHANGE `type_of_project` `type_of_project` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, CHANGE `project_location` `project_location` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `cpdo_development_permits` ADD `project_barangay_id` INT(11) NULL AFTER `project_location`;


ALTER TABLE `cpdo_development_permits` ADD `req_file` LONGTEXT NULL AFTER `cdp_total_amount`;