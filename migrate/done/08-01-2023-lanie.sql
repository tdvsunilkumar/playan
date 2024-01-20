CREATE TABLE `welfare_tcm_minors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wtcm_id` int(11) NOT NULL,
  `cit_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wtcmm_is_active` int(11) DEFAULT 1 NULL,
  PRIMARY KEY(id)
) ;

ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_status` int DEFAULT 0 COMMENT '1 = traveling alone; 2 = with companion';
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_validity` int DEFAULT 0 COMMENT '1 = 1 year; 2 = 2 years';
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_minor_address` varchar(200) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_adoption_no` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_foster_liscense` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_foster_validity` date NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_father_cit_id` int DEFAULT 0 COMMENT 'ref-Table: citizens.cit_id';
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_mother_cit_id` int DEFAULT 0 COMMENT 'ref-Table: citizens.cit_id';
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_father_id_num` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_mother_id_num` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_companion_relation` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_companion_contact` int DEFAULT 0 COMMENT 'ref-Table: citizens.cit_id';
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_sponsor` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_sponsor_relation` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_sponsor_contact` varchar(100) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_sponsor_address` varchar(200) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_destination` varchar(200) NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_travel_from` date NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_travel_to` date NULL;
ALTER TABLE `welfare_travel_clearance_minor` ADD COLUMN IF NOT EXISTS `wtcm_reason_cant_accompany` text NULL;
