INSERT INTO `acctg_account_general_ledgers` (`id`, `acctg_account_group_id`, `acctg_account_group_major_id`, `acctg_account_group_submajor_id`, `acctg_fund_code_id`, `prefix`, `code`, `description`, `mother_code`, `is_with_sl`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 1, 1, 1, 1, '010', '10101010', 'Cash Local Treasury', '', 0, '2023-02-14 02:50:56', 1, '2023-02-15 01:24:22', 1, 1),
(2, 1, 1, 1, NULL, '020', '10101020', 'Petty Cash', '', 0, '2023-02-14 02:50:56', 1, '2023-02-15 01:23:10', 1, 1),
(3, 1, 1, 2, NULL, '010', '10102010', 'Cash in Bank - Local Currency, Current Account', '', 0, '2023-02-14 13:21:50', 1, '2023-02-15 01:22:31', 1, 1),
(4, 1, 1, 2, NULL, '020', '10102020', 'Cash in Bank - Local Currency, Savings Account', '', 0, '2023-02-14 13:23:13', 1, '2023-02-15 00:53:15', 1, 1),
(5, 1, 1, 3, NULL, '010', '10103010', 'Cash in Bank - Foreign Currency, Savings Account', NULL, 0, '2023-02-15 01:24:49', 1, '2023-02-15 01:25:08', 1, 1),
(6, 1, 1, 3, NULL, '020', '10103020', 'Cash in Bank - Foreign Currency, Savings Account', NULL, 0, '2023-02-15 01:25:37', 1, NULL, NULL, 1),
(7, 1, 2, 4, NULL, '010', '10201010', 'Cash in Bank -Local Currency, Time Deposits', NULL, 0, '2023-02-15 01:27:12', 1, NULL, NULL, 1),
(8, 1, 2, 4, NULL, '020', '10201020', 'Cash in Bank - Foreign Currency, Time Deposits', NULL, 0, '2023-02-15 01:28:00', 1, '2023-02-15 01:28:11', 1, 1),
(9, 1, 2, 4, NULL, '030', '10201030', 'Treasury Bills', NULL, 0, '2023-02-15 01:29:39', 1, NULL, NULL, 1),
(10, 1, 5, 5, NULL, '010', '10701010', 'Land', NULL, 0, '2023-02-15 10:06:38', 1, NULL, NULL, 1),
(11, 1, 5, 5, NULL, '011', '10701011', 'Accumulated Impairment Losses - Land', NULL, 0, '2023-02-15 10:08:39', 1, NULL, NULL, 1),
(12, 1, 5, 6, NULL, '010', '10702010', 'Land Improvements, Aquaculture Structures', NULL, 0, '2023-02-15 10:09:35', 1, NULL, NULL, 1),
(13, 1, 5, 6, NULL, '011', '10702011', 'Accumulated Depreciation - Land Improvements, Aquaculture Structures', NULL, 0, '2023-02-15 10:10:43', 1, NULL, NULL, 1),
(14, 1, 5, 6, NULL, '012', '10702012', 'Accumulated Impairment Losses - Land Improvements, Aquaculture Structures', NULL, 0, '2023-02-15 10:12:07', 1, NULL, NULL, 1),
(15, 1, 5, 6, NULL, '990', '10702990', 'Other Land Improvements', NULL, 0, '2023-02-15 10:13:14', 1, NULL, NULL, 1),
(16, 1, 5, 6, NULL, '991', '10702991', 'Accumulated Depreciation - Other Land Improvements', NULL, 0, '2023-02-15 10:13:54', 1, NULL, NULL, 1),
(17, 1, 5, 6, NULL, '992', '10702992', 'Accumulated Impairment Losses - Other Land Improvements', NULL, 0, '2023-02-15 10:14:34', 1, NULL, NULL, 1),
(18, 1, 5, 7, NULL, '010', '10703010', 'Road Networks', NULL, 0, '2023-02-15 10:15:35', 1, '2023-02-15 10:16:40', 1, 1),
(19, 1, 5, 7, NULL, '011', '10703011', 'Accumulated Depreciation - Road Networks', NULL, 0, '2023-02-15 10:17:49', 1, NULL, NULL, 1),
(20, 1, 5, 7, NULL, '012', '10703012', 'Accumulated Impairment Losses - Road Networks', NULL, 0, '2023-02-15 10:19:14', 1, NULL, NULL, 1),
(21, 1, 5, 7, NULL, '020', '10703020', 'Flood Control Systems', NULL, 0, '2023-02-15 10:20:12', 1, NULL, NULL, 1),
(22, 1, 5, 7, NULL, '021', '10703021', 'Accumulated Depreciation - Flood Control Systems', NULL, 0, '2023-02-15 10:21:47', 1, NULL, NULL, 1),
(23, 1, 5, 7, NULL, '030', '10703030', 'Sewer Systems', NULL, 0, '2023-02-15 10:22:43', 1, NULL, NULL, 1),
(24, 1, 5, 7, NULL, '040', '10703040', 'Water Supply Systems', NULL, 0, '2023-02-15 11:35:48', 1, NULL, NULL, 1),
(25, 1, 5, 7, NULL, '050', '10703050', 'Power Supply Systems', NULL, 0, '2023-02-15 11:36:39', 1, NULL, NULL, 1),
(26, 1, 5, 7, NULL, '090', '10703090', 'Parks, Plazas and Monuments', NULL, 0, '2023-02-15 11:38:02', 1, NULL, NULL, 1),
(27, 1, 5, 7, NULL, '990', '10703990', 'Other Infrastructure Assets', NULL, 0, '2023-02-15 11:39:27', 1, NULL, NULL, 1),
(28, 1, 5, 8, NULL, '010', '10704010', 'Buildings', NULL, 0, '2023-02-15 11:40:26', 1, NULL, NULL, 1),
(29, 1, 5, 8, NULL, '020', '10704020', 'School Buildings', NULL, 0, '2023-02-15 11:41:13', 1, NULL, NULL, 1),
(30, 1, 5, 8, NULL, '030', '10704030', 'Hospitals and Health Centers', NULL, 0, '2023-02-15 11:42:33', 1, NULL, NULL, 1),
(31, 1, 5, 8, NULL, '040', '10704040', 'Markets', NULL, 0, '2023-02-15 11:45:20', 1, NULL, NULL, 1),
(32, 1, 5, 8, NULL, '990', '10704990', 'Other Structures', NULL, 0, '2023-02-15 11:46:05', 1, NULL, NULL, 1),
(33, 1, 5, 9, NULL, '010', '10705010', 'Machinery', NULL, 0, '2023-02-16 01:09:36', 1, '2023-02-16 01:10:23', 1, 1),
(34, 1, 5, 9, NULL, '020', '10705020', 'Office Equipment', NULL, 0, '2023-02-16 01:10:45', 1, NULL, NULL, 1),
(35, 1, 5, 9, NULL, '030', '10705030', 'Information and Communication Technology Equipment', NULL, 0, '2023-02-16 01:11:22', 1, NULL, NULL, 1),
(36, 1, 5, 9, NULL, '040', '10705040', 'Agricultural and Forestry Equipment', NULL, 0, '2023-02-16 01:12:17', 1, NULL, NULL, 1),
(37, 1, 5, 9, NULL, '070', '10705070', 'Communication Equipment', NULL, 0, '2023-02-16 01:12:48', 1, NULL, NULL, 1),
(38, 1, 5, 9, NULL, '080', '10705080', 'Construction and Heavy Equipment', NULL, 0, '2023-02-16 01:13:27', 1, NULL, NULL, 1),
(39, 1, 5, 9, NULL, '090', '10705090', 'Disaster Response and Rescue Equipment', NULL, 0, '2023-02-16 01:14:09', 1, NULL, NULL, 1),
(40, 1, 5, 9, NULL, '110', '10705110', 'Medical Equipment', NULL, 0, '2023-02-16 01:14:52', 1, NULL, NULL, 1),
(41, 1, 5, 9, NULL, '990', '10705990', 'Other Machinery and Equipment', NULL, 0, '2023-02-16 01:15:19', 1, NULL, NULL, 1),
(42, 1, 5, 10, NULL, '010', '10706010', 'Motor Vehicles', NULL, 0, '2023-02-16 01:15:54', 1, NULL, NULL, 1),
(43, 1, 5, 10, NULL, '040', '10706040', 'Watercrafts', NULL, 0, '2023-02-16 01:16:49', 1, NULL, NULL, 1),
(44, 1, 5, 10, NULL, '990', '10706990', 'Other Transportation Equipment', NULL, 0, '2023-02-16 01:17:19', 1, NULL, NULL, 1),
(45, 1, 5, 11, NULL, '010', '10707010', 'Furniture and Fixtures', NULL, 0, '2023-02-16 01:17:56', 1, NULL, NULL, 1),
(46, 1, 5, 11, NULL, '020', '10707020', 'Books', NULL, 0, '2023-02-16 01:18:28', 1, NULL, NULL, 1),
(47, 1, 5, 14, NULL, '010', '10710010', 'Construction in Progress - Land Improvements', NULL, 0, '2023-02-16 01:19:10', 1, NULL, NULL, 1),
(48, 1, 5, 14, NULL, '020', '10710020', 'Construction in Progress - Infrastructure Assets', NULL, 0, '2023-02-16 01:19:50', 1, NULL, NULL, 1),
(49, 1, 5, 14, NULL, '030', '10710030', 'Construction in Progress - Buildings and Other Structures', NULL, 0, '2023-02-16 01:20:25', 1, NULL, NULL, 1),
(50, 1, 5, 16, NULL, '990', '10799990', 'Other Property, Plant and Equipment', NULL, 0, '2023-02-16 01:21:15', 1, NULL, NULL, 1),
(51, 4, 12, 18, NULL, '040', '40102040', 'Real Property Tax- Basic', NULL, 0, '2023-02-16 01:29:07', 1, NULL, NULL, 1),
(52, 4, 12, 18, NULL, '041', '40102041', 'Discount on Real Property Tax- Basic', NULL, 0, '2023-02-16 01:29:38', 1, NULL, NULL, 1);


