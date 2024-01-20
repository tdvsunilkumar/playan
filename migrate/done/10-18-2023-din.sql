-- ALTER TABLE `cbo_allotment_obligations` CHANGE `approved_at` `approved_at` TEXT NULL DEFAULT NULL;

ALTER TABLE `cbo_allotment_obligations` ADD `approved_datetime` TEXT NULL DEFAULT NULL AFTER `approved_by`;