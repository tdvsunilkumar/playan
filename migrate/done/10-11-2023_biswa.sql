ALTER TABLE `cpdo_development_permits` ADD `is_synced` BOOLEAN NULL DEFAULT FALSE AFTER `is_active`;
ALTER TABLE `cpdo_development_permits` CHANGE `cdp_control_no` `cdp_control_no` VARCHAR(255) NOT NULL COMMENT 'generated by system';