INSERT INTO `acctg_account_groups` (`id`, `code`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, '1', 'Assets', '2023-02-13 02:07:07', 1, '2023-02-14 08:21:46', 1, 1),
(2, '2', 'Liabilities', '2023-02-13 02:09:36', 1, '2023-02-14 05:30:24', 1, 1),
(3, '3', 'Equity', '2023-02-14 05:31:12', 1, NULL, NULL, 1),
(4, '4', 'Income', '2023-02-14 05:31:25', 1, NULL, NULL, 1),
(5, '5', 'Expenses', '2023-02-14 05:31:36', 1, NULL, NULL, 1);


INSERT INTO `acctg_account_groups_majors` (`id`, `acctg_account_group_id`, `prefix`, `code`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 1, '01', '101', 'Cash', '2023-02-13 06:01:01', 1, '2023-02-14 05:51:09', 1, 1),
(2, 1, '02', '102', 'Investments', '2023-02-13 06:07:06', 1, '2023-02-14 05:34:30', 1, 1),
(3, 1, '03', '103', 'Receivables', '2023-02-13 06:10:53', 1, '2023-02-14 05:34:53', 1, 1),
(4, 1, '04', '104', 'Inventories', '2023-02-14 08:28:52', 1, NULL, NULL, 1),
(5, 1, '07', '107', 'Property, Plant and Equipment', '2023-02-15 09:51:47', 1, NULL, NULL, 1),
(6, 1, '05', '105', 'Prepayments', '2023-02-15 09:53:17', 1, NULL, NULL, 1),
(7, 1, '06', '106', 'Investment Property', '2023-02-15 09:53:58', 1, NULL, NULL, 1),
(8, 1, '08', '108', 'Biological Assets', '2023-02-15 09:54:40', 1, NULL, NULL, 1),
(9, 1, '09', '109', 'Intangible Assets', '2023-02-15 09:54:58', 1, NULL, NULL, 1),
(10, 2, '01', '201', 'Financial Liabilities', '2023-02-15 09:55:31', 1, NULL, NULL, 1),
(11, 2, '02', '202', 'Inter-Agency Payables', '2023-02-15 09:55:58', 1, NULL, NULL, 1),
(12, 4, '01', '401', 'Tax Revenue', '2023-02-16 01:22:14', 1, NULL, NULL, 1),
(13, 4, '02', '402', 'Service and Business Income', '2023-02-16 01:22:36', 1, NULL, NULL, 1),
(14, 4, '03', '403', 'Transfers and Subsidy', '2023-02-16 01:22:55', 1, NULL, NULL, 1),
(15, 4, '05', '405', 'Gains', '2023-02-16 01:23:24', 1, NULL, NULL, 1),
(16, 4, '06', '406', 'Miscellaneous Income', '2023-02-16 01:23:45', 1, '2023-02-16 01:24:02', 1, 1);


