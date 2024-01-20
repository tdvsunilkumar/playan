ALTER TABLE `bplo_business` ADD `is_synced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_active`;
ALTER TABLE `bplo_business_history` ADD `is_synced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `is_active`;
ALTER TABLE `bplo_business_psic` ADD `is_synced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `updated_by`;
ALTER TABLE `bplo_business_psic_req` ADD `is_synced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `updated_by`;
ALTER TABLE `bplo_business_measure_pax` ADD `is_synced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `updated_by`;