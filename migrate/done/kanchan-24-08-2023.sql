ALTER TABLE `ho_deceased_cert` CHANGE `brgy_id` `brgy_id` INT(11) NULL DEFAULT NULL COMMENT 'citizens.id get fullname';

ALTER TABLE `ho_deceased_cert` CHANGE `place_of_death_id` `place_of_death_id` INT(11) NULL COMMENT 'barangays.id get brgy_name, mun_desc, prov_desc';

ALTER TABLE `ho_deceased_cert` CHANGE `death_date` `death_date` DATETIME NULL DEFAULT NULL;

ALTER TABLE `ho_deceased_cert` CHANGE `transfer_add_id` `transfer_add_id` INT(11) NULL DEFAULT NULL COMMENT 'barangays.id get brgy_name, mun_desc';

ALTER TABLE `ho_deceased_cert` ADD `top_transaction_no` INT(11) NULL DEFAULT NULL AFTER `transfer_add_id`;

ALTER TABLE `ho_deceased_cert` ADD `trans_id` INT(20) NULL DEFAULT NULL AFTER `top_transaction_no`;

ALTER TABLE `ho_deceased_cert` CHANGE `cashierd_id` `cashierd_id` INT(11) NULL DEFAULT NULL COMMENT 'cto_cashier_details.id';

ALTER TABLE `ho_deceased_cert` CHANGE `cashier_id` `cashier_id` INT(11) NULL DEFAULT NULL COMMENT 'cto_cashier.id';

ALTER TABLE `ho_deceased_cert` CHANGE `cashier_id` `cashier_id` INT(11) NULL DEFAULT NULL COMMENT 'cto_cashier.id';

ALTER TABLE `ho_deceased_cert` CHANGE `or_amount` `or_amount` DOUBLE NULL DEFAULT NULL COMMENT 'Ref-Table: cto_cashier_details.tfc_amount';