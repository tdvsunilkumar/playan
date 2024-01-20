ALTER TABLE cto_payment_or_setups ADD user_id integer AFTER id;
ALTER TABLE cto_payment_or_setups ADD or_field_id integer AFTER user_id;
ALTER TABLE cto_payment_or_setups ADD or_field_form varchar(255) AFTER or_field_id;