ALTER TABLE `hr_timecards` CHANGE `hrtc_time_in` `hrtc_time_in` TIME NULL COMMENT 'In time', CHANGE `hrtc_time_out` `hrtc_time_out` TIME NULL COMMENT 'Out Time';
ALTER TABLE `hr_appointment` ADD COLUMN IF NOT EXISTS `hra_hourly_rate` double(10,2)	 NULL COMMENT 'hra_monthly_rate / 208' AFTER `hra_annual_rate`;
ALTER TABLE `hr_overtime` ADD COLUMN IF NOT EXISTS `hrot_ot_cost` double(10,2)	 NULL COMMENT 'hr_appointment.hra_hourly_rate * hr_overtime.hrot_multiplier' AFTER `hro_reason`;
ALTER TABLE `hr_leaves` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

CREATE TABLE `hr_payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hrcp_id` int(11) NOT NULL COMMENT 'ref-Table: hr_cutoff_period.hrcp_id',
  `hrpr_payroll_no` VARCHAR(225) NOT NULL COMMENT 'YEAR-SERIES(digits)',
  `hrpr_appointment_type` int(11) NOT NULL COMMENT 'ref-Table: hr_appointment.hras_id',
  `hrpr_employees_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hrpr_department_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department_id',
  `hrpr_division_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department_division_id',
  `hrpr_monthly_rate` int(11) NOT NULL COMMENT 'ref-Table: hr_appointment.hra_monthly_rate',
  `hrpr_aut` int(11) NOT NULL,
  `hrpr_reg_ot` int(11) NOT NULL,
  `hrpr_rd_ot` int(11) NOT NULL,
  `hrpr_holiday_ot` int(11) NOT NULL,
  `hrpr_total_salary` int(11) NOT NULL,
  `hrpr_earnings` int(11) NOT NULL,
  `hrpr_deductions` int(11) NOT NULL,
  `hrpr_net_salary` int(11) NOT NULL,
  `hrpr_is_processed` int(11) NOT NULL,
  `hrpr_processed_date` date NOT NULL,

  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY(id)
) ;