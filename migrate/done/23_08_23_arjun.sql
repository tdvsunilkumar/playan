ALTER TABLE `ho_request_permit` CHANGE `is_free` `is_free` INT(11) NULL DEFAULT '0' COMMENT '0 = not free, 1 = free';
ALTER TABLE `eng_building_permit_fees_division` ADD `ebpfd_feessetid` INT(11) NOT NULL DEFAULT '0' AFTER `ebpfc_id`;

