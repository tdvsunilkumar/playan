ALTER TABLE ho_inventory_adjustment_details ADD is_parent tinyint AFTER id;
ALTER TABLE ho_inventory_adjustment_details ADD parent_id integer AFTER is_parent;