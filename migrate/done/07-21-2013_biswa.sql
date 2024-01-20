-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2023 at 07:06 AM
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
-- Table structure for table `hr_appointment`
--

CREATE TABLE `hr_appointment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_emp_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hra_department_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department_id',
  `hra_division_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.acctg_department__division_id',
  `hra_employee_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hra_date_hired` date NOT NULL,
  `hra_designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ref-Table: hr_employees.hr_designation_id',
  `hres_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employee_status.hres_id',
  `hras_id` int(11) NOT NULL COMMENT 'ref-Table: hr_employee_appointment_status',
  `hrpt_id` int(11) NOT NULL COMMENT 'ref-Table: hr_payment_term.hrpt_id',
  `hrol_id` int(11) NOT NULL COMMENT 'ref-Table: hr_occupational_level.hrol_id',
  `hrsg_id` int(11) NOT NULL COMMENT 'ref-Table: hr_salary_grade.hrsg_id',
  `hrsgs_id` int(11) NOT NULL COMMENT 'ref-Table: hr_salary_grade_step.hrsgs_id',
  `hra_monthly_rate` double(10,2) NOT NULL,
  `hra_annual_rate` double(10,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT '2023-07-21',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_appointment`
--
ALTER TABLE `hr_appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_appointment_id_index` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_appointment`
--
ALTER TABLE `hr_appointment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