INSERT INTO `acctg_account_groups_submajors` (`id`, `acctg_account_group_id`, `acctg_account_group_major_id`, `prefix`, `code`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 1, 1, '01', '10101', 'Cash on Hand', '2023-02-13 08:42:42', 1, '2023-02-14 12:27:42', 1, 1),
(2, 1, 1, '02', '10102', 'Cash in Bank - Local Currency', '2023-02-13 08:53:53', 1, '2023-02-14 08:32:15', 1, 1),
(3, 1, 1, '03', '10103', 'Cash in Bank - Foreign Currency', '2023-02-13 08:54:49', 1, '2023-02-14 08:32:42', 1, 1),
(4, 1, 2, '01', '10201', 'Investments in Time Deposits', '2023-02-14 08:00:13', 1, '2023-02-14 08:36:10', 1, 1),
(5, 1, 5, '01', '10701', 'Land', '2023-02-15 09:57:37', 1, NULL, NULL, 1),
(6, 1, 5, '02', '10702', 'Land Improvements', '2023-02-15 09:58:09', 1, NULL, NULL, 1),
(7, 1, 5, '03', '10703', 'Infrastructure Assets', '2023-02-15 09:58:46', 1, NULL, NULL, 1),
(8, 1, 5, '04', '10704', 'Buildings and Other Structures', '2023-02-15 09:59:29', 1, NULL, NULL, 1),
(9, 1, 5, '05', '10705', 'Machinery and Equipment', '2023-02-15 10:00:09', 1, NULL, NULL, 1),
(10, 1, 5, '06', '10706', 'Transportation Equipment', '2023-02-15 10:00:45', 1, NULL, NULL, 1),
(11, 1, 5, '07', '10707', 'Furniture, Fixtures and Books', '2023-02-15 10:01:13', 1, NULL, NULL, 1),
(12, 1, 5, '08', '10708', 'Leased Assets', '2023-02-15 10:01:34', 1, NULL, NULL, 1),
(13, 1, 5, '09', '10709', 'Leased Assets Improvements', '2023-02-15 10:02:01', 1, NULL, NULL, 1),
(14, 1, 5, '10', '10710', 'Construction in Progress', '2023-02-15 10:02:41', 1, NULL, NULL, 1),
(15, 1, 5, '11', '10711', 'Service Concession Assets', '2023-02-15 10:03:14', 1, NULL, NULL, 1),
(16, 1, 5, '99', '10799', 'Other Property, Plant and Equipment', '2023-02-15 10:03:45', 1, NULL, NULL, 1),
(17, 4, 12, '01', '40101', 'Tax Revenue - Individual and Corporation', '2023-02-16 01:25:01', 1, '2023-02-16 01:25:33', 1, 1),
(18, 4, 12, '02', '40102', 'Tax Revenue - Property', '2023-02-16 01:25:57', 1, NULL, NULL, 1),
(19, 4, 12, '03', '40103', 'Tax Revenue - Goods and Services', '2023-02-16 01:26:34', 1, NULL, NULL, 1),
(20, 4, 12, '04', '40104', 'Tax Revenue - Others', '2023-02-16 01:26:58', 1, NULL, NULL, 1),
(21, 4, 12, '05', '40105', 'Tax Revenue - Fines and Penalties', '2023-02-16 01:27:24', 1, NULL, NULL, 1),
(22, 4, 12, '06', '40106', 'Share from National Taxes', '2023-02-16 01:28:05', 1, NULL, NULL, 1);


