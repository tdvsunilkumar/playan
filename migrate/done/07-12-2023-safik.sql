ALTER TABLE `rpt_property_sworns` ADD `rps_person_taking_oath_id` INT(10) NULL DEFAULT NULL COMMENT ' Person Taking Oath. Get data from table: clients.id' AFTER `rps_improvement_value`;
ALTER TABLE `rpt_property_sworns` ADD `cashier_id` INT(10) NULL DEFAULT NULL COMMENT 'Ref-Table: cto_cashiers.id' AFTER `rps_date`, ADD `cashierd_id` INT(10) NULL DEFAULT NULL COMMENT ' Ref-Table: cto_cashiers_details.id' AFTER `cashier_id`;
ALTER TABLE `rpt_property_sworns` ADD `rps_administer_official1_type` INT(10) NULL DEFAULT NULL COMMENT '1=Taxpayer, 2=Employee, 3=Consultant' AFTER `rps_ctc_issued_place`, ADD `rps_administer_official1_id` INT(11) NULL DEFAULT NULL COMMENT 'When 1[Taxpayer] then Ref-Table: clients.id, When 2[Employee] then Ref-Table: hr_employees.id, When 3[Consultant] then Ref-Table: consultants.id' AFTER `rps_administer_official1_type`;

ALTER TABLE `rpt_property_sworns` ADD `rps_administer_official2_type` INT(10) NOT NULL COMMENT '1=Taxpayer, 2=Employee, 3=Consultant' AFTER `rps_administer_official_title1`, ADD `rps_administer_official2_id` VARCHAR(75) NOT NULL COMMENT ' When 1[Taxpayer] then Ref-Table: clients.id, When 2[Employee] then Ref-Table: hr_employees.id, When 3[Consultant] then Ref-Table: consultants.id' AFTER `rps_administer_official2_type`;