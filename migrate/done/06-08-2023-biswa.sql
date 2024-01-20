TRUNCATE `palayan`.`bplo_business_psic_req`;
TRUNCATE `palayan`.`bplo_business`;
ALTER TABLE `bplo_business` ADD `application_date` DATE NULL AFTER `is_final_assessment`;