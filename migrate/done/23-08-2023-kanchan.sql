ALTER TABLE `ho_deceased_cert` CHANGE `or_no` `or_no` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'cto_cashier.or_no';

ALTER TABLE `ho_deceased_cert` CHANGE `transfer_location` `transfer_location` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `ho_deceased_cert` CHANGE `place_of_death_id` `place_of_death_id` INT(11) NULL DEFAULT NULL COMMENT 'barangays.id get brgy_name, mun_desc, prov_desc';

ALTER TABLE `ho_deceased_cert` CHANGE `is_approved` `is_approved` INT(11) NULL DEFAULT NULL COMMENT '0 = Pending, 1 - Approved';