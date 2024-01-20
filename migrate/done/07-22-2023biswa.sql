UPDATE `bplo_business_psic`
SET `busp_no_units` = 0
WHERE `busp_no_units` IS NULL;

UPDATE `bplo_business_psic`
SET `busp_capital_investment` = 0.00
WHERE `busp_capital_investment` IS NULL;

UPDATE `bplo_business_psic`
SET `busp_essential` = 0.00
WHERE `busp_essential` IS NULL;

UPDATE `bplo_business_psic`
SET `busp_non_essential` = 0.00
WHERE `busp_non_essential` IS NULL;