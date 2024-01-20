ALTER TABLE `bfp_application_forms` ADD `bff_document` TEXT NULL DEFAULT NULL AFTER `bff_remarks`;
ALTER TABLE `bfp_certificates` ADD `bfpcert_document` TEXT NULL DEFAULT NULL AFTER `bfpcert_approved_date`;
