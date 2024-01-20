ALTER TABLE `acctg_expanded_vatable_taxes` ADD `gl_account_id` INT NOT NULL AFTER `percentage`;
ALTER TABLE `acctg_expanded_withholding_taxes` ADD `gl_account_id` INT NOT NULL AFTER `percentage`;
ALTER TABLE `acctg_fund_codes` ADD `is_payroll` INT NOT NULL AFTER `description`;
ALTER TABLE `acctg_disburse_types` ADD `is_payroll` INT NOT NULL AFTER `description`;