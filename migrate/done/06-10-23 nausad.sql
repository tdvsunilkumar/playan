ALTER TABLE ho_inventory_posting ADD base_unit_cost decimal(10,2) AFTER cip_total_cost;
ALTER TABLE ho_inventory_posting ADD base_total_cost decimal(10,2) AFTER base_unit_cost;
ALTER TABLE ho_inventory_breakdowns ADD hrb_base_unit_cost decimal(10,2) AFTER hrb_balance_qty;
ALTER TABLE ho_inventory_breakdowns ADD hrb_base_total_cost decimal(10,2) AFTER hrb_base_unit_cost;
