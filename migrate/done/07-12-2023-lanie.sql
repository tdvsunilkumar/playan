CREATE TABLE `ho_utility_yearly_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ho_inv_posting_id` int(11) NOT NULL,
  `beginning_qty` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  PRIMARY KEY(id)
);

ALTER TABLE `ho_lab_requests` ADD COLUMN IF NOT EXISTS `lab_req_others` text NULL AFTER `lab_req_diagnosis`;
ALTER TABLE `ho_lab_requests` ADD COLUMN IF NOT EXISTS `or_date` date NULL AFTER `lab_req_or`;