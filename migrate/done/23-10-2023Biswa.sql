ALTER TABLE `bplo_business_psic_history` ADD `app_code` INT(11) NOT NULL AFTER `id`;
ALTER TABLE `bplo_business_measure_pax_history` ADD `app_code` INT(11) NOT NULL AFTER `busn_id`;