INSERT INTO `acctg_departments` (`id`, `acctg_department_function_id`, `hr_employee_id`, `hr_designation_id`, `code`, `name`, `financial_code`, `shortname`, `program`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 1, 1, 1, '1021', 'Vice Mayors & SPO', '02-01-002', 'VMO', NULL, NULL, '2023-02-10 01:21:43', 1, '2023-02-15 06:29:41', 1, 1),
(2, 1, 4, 4, '1011', 'Office Of The City Mayor', '02-01-001', 'CMO', NULL, NULL, '2023-02-10 05:46:48', 1, '2023-02-15 06:39:08', 1, 1),
(3, 1, 4, NULL, '1061', 'City General Services Office', '02-01-017', 'GSO', NULL, NULL, '2023-02-14 03:22:03', 1, '2023-02-15 06:46:58', 1, 1),
(4, 2, NULL, NULL, '8751', 'City Engineering Office', '02-01-010', 'CEO', NULL, NULL, '2023-02-15 00:19:25', 1, '2023-02-15 06:49:25', 1, 1),
(5, 2, NULL, NULL, '4411', 'City Health Office', '02-01-011', 'CHO', NULL, NULL, '2023-02-15 04:23:44', 1, '2023-02-15 06:53:12', 1, 1);


INSERT INTO `acctg_departments_divisions` (`id`, `acctg_department_id`, `code`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 1, '0000', 'Vice Mayor & SPO', '2023-02-10 01:22:00', 1, '2023-02-13 01:18:12', 1, 1),
(2, 2, '0000', 'Office Of The City Mayor', '2023-02-10 05:47:14', 1, '2023-02-15 06:32:48', 1, 1),
(3, 3, '0000', 'City General Services Office', '2023-02-14 03:22:12', 1, '2023-02-15 06:45:17', 1, 1),
(4, 2, '0001', 'Environmental & Natural Rescources Office', '2023-02-15 06:33:12', 1, NULL, NULL, 1),
(5, 2, '0002', 'Palayan City Rescue(CDRRMO)', '2023-02-15 06:33:30', 1, '2023-02-15 06:33:39', 1, 1),
(6, 4, '0000', 'City Engieering Office', '2023-02-15 06:49:17', 1, NULL, NULL, 1),
(7, 5, '0000', 'Integrated City Health Office', '2023-02-15 06:52:22', 1, '2023-02-15 06:52:32', 1, 1);


