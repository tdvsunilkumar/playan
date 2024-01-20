-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2023 at 05:49 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

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
-- Table structure for table `cpdo_development_certificate`
--

CREATE TABLE `cpdo_development_certificate` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cc_applicant_no` varchar(255) NOT NULL COMMENT 'System generate the applicant no.',
  `cc_date` date NOT NULL COMMENT 'date.',
  `cc_falc_no` varchar(255) NOT NULL COMMENT 'combination of applicant no - month - year.',
  `caf_id` int(11) NOT NULL COMMENT 'ref-table : cpdo_application_form.caf_id',
  `cc_rol` varchar(255) DEFAULT NULL COMMENT 'Right Over Land.',
  `cc_boc` varchar(255) NOT NULL COMMENT 'Basis for Clearance.',
  `cc_name_project` varchar(255) NOT NULL COMMENT 'Name of Project',
  `cc_area` varchar(255) NOT NULL COMMENT 'Area.',
  `cc_location` varchar(255) NOT NULL COMMENT 'Location.',
  `cc_project_class` varchar(255) NOT NULL COMMENT 'Project Classification.',
  `cc_site_classification` varchar(255) NOT NULL COMMENT 'Site Classification.',
  `cc_dominant` varchar(255) NOT NULL COMMENT 'Dominant land….',
  `cc_evaluation` varchar(255) NOT NULL COMMENT 'Evaluation of Facts.',
  `cc_decision` varchar(255) NOT NULL COMMENT 'Decision.',
  `preparedby` int(11) DEFAULT NULL,
  `prparedby` int(11) NOT NULL,
  `cc_recom_approval` int(11) NOT NULL COMMENT 'ref-table: hr_employee.id who will be first approval recommending Approval.',
  `cc_recom_approval_date` date DEFAULT NULL COMMENT 'date recommended.',
  `cc_noted` int(11) NOT NULL COMMENT 'ref-table: hr_employee.id who will be first approval recommending Approval.',
  `cc_noted_date` date DEFAULT NULL COMMENT 'date noted.',
  `cc_approved` int(11) NOT NULL COMMENT 'ref-table: hr_employee.id who will be first approval recommending Approval.',
  `cc_approved_date` date DEFAULT NULL COMMENT 'date approved.',
  `cc_notes_status` int(11) NOT NULL DEFAULT 0,
  `cc_recom_status` int(11) NOT NULL DEFAULT 0,
  `cc_approval_status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cc_recom_approval_position` varchar(255) DEFAULT NULL,
  `cc_noted_position` varchar(255) DEFAULT NULL,
  `cc_approved_position` varchar(255) DEFAULT NULL,
  `cir_created_position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cpdo_development_certificate`
--

INSERT INTO `cpdo_development_certificate` (`id`, `cc_applicant_no`, `cc_date`, `cc_falc_no`, `caf_id`, `cc_rol`, `cc_boc`, `cc_name_project`, `cc_area`, `cc_location`, `cc_project_class`, `cc_site_classification`, `cc_dominant`, `cc_evaluation`, `cc_decision`, `preparedby`, `prparedby`, `cc_recom_approval`, `cc_recom_approval_date`, `cc_noted`, `cc_noted_date`, `cc_approved`, `cc_approved_date`, `cc_notes_status`, `cc_recom_status`, `cc_approval_status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `cc_recom_approval_position`, `cc_noted_position`, `cc_approved_position`, `cir_created_position`) VALUES
(4, '000007', '2023-05-15', '05-2023-000007', 7, '1', 'adasd', 'wrwe', 'adas', 'asdasd', 'Residencial', 'asdasd', 'asdada', 'asdasd', 'adasd', 1, 0, 1, '2023-05-15', 2, NULL, 1, '2023-05-15', 0, 1, 1, 1, 1, '2023-05-13 12:56:59', '2023-05-13 12:56:59', 'Judge', 'Business Permit Head', 'Judge', 'Judge'),
(5, '000008', '2023-05-18', '05-2023-000008', 8, '2', 'Residencial', 'Proposed Medical Clinic', '123', 'Palayan City', 'Residencial', 'Residencial', '12321', 'qwe', 'asdas', 1, 0, 1, '2023-05-15', 7, NULL, 12, NULL, 0, 1, 0, 1, 1, '2023-05-15 17:22:04', '2023-07-12 13:45:14', 'Judge', 'VCM', 'Others', 'Judge'),
(6, '000009', '2023-05-18', '05-2023-000009', 9, '2', 'Residencial', 'Medical Clinic', 'adas', 'asdasd', 'Residencial', 'Residencial', 'asdada', 'asdasd', 'adasd', 7, 0, 36, NULL, 3, NULL, 3, NULL, 0, 0, 0, 1, 1, '2023-05-16 14:25:23', '2023-05-16 14:27:02', 'City Mayor', 'Business Permit Head', 'Business Permit Head', 'VCM'),
(7, '000010', '2023-05-22', '05-2023-000010', 10, '2', 'Residencial', 'Resort', '123', 'Palayan City', 'Residencial', 'Residencial', 'asasd', 'asdasd', 'adasd', 1, 0, 4, NULL, 8, NULL, 3, NULL, 0, 0, 0, 1, 1, '2023-05-18 09:47:21', '2023-05-22 12:20:03', 'Fiscal', 'City Mayor', 'Business Permit Head', 'Judge'),
(8, '000012', '2023-05-23', '05-2023-000012', 11, '1', '4000', 'Industrial', '1500', 'Miraj Road', 'Classification', 'Site Classification', '195', '3', 'Good', 2, 0, 4, NULL, 3, NULL, 4, NULL, 0, 0, 0, 1, 1, '2023-05-22 12:06:28', '2023-05-25 14:13:59', 'Fiscal', 'Business Permit Head', 'Fiscal', 'Department Head'),
(9, '000048', '2023-06-08', '06-2023-000048', 13, '1', 'Residencial', 'Resort', '123', 'Palayan City', 'Residencial', 'Residencial', 'asdada', 'asdasd', 'adasd', 4, 0, 22, '2023-06-01', 7, '2023-06-01', 36, '2023-06-01', 1, 1, 1, 2, 1, '2023-06-01 11:14:09', '2023-06-01 11:16:26', 'Department Head', 'VCM', 'City Mayor', 'Fiscal');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cpdo_development_certificate`
--
ALTER TABLE `cpdo_development_certificate`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cpdo_development_certificate`
--
ALTER TABLE `cpdo_development_certificate`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
