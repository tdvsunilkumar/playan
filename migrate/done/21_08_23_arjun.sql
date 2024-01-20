ALTER TABLE `ho_request_permit` ADD `control_no` VARCHAR(20) NOT NULL COMMENT 'format [year-0001] 2023-0001 incremental and resets every year ' AFTER `request_date`;
ALTER TABLE `ho_request_permit` CHANGE `brgy_id` `brgy_id` VARCHAR(11) NOT NULL DEFAULT '0';
ALTER TABLE `ho_request_permit` CHANGE `request_amount` `request_amount` DOUBLE(8,2) UNSIGNED NOT NULL DEFAULT '0.000';