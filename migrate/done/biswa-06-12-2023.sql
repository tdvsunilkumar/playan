ALTER TABLE `payment_history` ADD `cpdo_type` INT(11) NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `payment_history` ADD `occupancy_id` INT(11) NULL AFTER `busn_id`;

ALTER TABLE `payment_history` ADD `planning_id` INT(11) NULL AFTER `occupancy_id`;
ALTER TABLE `payment_history` ADD `eng_jobrequest_id` INT(11) NULL AFTER `planning_id`;