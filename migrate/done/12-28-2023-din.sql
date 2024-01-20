ALTER TABLE `gso_project_procurement_management_plans` ADD COLUMN IF NOT EXISTS `budget_category_id` INT NULL DEFAULT NULL COMMENT 'cbo_budget_categories' AFTER `department_id`;
ALTER TABLE `gso_departmental_requests` ADD COLUMN IF NOT EXISTS `budget_category_id` INT NULL DEFAULT NULL COMMENT 'cbo_budget_categories' AFTER `designation_id`;
ALTER TABLE `cbo_allotment_obligations` ADD COLUMN IF NOT EXISTS `budget_category_id` INT NULL DEFAULT NULL COMMENT 'cbo_budget_categories' AFTER `division_id`;