-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2023 at 10:20 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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
-- Table structure for table `hr_income_and_deduction`
--

CREATE TABLE `hr_income_and_deduction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hriad_ref_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'YEAR-SERIES(5digits)',
  `hriad_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hriad_amount` double(8,2) NOT NULL,
  `hrlc_id` int(11) NOT NULL COMMENT 'ref-Table: hr_loan_cycle.hrlc_id',
  `emp_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hrla_department_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department_id',
  `hrla_division_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department__division_id',
  `hriad_effectivity_date` date NOT NULL,
  `hriad_balance` double(8,2) NOT NULL COMMENT 'Interest Percentage',
  `hriad_approved_by` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hriad_approved_date` date NOT NULL COMMENT 'Approved Date',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_income_and_deduction`
--
ALTER TABLE `hr_income_and_deduction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_income_and_deduction`
--
ALTER TABLE `hr_income_and_deduction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
