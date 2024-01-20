ALTER TABLE `gso_suppliers` ADD COLUMN IF NOT EXISTS `payee_id` INT NULL DEFAULT NULL AFTER `id`;

update gso_suppliers as supplier 
LEFT JOIN cbo_payee as payee ON supplier.id = payee.scp_id 
SET supplier.payee_id = payee.id 
WHERE payee.paye_type = 2;

ALTER TABLE `hr_employees` ADD COLUMN IF NOT EXISTS `payee_id` INT NULL DEFAULT NULL AFTER `id`;

update hr_employees as employee 
LEFT JOIN cbo_payee as payee ON employee.id = payee.hr_employee_id 
SET employee.payee_id = payee.id WHERE payee.paye_type = 1;

update acctg_payables as payables 
LEFT JOIN gso_purchase_orders as purchase ON payables.trans_no = purchase.purchase_order_no
LEFT JOIN gso_suppliers as supplier ON purchase.supplier_id = supplier.id
SET payables.payee_id = supplier.payee_id
WHERE payables.trans_type = 'Purchase Order';