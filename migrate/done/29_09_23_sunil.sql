-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2023 at 06:58 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `rpt_properties_assessment_notices`
--

CREATE TABLE `rpt_properties_assessment_notices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rp_code` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: rpt_properties.id',
  `rp_property_code` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Ref-Table: rpt_properties.rp_property_code',
  `ntob_year` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Year',
  `ntob_month` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Month',
  `ntob_control_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Combination of (ntob_year-ntob_month+ntob_no) like "2020-120000',
  `rp_registered_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'reference profile.p_code of the system who registered the rpt_property',
  `rp_modified_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'reference profile.p_code of the system  who update the rpt_property',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rpt_properties_assessment_notices`
--
ALTER TABLE `rpt_properties_assessment_notices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rpt_properties_assessment_notices`
--
ALTER TABLE `rpt_properties_assessment_notices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `rpt_properties_assessment_notices` 
  ADD `rpo_code` BIGINT(20) NULL COMMENT 'Reference table clients.id' AFTER `id`;

ALTER TABLE `rpt_properties_assessment_notices` 
 ADD `type` TINYINT(1) NULL DEFAULT '0' 
 COMMENT '0 => if Control no genearated by date difference 1=> If control number genearated because of properties difference' AFTER `ntob_control_no`;  

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
