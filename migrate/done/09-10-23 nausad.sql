ALTER TABLE ho_inventory_posting ADD current_qty integer AFTER cip_item_name;
ALTER TABLE ho_inventory_breakdowns ADD hrb_current_qty integer AFTER hrb_qty_posted;