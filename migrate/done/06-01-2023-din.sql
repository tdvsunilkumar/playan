ALTER TABLE `acctg_disbursements` CHANGE `payment_date` `payment_date` DATE NOT NULL;
ALTER TABLE `acctg_disbursements` CHANGE `cheque_date` `cheque_date` DATE NULL DEFAULT NULL;