INSERT INTO `acctg_departments_functions` (`id`, `code`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'GS', 'General Services', '2023-02-08 15:18:52', 1, NULL, NULL, 1),
(2, 'ES', 'Economic Services', '2023-02-08 15:18:52', 1, NULL, NULL, 1),
(3, 'SS', 'Social Services', '2023-02-08 15:18:52', 1, NULL, NULL, 1);


INSERT INTO `acctg_fund_codes` (`id`, `code`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, '101', 'General Fund Proper', '2023-02-08 01:40:04', 1, '2023-02-13 01:01:36', 1, 1),
(2, '221', 'Special Education Fund', '2023-02-08 01:40:04', 1, '2023-02-13 01:01:58', 1, 1),
(3, '301', 'Socialize Housing Fund', '2023-02-08 06:33:09', 1, '2023-02-13 01:02:59', 1, 1),
(4, '401', 'Trust Fund', '2023-02-08 06:33:43', 1, '2023-02-13 01:03:17', 1, 1);


INSERT INTO `hr_designations` (`id`, `code`, `description`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 'VCM', 'Vice Mayor', '2023-02-10 00:38:06', 1, '2023-02-14 02:51:47', 1, 1),
(2, 'CA', 'City Administrator', '2023-02-10 00:38:06', 1, '2023-02-14 12:22:42', 1, 1),
(3, 'BPH', 'Business Permit Head', '2023-02-10 00:38:06', 1, '2023-02-14 12:23:23', 1, 1),
(4, 'CM', 'City Mayor', '2023-02-10 00:38:06', 1, '2023-02-14 12:23:52', 1, 1),
(5, 'ARC', 'Architect', '2023-02-10 06:13:14', 1, '2023-02-14 12:24:10', 1, 1),
(6, '969', '4242', '2023-02-10 06:14:59', 1, NULL, NULL, 1);


