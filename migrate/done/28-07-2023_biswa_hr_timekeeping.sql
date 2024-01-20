-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2023 at 03:27 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

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
-- Table structure for table `hr_timekeeping`
--

CREATE TABLE `hr_timekeeping` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrtk_emp_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hrtk_department_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department_id',
  `hrtk_division_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department__division_id',
  `hrtk_date` date NOT NULL,
  `hrtk_total_hours` double(10,2) NOT NULL,
  `hrtk_total_aut` double(10,2) NOT NULL,
  `hrtk_total_overtime` double(10,2) NOT NULL,
  `hrtk_total_leave` double(10,2) NOT NULL,
  `hrtk_is_processed` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_timekeeping`
--
ALTER TABLE `hr_timekeeping`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_timekeeping`
--
ALTER TABLE `hr_timekeeping`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
