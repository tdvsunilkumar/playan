ALTER TABLE ho_issuances RENAME COLUMN issuance_item_name to item_id;
ALTER TABLE ho_issuances MODIFY item_id int; 
ALTER TABLE ho_issuances MODIFY issuance_uom int;
ALTER TABLE ho_issuances RENAME COLUMN receiver_brgy to brgy_id;
ALTER TABLE ho_issuances MODIFY brgy_id VARCHAR(10); 
ALTER TABLE ho_issuances RENAME COLUMN receiver_name to receiver_id;


