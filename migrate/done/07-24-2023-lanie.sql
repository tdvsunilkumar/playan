ALTER TABLE `welfare_swsc_dependent` ADD COLUMN IF NOT EXISTS `wswscd_is_active` int DEFAULT 1 AFTER `wswscd_health_status`;
ALTER TABLE `welfare_social_welfare_sc_treatment` ADD COLUMN IF NOT EXISTS `wswsc_treatment_is_active` int DEFAULT 1 AFTER `wswsc_treatment_plan_timeframe`;

ALTER TABLE `welfare_policy_settings` ADD COLUMN IF NOT EXISTS `wps_note` varchar(200) NULL AFTER `wps_value`;
ALTER TABLE `welfare_policy_settings` ADD COLUMN IF NOT EXISTS `wps_is_active` int DEFAULT 1 AFTER `wps_note`;
