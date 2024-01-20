
ALTER TABLE `bplo_business_permit_issuance` ADD `app_type_id` INT(1) NOT NULL COMMENT ' 1-New, 2-Renew, 3-Retire' AFTER `bpi_permit_no`;