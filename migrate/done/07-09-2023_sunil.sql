ALTER TABLE `rpt_properties` CHANGE `created_against_appraisal` `created_against_appraisal` VARCHAR(100) NULL DEFAULT NULL;
ALTER TABLE rpt_properties DROP CONSTRAINT rpt_properties_pk_id_foreign