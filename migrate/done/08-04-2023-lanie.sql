ALTER TABLE `welfare_travel_clearance_minor` CHANGE `cit_id` `cit_id` INT(11) NULL COMMENT 'ref-Table: citizens.cit_id';
ALTER TABLE `welfare_travel_clearance_minor` CHANGE `wtcm_date_interviewed` `wtcm_date_interviewed` DATE NULL;
ALTER TABLE `welfare_travel_clearance_minor` CHANGE `wtcm_child_status` `wtcm_child_status` INT(11) NULL COMMENT 'Legitimate, Illegitimate, Adopted/ Adoption Degree';

CREATE TABLE `welfare_tcm_destination` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `wtcm_id` int(11) NOT NULL,
    `wtcmd_place` VARCHAR(100) NOT NULL,
    `wtcmd_companion` VARCHAR(100) NOT NULL,
    `wtcmd_address` VARCHAR(100) NOT NULL,
    `wtcmd_contactno` VARCHAR(100) NOT NULL,
    `created_by` int(11) NOT NULL,
    `updated_by` int(11) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `is_active` int(11) DEFAULT 1 NULL,
    PRIMARY KEY(id)
) ;