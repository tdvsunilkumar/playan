ALTER TABLE `pdo_bplo_endosements` ADD `pend_inspected_by` INT(11) NOT NULL AFTER `pend_remarks`, ADD `pend_inspected_status` INT(1) NOT NULL DEFAULT '0' AFTER `pend_inspected_by`, ADD `pend_inspected_officer_position` VARCHAR(70) NOT NULL AFTER `pend_inspected_status`;