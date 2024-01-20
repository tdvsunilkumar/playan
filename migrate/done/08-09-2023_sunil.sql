ALTER TABLE `rpt_property_machine_appraisals` ADD `created_against` BIGINT(20) NULL COMMENT 'This column used during subdivision of Machinery' AFTER `rpma_modified_by`;