INSERT INTO `hr_employees` (`id`, `barangay_id`, `acctg_department_id`, `acctg_department_division_id`, `hr_designation_id`, `identification_no`, `firstname`, `middlename`, `lastname`, `suffix`, `title`, `gender`, `birthdate`, `c_house_lot_no`, `c_street_name`, `c_subdivision`, `c_brgy_code`, `c_region`, `c_zip`, `c_country`, `current_address`, `email_address`, `telephone_no`, `mobile_no`, `fax_no`, `sss_no`, `tin_no`, `pag_ibig_no`, `philhealth_no`, `is_dept_restricted`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_active`) VALUES
(1, 27, 2, 3, 1, '001', 'Moises Jr.', 'A.', 'Carmona', NULL, 'Mr.', 'Male', '2023-02-22', 'Block 18 Lot 3', NULL, NULL, NULL, NULL, NULL, NULL, 'Block 18 Lot 3 ATATE, Palayan City, Nueva Ecija, Region III', NULL, NULL, '09283164164', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-10 00:52:40', 1, '2023-02-15 07:04:18', 1, 1),
(2, 27, 2, 3, 3, '002', 'Fercilyn', 'E.', 'Grospe', 'Jr.', NULL, 'Male', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ATATE, Palayan City, Nueva Ecija, Region III', NULL, NULL, '09283164165', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-10 00:52:40', 1, '2023-02-15 07:04:34', 1, 1),
(3, 29, 2, 3, 3, '003', 'Adora', 'S.', 'Mongaya', NULL, 'Mngr.', 'Male', '1970-01-01', 'Block 18 Lot 3', 'Phase 4', 'Celina Plains', NULL, NULL, NULL, NULL, 'Block 18 Lot 3, Phase 4, Celina Plains BAGONG BUHAY, Palayan City, Nueva Ecija, Region III', NULL, NULL, '09283164166', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-10 00:52:40', 1, '2023-02-15 07:04:55', 1, 1),
(4, 23, 2, 3, 4, '004', 'Trinidad', 'O.', 'Jose', NULL, NULL, 'Male', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' BUYON, Bacarra, Ilocos Norte, Region I', NULL, NULL, '09283164167', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-10 00:52:40', 1, '2023-02-15 07:05:16', 1, 1),
(5, 27, 2, 3, 3, '005', 'Elsie', 'D.', 'Pacis', NULL, NULL, 'Male', '2023-02-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' ATATE, Palayan City, Nueva Ecija, Region III', NULL, NULL, '5252', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-11 16:42:44', 1, '2023-02-15 07:18:03', 1, 1),
(6, 29, 2, 3, 4, '005', 'Aliudin', 'Amer', 'Macalawi', NULL, NULL, 'Male', '1970-01-01', '24', 'Sarmiento Street', 'Guanzon Ville', NULL, NULL, NULL, NULL, '24, Sarmiento Street, Guanzon Ville BAGONG BUHAY, Palayan City, Nueva Ecija, Region III', NULL, NULL, '09283164164', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-14 06:25:05', 1, '2023-02-14 06:26:40', 1, 1),
(7, 29, 1, 1, 1, '123', 'Olga', 'M.', 'Berdon', NULL, 'Atty', 'Female', '2023-02-14', 'Block 18 Lot 3', 'Phase 4', 'Celina Plains', NULL, NULL, NULL, NULL, 'Block 18 Lot 3, Phase 4, Celina Plains BAGONG BUHAY, Palayan City, Nueva Ecija, Region III', NULL, NULL, '42342342', NULL, NULL, NULL, NULL, NULL, 1, '2023-02-14 07:58:57', 1, '2023-02-14 08:17:38', 1, 1),
(8, 28, 2, 3, 4, '2424', 'Jherome', NULL, 'Deguzman', NULL, NULL, 'Male', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, ' AULO, Palayan City, Nueva Ecija, Region III', NULL, NULL, '09283164165', NULL, NULL, NULL, NULL, NULL, 0, '2023-02-15 00:44:24', 1, '2023-02-15 00:57:28', 1, 1);

