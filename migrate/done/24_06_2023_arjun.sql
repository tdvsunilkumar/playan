ALTER TABLE `enro_bplo_app_clearances` CHANGE `ebac_app_no` `ebac_app_no` VARCHAR(11) NOT NULL COMMENT 'xxxxxx application number representing incremental values from the system 1. written into 000001';
ALTER TABLE `enro_bplo_inspection_report` CHANGE `ebir_control_no` `ebir_control_no` VARCHAR(11) NOT NULL COMMENT 'Combination of (ebir_year - ebir_no) WHERE ebir_no=incremental value';
