-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 01, 2023 at 05:49 AM
-- Server version: 10.10.2-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `palayan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bplo_business_history`
--

CREATE TABLE IF NOT EXISTS `bplo_business_history` (
  `id` bigint(20) unsigned NOT NULL,
  `busn_id` int(11) NOT NULL COMMENT 'bplo_business_id',
  `locality_id` int(11) DEFAULT NULL,
  `busn_tax_year` int(11) DEFAULT NULL,
  `busn_tax_month` int(11) DEFAULT NULL,
  `busn_series_no` int(11) DEFAULT NULL,
  `is_individual` int(11) DEFAULT NULL,
  `busn_tracking_no` varchar(100) DEFAULT NULL,
  `app_code` int(11) DEFAULT NULL,
  `pm_id` int(11) DEFAULT NULL,
  `busn_id_initial` varchar(100) DEFAULT NULL,
  `loc_local_id` varchar(100) DEFAULT NULL,
  `busns_id` int(11) DEFAULT NULL,
  `busns_id_no` varchar(100) DEFAULT NULL COMMENT 'Combination of fields: [busn_id_initial -  rpt_locality.loc_local_code-busn_id] data format will be like "P-034919-00023"',
  `busn_name` varchar(150) DEFAULT NULL,
  `busn_trade_name` varchar(150) DEFAULT NULL,
  `btype_id` int(11) DEFAULT NULL,
  `busn_registration_no` varchar(100) DEFAULT NULL,
  `busn_tin_no` varchar(100) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `busn_office_main_building_no` varchar(100) DEFAULT NULL,
  `busn_office_main_building_name` varchar(100) DEFAULT NULL,
  `busn_office_main_add_block_no` varchar(100) DEFAULT NULL,
  `busn_office_main_add_lot_no` varchar(100) DEFAULT NULL,
  `busn_office_main_add_street_name` varchar(100) DEFAULT NULL,
  `busn_office_main_add_subdivision` varchar(100) DEFAULT NULL,
  `busn_office_main_barangay_id` int(11) DEFAULT NULL,
  `busloc_id` int(11) DEFAULT NULL,
  `busn_bldg_area` int(11) DEFAULT NULL,
  `busn_bldg_total_floor_area` int(11) DEFAULT NULL,
  `busn_employee_no_female` int(11) DEFAULT NULL,
  `busn_employee_no_male` int(11) DEFAULT NULL,
  `busn_employee_total_no` int(11) DEFAULT NULL,
  `busn_employee_no_lgu` int(11) DEFAULT NULL,
  `busn_vehicle_no_van_truck` int(11) DEFAULT NULL,
  `busn_vehicle_no_motorcycle` int(11) DEFAULT NULL,
  `busn_bldg_is_owned` int(11) DEFAULT NULL,
  `busn_bldg_tax_declaration_no` varchar(150) DEFAULT NULL,
  `busn_bldg_property_index_no` varchar(150) DEFAULT NULL,
  `busn_tax_incentive_enjoy` tinyint(4) DEFAULT NULL,
  `busn_office_is_same_as_main` tinyint(4) DEFAULT NULL,
  `busn_office_building_no` varchar(100) DEFAULT NULL,
  `busn_office_building_name` varchar(100) DEFAULT NULL,
  `busn_office_add_block_no` varchar(100) DEFAULT NULL,
  `busn_office_add_lot_no` varchar(100) DEFAULT NULL,
  `busn_office_add_street_name` varchar(100) DEFAULT NULL,
  `busn_office_add_subdivision` varchar(100) DEFAULT NULL,
  `busn_office_barangay_id` int(11) DEFAULT NULL,
  `busn_app_status` tinyint(4) DEFAULT NULL,
  `busn_dept_involved` tinyint(4) DEFAULT NULL,
  `busn_dept_completed` tinyint(4) DEFAULT NULL,
  `busn_app_method` enum('Online','Walk-In') DEFAULT 'Walk-In',
  `is_final_assessment` int(1) DEFAULT 0,
  `application_date` date DEFAULT NULL,
  `busn_plate_number` varchar(50) DEFAULT NULL COMMENT ' Will display only in the side of the Business Permit Issuance',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) unsigned NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bplo_business_history`
--
ALTER TABLE `bplo_business_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bplo_business_history`
--
ALTER TABLE `bplo_business_history`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
