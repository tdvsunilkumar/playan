ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_color` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_consistency` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_rbc` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_wbc` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_bacteria` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_fat_glob` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_parasite` varchar(30) NULL;
ALTER TABLE `ho_fecalysis` MODIFY COLUMN IF EXISTS `fec_others` varchar(30) NULL;

ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_color` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_appearance` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_leukocytes` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_nitrite` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_urobilinogen` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_protein` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_reaction` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_blood` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_sg` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_ketones` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_bilirubin` varchar(30) NULL;
ALTER TABLE `ho_urinalysis` MODIFY COLUMN IF EXISTS `urin_glucose` varchar(30) NULL;

ALTER TABLE `ho_pregnancy` MODIFY COLUMN IF EXISTS `pt_specimen` varchar(30) NULL;
ALTER TABLE `ho_pregnancy` MODIFY COLUMN IF EXISTS `pt_brand_lot` varchar(30) NULL;
ALTER TABLE `ho_pregnancy` MODIFY COLUMN IF EXISTS `pt_expiry` varchar(30) NULL;
ALTER TABLE `ho_pregnancy` MODIFY COLUMN IF EXISTS `pt_result` varchar(30) NULL;