ALTER TABLE `rpt_revision_year` ADD `has_tax_basic` INT(1) NULL DEFAULT NULL COMMENT 'with Basic Tax(with Basic Tax)' AFTER `rvy_revision_code`, ADD `has_tax_sef` INT(1) NULL DEFAULT NULL COMMENT 'with SEF[Special Education Fund](with Basic Tax)' AFTER `has_tax_basic`, ADD `has_tax_sh` INT(1) NULL DEFAULT NULL COMMENT 'with SHT[Socialize Housing Tax](with Basic Tax)' AFTER `has_tax_sef`;