-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2023 at 07:52 AM
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
-- Table structure for table `hr_changeof_schedules`
--

CREATE TABLE `hr_changeof_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `applicationno` varchar(100) NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `department_id` int(11) NOT NULL DEFAULT 0,
  `hrcos_start_date` date NOT NULL COMMENT 'Start Date',
  `hrcos_end_date` date NOT NULL COMMENT 'End Date',
  `hrcos_original_schedule` int(11) NOT NULL COMMENT 'default schedule id',
  `hrcos_new_schedule` int(11) NOT NULL COMMENT 'default schedule id',
  `reason` varchar(255) DEFAULT NULL COMMENT 'Reason',
  `status` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approvedbyposition` varchar(255) DEFAULT NULL,
  `disapproved_at` timestamp NULL DEFAULT NULL,
  `disapproved_by` int(11) DEFAULT NULL,
  `reviewd_by` int(11) NOT NULL DEFAULT 0,
  `reviewed_position` varchar(255) DEFAULT NULL,
  `noted_by` int(11) NOT NULL DEFAULT 0,
  `noted_position` varchar(255) DEFAULT NULL,
  `approved_counter` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_changeof_schedules`
--

INSERT INTO `hr_changeof_schedules` (`id`, `applicationno`, `hr_employeesid`, `department_id`, `hrcos_start_date`, `hrcos_end_date`, `hrcos_original_schedule`, `hrcos_new_schedule`, `reason`, `status`, `approved_at`, `approved_by`, `approvedbyposition`, `disapproved_at`, `disapproved_by`, `reviewd_by`, `reviewed_position`, `noted_by`, `noted_position`, `approved_counter`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '', 1, 32, '2023-07-06', '2023-07-29', 1, 2, NULL, 5, NULL, '1', 'sample', '2023-07-07 11:07:32', 1, 0, NULL, 0, NULL, 1, 1, 1, '2023-07-06 08:39:08', '2023-07-10 10:29:39'),
(2, '', 1, 32, '2023-07-06', '2023-07-29', 1, 2, 'REason for schedule', 0, NULL, '1', 'VCM', NULL, NULL, 1, 'VCM', 1, 'VCM', 3, 1, 1, '2023-07-06 08:40:10', '2023-07-12 07:31:49'),
(3, '2023-00003', 1, 14, '2023-07-13', '2023-07-15', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, 1, 1, '2023-07-11 04:48:23', '2023-07-11 11:28:03'),
(4, '2023-00004', 1, 14, '2023-07-18', '2023-07-16', 1, 1, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, 1, 1, '2023-07-11 11:30:13', '2023-07-11 11:30:13'),
(5, '2023-00005', 1, 14, '2023-07-12', '2023-07-14', 1, 1, 'sample reason', 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, 1, 1, '2023-07-12 08:41:51', '2023-07-12 08:41:51'),
(6, '2023-00006', 2, 2, '2023-08-10', '2023-08-20', 1, 3, 'Another Schedule', 5, NULL, '0', 'VCM', NULL, NULL, 1, 'VCM', 1, 'VCM', 3, 1, 1, '2023-07-18 10:17:03', '2023-07-20 05:09:39'),
(7, '2023-00007', 7, 2, '2023-07-19', '2023-07-21', 1, 1, NULL, 4, NULL, '1', 'VCM', NULL, NULL, 1, 'VCM', 0, NULL, 2, 1, 1, '2023-07-19 04:15:15', '2023-07-20 08:15:19'),
(8, '2023-00008', 7, 2, '2023-07-21', '2023-07-23', 1, 2, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, 1, 1, '2023-07-21 06:17:36', '2023-07-21 06:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `hr_default_schedules`
--

CREATE TABLE `hr_default_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrds_range` varchar(255) NOT NULL COMMENT 'Schedule Range',
  `hrds_start_time` time NOT NULL COMMENT 'Start Time',
  `hrds_end_time` time NOT NULL COMMENT 'End Time',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_default_schedules`
--

INSERT INTO `hr_default_schedules` (`id`, `hrds_range`, `hrds_start_time`, `hrds_end_time`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '', '07:00:00', '16:00:00', 1, 1, 1, '2023-07-04 08:20:45', '2023-07-04 08:33:29'),
(2, '09:40 17:40', '09:40:00', '17:40:00', 1, 1, 1, '2023-07-12 07:40:30', '2023-07-12 07:40:30'),
(3, '08:00 17:00', '08:00:00', '17:00:00', 1, 1, 1, '2023-07-12 07:41:14', '2023-07-12 07:41:14');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employee_statuses`
--

CREATE TABLE `hr_employee_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hres_description` varchar(255) NOT NULL COMMENT 'Description',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_employee_statuses`
--

INSERT INTO `hr_employee_statuses` (`id`, `hres_description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'open', 1, 1, 1, '2023-07-24 05:22:04', '2023-07-24 05:22:04');

-- --------------------------------------------------------

--
-- Table structure for table `hr_holidays`
--

CREATE TABLE `hr_holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrh_date` date NOT NULL COMMENT 'date',
  `hrh_description` varchar(255) NOT NULL COMMENT 'Description',
  `hrht_id` int(11) NOT NULL COMMENT 'ref-Table: hr_holiday_types.hrht_id',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_holidays`
--

INSERT INTO `hr_holidays` (`id`, `hrh_date`, `hrh_description`, `hrht_id`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2023-01-01', 'New Year\'s Day', 1, 1, 1, 1, '2023-07-04 09:27:51', '2023-07-04 09:27:51'),
(2, '2023-04-06', 'Maundy Thursday', 1, 1, 1, 1, '2023-07-04 09:28:30', '2023-07-04 09:29:20');

-- --------------------------------------------------------

--
-- Table structure for table `hr_leaves`
--

CREATE TABLE `hr_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `applicationno` varchar(100) NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hrl_start_date` date NOT NULL COMMENT 'Start Date',
  `hrl_end_date` date NOT NULL COMMENT 'End Date',
  `hrlt_id` int(11) NOT NULL COMMENT 'Leave Type',
  `hrla_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_application.hrlt_id',
  `dayswithpay` int(11) NOT NULL DEFAULT 0,
  `hrla_reason` varchar(255) DEFAULT NULL COMMENT 'Reason',
  `hrla_status` int(11) DEFAULT NULL COMMENT 'Status of Application',
  `hrla_approved_by` int(11) NOT NULL COMMENT 'Approved',
  `hrla_approved_at` datetime NOT NULL COMMENT 'date time',
  `hrla_reviewed_by` int(11) NOT NULL COMMENT 'Reviewed By',
  `hrla_reviewed_at` datetime NOT NULL COMMENT 'date time',
  `hrla_noted_by` int(11) NOT NULL COMMENT 'Noted By',
  `hrla_noted_at` datetime NOT NULL COMMENT 'date time',
  `hrla_disapproved_by` int(11) NOT NULL COMMENT 'Disapprove BY',
  `hrla_disapproved_at` datetime NOT NULL COMMENT 'date time',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leaves`
--

INSERT INTO `hr_leaves` (`id`, `applicationno`, `hr_employeesid`, `hrl_start_date`, `hrl_end_date`, `hrlt_id`, `hrla_id`, `dayswithpay`, `hrla_reason`, `hrla_status`, `hrla_approved_by`, `hrla_approved_at`, `hrla_reviewed_by`, `hrla_reviewed_at`, `hrla_noted_by`, `hrla_noted_at`, `hrla_disapproved_by`, `hrla_disapproved_at`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '', 2, '2023-07-10', '2023-07-12', 1, 2, 0, 'Sick Leave', 2, 1, '2023-07-10 05:14:05', 1, '2023-07-10 05:14:05', 1, '2023-07-10 05:14:05', 1, '2023-07-10 05:14:05', 1, 1, '2023-07-10 03:14:06', '2023-07-10 03:14:06'),
(2, '', 3, '2023-07-10', '2023-07-16', 4, 1, 0, NULL, 3, 1, '2023-07-12 10:31:08', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-10 07:45:51', '2023-07-12 05:01:08'),
(3, '2023-00003', 4, '2023-07-12', '2023-07-13', 1, 1, 0, 'Reason', 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-12 10:23:10', '2023-07-12 10:23:10'),
(4, '2023-00004', 7, '2023-07-12', '2023-07-14', 1, 1, 0, 'Reason', 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-12 10:41:25', '2023-07-20 07:56:13'),
(5, '2023-00005', 7, '2023-07-19', '2023-07-22', 1, 1, 0, NULL, 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-19 04:38:59', '2023-07-21 06:23:39'),
(6, '2023-00006', 7, '2023-07-20', '2023-07-23', 1, 1, 0, 'Sample', 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-21 06:25:23', '2023-07-21 06:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `hr_leavetypes`
--

CREATE TABLE `hr_leavetypes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrlt_leave_code` varchar(255) NOT NULL COMMENT 'Leave Code',
  `hrlt_leave_type` text NOT NULL COMMENT 'Leave Type',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leavetypes`
--

INSERT INTO `hr_leavetypes` (`id`, `hrlt_leave_code`, `hrlt_leave_type`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'LOWP', 'Leave Without pay', 0, 1, 1, '2023-07-04 05:06:25', '2023-07-04 05:12:43'),
(2, 'VL', 'Vacation leave', 1, 1, 1, '2023-07-04 05:14:49', '2023-07-04 05:14:49'),
(3, 'SL', 'Sick leave', 1, 1, 1, '2023-07-10 05:10:54', '2023-07-10 05:10:54'),
(4, 'BL', 'Bereavement leave', 1, 1, 1, '2023-07-10 05:11:08', '2023-07-10 05:11:08'),
(5, 'BDL', 'Birthday leave', 1, 1, 1, '2023-07-10 05:11:28', '2023-07-10 05:11:28'),
(6, 'ML', 'Maternity leave', 1, 1, 1, '2023-07-10 05:11:43', '2023-07-10 05:11:43'),
(7, 'PL', 'Partenity Leave', 1, 1, 1, '2023-07-10 05:11:58', '2023-07-10 05:11:58'),
(8, 'SPL', 'Solo Parent leave', 1, 1, 1, '2023-07-10 05:12:13', '2023-07-10 05:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_adjustments`
--

CREATE TABLE `hr_leave_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hrlp_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_parameter.hrlp_id',
  `hrlea_date_effective` date NOT NULL COMMENT 'Date Effective',
  `hrlea_status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leave_adjustments`
--

INSERT INTO `hr_leave_adjustments` (`id`, `hr_employeesid`, `hrlp_id`, `hrlea_date_effective`, `hrlea_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 8, 1, '2023-07-21', 0, 1, 1, '2023-07-21 04:24:21', '2023-07-21 04:37:28'),
(9, 9, 1, '2023-07-22', 2, 1, 1, '2023-07-21 04:43:04', '2023-07-21 07:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_adjustment_detail`
--

CREATE TABLE `hr_leave_adjustment_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrlead_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_earnings_adjustment.hrlead_id',
  `hrlt_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_type.hrlt_id',
  `hrlad_adjustment` int(11) NOT NULL COMMENT 'adjustment',
  `hrlad_requested_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Requested By',
  `hrlad_approved_by` int(11) NOT NULL DEFAULT 0 COMMENT 'Approved By',
  `hrlad_status` int(11) NOT NULL DEFAULT 0 COMMENT 'status',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leave_adjustment_detail`
--

INSERT INTO `hr_leave_adjustment_detail` (`id`, `hrlead_id`, `hrlt_id`, `hrlad_adjustment`, `hrlad_requested_by`, `hrlad_approved_by`, `hrlad_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 0, 0, 1, 1, NULL, '2023-07-20 04:37:05', NULL),
(2, 7, 7, 3, 0, 0, 1, 1, NULL, '2023-07-20 04:37:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_applications`
--

CREATE TABLE `hr_leave_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrla_description` varchar(255) NOT NULL COMMENT 'Leave Application',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leave_applications`
--

INSERT INTO `hr_leave_applications` (`id`, `hrla_description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Whole Day', 1, 1, 1, '2023-07-04 07:04:32', '2023-07-04 07:04:32'),
(2, '1st Half', 1, 1, 1, '2023-07-04 07:05:26', '2023-07-04 07:12:11'),
(3, '2nd Half', 1, 1, 1, '2023-07-04 07:12:46', '2023-07-04 07:12:46');

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_earning_adjustment_detail`
--

CREATE TABLE `hr_leave_earning_adjustment_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrlea_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_earning_adjustment.hrlea_id',
  `hrlt_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_type.hrlt_id',
  `hrlpc_days` int(11) NOT NULL COMMENT 'get number from Leave Parameter # Of Days',
  `hrlead_used` int(11) NOT NULL COMMENT 'Used',
  `hrlead_balance` int(11) NOT NULL COMMENT 'Balance',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leave_earning_adjustment_detail`
--

INSERT INTO `hr_leave_earning_adjustment_detail` (`id`, `hrlea_id`, `hrlt_id`, `hrlpc_days`, `hrlead_used`, `hrlead_balance`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 0, 0, 1, 1, '2023-07-21 04:24:21', '2023-07-21 04:40:18'),
(2, 1, 2, 0, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(3, 1, 3, 2, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(4, 1, 4, 0, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(5, 1, 5, 2, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(6, 1, 6, 0, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(7, 1, 7, 2, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(8, 1, 8, 0, 0, 0, 1, 1, '2023-07-21 04:25:51', '2023-07-21 04:34:05'),
(9, 9, 1, 2, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(10, 9, 2, 0, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(11, 9, 3, 2, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(12, 9, 4, 0, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(13, 9, 5, 2, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(14, 9, 6, 0, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(15, 9, 7, 2, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL),
(16, 9, 8, 0, 0, 0, 1, NULL, '2023-07-21 04:43:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_parameter`
--

CREATE TABLE `hr_leave_parameter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrlp_description` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leave_parameter`
--

INSERT INTO `hr_leave_parameter` (`id`, `hrlp_description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Leave Parameter', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_leave_parameter_detail`
--

CREATE TABLE `hr_leave_parameter_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrlp_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table: hr_leave_parameter.hrlp_id',
  `hrlt_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table: hr_leave_type.hrlt_id',
  `hrlpc_days` int(11) NOT NULL DEFAULT 0 COMMENT '# Of Days',
  `hrat_id` int(11) NOT NULL DEFAULT 0 COMMENT 'ref-Table: hr_accrual_type.hrat_id',
  `hrlpc_credits` int(11) NOT NULL DEFAULT 0 COMMENT 'Accrual Credits',
  `hrlpc_is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leave_parameter_detail`
--

INSERT INTO `hr_leave_parameter_detail` (`id`, `hrlp_id`, `hrlt_id`, `hrlpc_days`, `hrat_id`, `hrlpc_credits`, `hrlpc_is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 0, 0, 1, 0, 0, NULL, NULL),
(2, 1, 2, 0, 0, 0, 0, 0, 0, NULL, NULL),
(3, 1, 3, 2, 0, 0, 1, 0, 0, NULL, NULL),
(4, 1, 4, 0, 0, 0, 0, 0, 0, NULL, NULL),
(5, 1, 5, 2, 0, 0, 1, 0, 0, NULL, NULL),
(6, 1, 6, 0, 0, 0, 0, 0, 0, NULL, NULL),
(7, 1, 7, 2, 0, 0, 1, 0, 0, NULL, NULL),
(8, 1, 8, 0, 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_occupation_levels`
--

CREATE TABLE `hr_occupation_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrol_description` varchar(255) NOT NULL COMMENT 'Description',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_occupation_levels`
--

INSERT INTO `hr_occupation_levels` (`id`, `hrol_description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Level 1', 1, 1, 1, '2023-07-24 07:26:50', '2023-07-24 07:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `hr_official_works`
--

CREATE TABLE `hr_official_works` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `applicationno` varchar(100) NOT NULL,
  `department_id` int(11) NOT NULL DEFAULT 0,
  `hrow_work_date` date NOT NULL COMMENT 'Work Date',
  `hrwt_id` int(11) NOT NULL COMMENT 'ref-Table: hr_work_type.hrwt_id',
  `hrow_time_in` time NOT NULL COMMENT 'Start Time',
  `hrow_time_out` time NOT NULL COMMENT 'End Time',
  `hrow_reason` varchar(255) DEFAULT NULL COMMENT 'Reason',
  `hrow_status` int(11) DEFAULT NULL COMMENT 'Status of time log',
  `hrow_approved_by` int(11) NOT NULL COMMENT 'Approved',
  `hrow_approved_at` datetime NOT NULL COMMENT 'date time',
  `hrow_reviewed_by` int(11) NOT NULL COMMENT 'Reviewed By',
  `hrow_reviewed_at` datetime NOT NULL COMMENT 'date time',
  `hrow_noted_by` int(11) NOT NULL COMMENT 'Noted By',
  `hrow_noted_at` datetime NOT NULL COMMENT 'date time',
  `hrow_disapproved_by` int(11) NOT NULL COMMENT 'Disapprove BY',
  `hrow_disapproved_at` datetime NOT NULL COMMENT 'date time',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_official_works`
--

INSERT INTO `hr_official_works` (`id`, `hr_employeesid`, `applicationno`, `department_id`, `hrow_work_date`, `hrwt_id`, `hrow_time_in`, `hrow_time_out`, `hrow_reason`, `hrow_status`, `hrow_approved_by`, `hrow_approved_at`, `hrow_reviewed_by`, `hrow_reviewed_at`, `hrow_noted_by`, `hrow_noted_at`, `hrow_disapproved_by`, `hrow_disapproved_at`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, '2023-00001', 2, '2023-07-11', 1, '11:27:59', '12:23:00', 'reason', 1, 1, '2023-07-11 08:08:59', 1, '2023-07-11 08:08:59', 1, '2023-07-11 08:08:59', 1, '2023-07-11 08:08:59', 11, 1, NULL, '2023-07-14 08:22:28'),
(2, 1, '2023-00001', 2, '2023-07-11', 1, '09:00:00', '18:35:00', 'Reason', 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-11 09:44:45', '2023-07-14 08:36:06'),
(3, 7, '2023-00003', 2, '2023-07-19', 1, '07:49:00', '10:49:00', NULL, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-19 04:49:41', '2023-07-19 04:49:41'),
(4, 7, '2023-00004', 2, '2023-07-20', 1, '08:02:00', '16:02:00', NULL, 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-19 05:02:47', '2023-07-21 06:27:42'),
(5, 7, '2023-00005', 2, '2023-07-22', 1, '09:28:00', '15:28:00', NULL, 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-21 06:29:07', '2023-07-21 06:29:07');

-- --------------------------------------------------------

--
-- Table structure for table `hr_offsets`
--

CREATE TABLE `hr_offsets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `applicationno` varchar(255) NOT NULL COMMENT 'application no',
  `hro_work_date` date NOT NULL COMMENT 'work Date',
  `hro_id` int(11) NOT NULL COMMENT 'ref-Table: hr_leave_application.hrlt_id',
  `hro_remaining_offset_hours` int(11) NOT NULL DEFAULT 0,
  `hro_reason` varchar(255) DEFAULT NULL COMMENT 'Reason',
  `hro_status` int(11) DEFAULT 0 COMMENT 'Status of Application',
  `hro_approved_by` int(11) NOT NULL COMMENT 'Approved',
  `hro_approved_at` datetime NOT NULL COMMENT 'date time',
  `hro_reviewed_by` int(11) NOT NULL COMMENT 'Reviewed By',
  `hro_reviewed_at` datetime NOT NULL COMMENT 'date time',
  `hro_noted_by` int(11) NOT NULL COMMENT 'Noted By',
  `hro_noted_at` datetime NOT NULL COMMENT 'date time',
  `hro_disapproved_by` int(11) NOT NULL COMMENT 'Disapprove BY',
  `hro_disapproved_at` datetime NOT NULL COMMENT 'date time',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_offsets`
--

INSERT INTO `hr_offsets` (`id`, `hr_employeesid`, `applicationno`, `hro_work_date`, `hro_id`, `hro_remaining_offset_hours`, `hro_reason`, `hro_status`, `hro_approved_by`, `hro_approved_at`, `hro_reviewed_by`, `hro_reviewed_at`, `hro_noted_by`, `hro_noted_at`, `hro_disapproved_by`, `hro_disapproved_at`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-00001', '2023-07-14', 1, 4, NULL, 0, 1, '2023-07-14 07:06:00', 0, '2023-07-14 07:06:00', 0, '2023-07-14 07:06:00', 0, '2023-07-14 07:06:00', 0, 1, NULL, '2023-07-14 08:44:55'),
(2, 7, '2023-00001', '2023-07-19', 2, 0, 'Reason for offers', 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-17 06:44:50', '2023-07-21 06:33:15'),
(3, 7, '2023-00003', '2023-07-17', 2, 0, 'Reason for offers', 1, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-17 06:45:35', '2023-07-21 06:33:33'),
(4, 7, '2023-00004', '2023-07-20', 2, 1, NULL, 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 1, '2023-07-19 05:28:12', '2023-07-19 05:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `hr_offset_hours`
--

CREATE TABLE `hr_offset_hours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hroh_total_offset_hours` int(11) NOT NULL COMMENT 'Total Offset',
  `hroh_used_offset_hours` int(11) NOT NULL COMMENT 'Total Used',
  `hroh_balance_offset_hours` int(11) NOT NULL COMMENT 'Total Balance Offset',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_offset_hours`
--

INSERT INTO `hr_offset_hours` (`id`, `hr_employeesid`, `hroh_total_offset_hours`, `hroh_used_offset_hours`, `hroh_balance_offset_hours`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 7, 9, 4, 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_salary_grades`
--

CREATE TABLE `hr_salary_grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrsg_salary_grade` int(10) UNSIGNED NOT NULL COMMENT 'salary Grade',
  `hrsg_step_1` int(10) UNSIGNED NOT NULL COMMENT 'Step 1',
  `hrsg_step_2` int(10) UNSIGNED NOT NULL COMMENT 'Step 2',
  `hrsg_step_3` int(10) UNSIGNED NOT NULL COMMENT 'Step 3',
  `hrsg_step_4` int(10) UNSIGNED NOT NULL COMMENT 'Step 4',
  `hrsg_step_5` int(10) UNSIGNED NOT NULL COMMENT 'Step 5',
  `hrsg_step_6` int(10) UNSIGNED NOT NULL COMMENT 'Step 6',
  `hrsg_step_7` int(10) UNSIGNED NOT NULL COMMENT 'Step 7',
  `hrsg_step_8` int(10) UNSIGNED NOT NULL COMMENT 'Step 8',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_salary_grades`
--

INSERT INTO `hr_salary_grades` (`id`, `hrsg_salary_grade`, `hrsg_step_1`, `hrsg_step_2`, `hrsg_step_3`, `hrsg_step_4`, `hrsg_step_5`, `hrsg_step_6`, `hrsg_step_7`, `hrsg_step_8`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 13000, 13100, 13200, 13300, 13400, 13500, 13600, 13700, 1, 1, 1, NULL, '2023-07-03 08:34:36'),
(2, 2, 5000, 5100, 5200, 5300, 5400, 5500, 5600, 5700, 1, 1, 1, '2023-07-03 05:27:17', '2023-07-03 05:54:27'),
(3, 3, 5000, 5100, 5200, 5300, 5400, 5500, 5600, 5700, 1, 1, 1, '2023-07-03 05:28:02', '2023-07-03 11:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `hr_salary_grade_steps`
--

CREATE TABLE `hr_salary_grade_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hrsgs_description` varchar(255) NOT NULL COMMENT 'Description',
  `is_active` int(11) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_salary_grade_steps`
--

INSERT INTO `hr_salary_grade_steps` (`id`, `hrsgs_description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Step 1', 1, 1, 1, '2023-07-24 07:56:13', '2023-07-24 08:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `hr_work_schedules`
--

CREATE TABLE `hr_work_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hr_employeesid` int(11) NOT NULL COMMENT 'ref-Table: hr_employees.id',
  `hrds_date` date NOT NULL COMMENT 'Date',
  `hrds_id` int(11) NOT NULL COMMENT 'ref-Table: hr_default_schedule.hrds_id',
  `year` int(11) NOT NULL COMMENT 'Current Year',
  `month` int(11) NOT NULL COMMENT 'month in year',
  `monthdate_json` text NOT NULL COMMENT 'whole month date json',
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_work_schedules`
--

INSERT INTO `hr_work_schedules` (`id`, `hr_employeesid`, `hrds_date`, `hrds_id`, `year`, `month`, `monthdate_json`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-07-01', 1, 2023, 7, '[{\"year\":\"2023\",\"month\":\"08\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":6,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":7,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":8,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":9,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":10,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":11,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":12,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":13,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":14,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":15,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":16,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":17,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":18,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":19,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":20,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":21,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":22,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":23,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":24,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":25,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":26,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":27,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":28,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":29,\"schedule\":2},{\"year\":\"2023\",\"month\":\"08\",\"day\":30,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":31,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 04:23:41', '2023-07-12 04:23:41'),
(2, 1, '2023-08-01', 1, 2023, 8, '{\"31\":{\"year\":\"2023\",\"month\":\"08\",\"day\":1,\"schedule\":\"1\"},\"32\":{\"year\":\"2023\",\"month\":\"08\",\"day\":2,\"schedule\":\"1\"},\"33\":{\"year\":\"2023\",\"month\":\"08\",\"day\":3,\"schedule\":\"1\"},\"34\":{\"year\":\"2023\",\"month\":\"08\",\"day\":4,\"schedule\":\"1\"},\"35\":{\"year\":\"2023\",\"month\":\"08\",\"day\":5,\"schedule\":\"1\"},\"36\":{\"year\":\"2023\",\"month\":\"08\",\"day\":6,\"schedule\":\"1\"},\"37\":{\"year\":\"2023\",\"month\":\"08\",\"day\":7,\"schedule\":\"1\"},\"38\":{\"year\":\"2023\",\"month\":\"08\",\"day\":8,\"schedule\":\"1\"},\"39\":{\"year\":\"2023\",\"month\":\"08\",\"day\":9,\"schedule\":\"1\"},\"40\":{\"year\":\"2023\",\"month\":\"08\",\"day\":10,\"schedule\":\"1\"},\"41\":{\"year\":\"2023\",\"month\":\"08\",\"day\":11,\"schedule\":\"1\"},\"42\":{\"year\":\"2023\",\"month\":\"08\",\"day\":12,\"schedule\":\"1\"},\"43\":{\"year\":\"2023\",\"month\":\"08\",\"day\":13,\"schedule\":\"1\"},\"44\":{\"year\":\"2023\",\"month\":\"08\",\"day\":14,\"schedule\":\"1\"},\"45\":{\"year\":\"2023\",\"month\":\"08\",\"day\":15,\"schedule\":\"1\"},\"46\":{\"year\":\"2023\",\"month\":\"08\",\"day\":16,\"schedule\":\"1\"},\"47\":{\"year\":\"2023\",\"month\":\"08\",\"day\":17,\"schedule\":\"1\"},\"48\":{\"year\":\"2023\",\"month\":\"08\",\"day\":18,\"schedule\":\"1\"},\"49\":{\"year\":\"2023\",\"month\":\"08\",\"day\":19,\"schedule\":\"1\"},\"50\":{\"year\":\"2023\",\"month\":\"08\",\"day\":20,\"schedule\":\"1\"},\"51\":{\"year\":\"2023\",\"month\":\"08\",\"day\":21,\"schedule\":\"1\"},\"52\":{\"year\":\"2023\",\"month\":\"08\",\"day\":22,\"schedule\":\"1\"},\"53\":{\"year\":\"2023\",\"month\":\"08\",\"day\":23,\"schedule\":\"1\"},\"54\":{\"year\":\"2023\",\"month\":\"08\",\"day\":24,\"schedule\":\"1\"},\"55\":{\"year\":\"2023\",\"month\":\"08\",\"day\":25,\"schedule\":\"1\"},\"56\":{\"year\":\"2023\",\"month\":\"08\",\"day\":26,\"schedule\":\"1\"},\"57\":{\"year\":\"2023\",\"month\":\"08\",\"day\":27,\"schedule\":\"1\"},\"58\":{\"year\":\"2023\",\"month\":\"08\",\"day\":28,\"schedule\":\"1\"},\"59\":{\"year\":\"2023\",\"month\":\"08\",\"day\":29,\"schedule\":\"1\"},\"60\":{\"year\":\"2023\",\"month\":\"08\",\"day\":30,\"schedule\":\"1\"},\"61\":{\"year\":\"2023\",\"month\":\"08\",\"day\":31,\"schedule\":\"1\"}}', 1, 1, '2023-07-12 04:23:41', '2023-07-12 04:23:41'),
(3, 1, '2023-09-01', 1, 2023, 9, '{\"62\":{\"year\":\"2023\",\"month\":\"09\",\"day\":1,\"schedule\":\"1\"},\"63\":{\"year\":\"2023\",\"month\":\"09\",\"day\":2,\"schedule\":\"1\"},\"64\":{\"year\":\"2023\",\"month\":\"09\",\"day\":3,\"schedule\":\"1\"},\"65\":{\"year\":\"2023\",\"month\":\"09\",\"day\":4,\"schedule\":\"1\"},\"66\":{\"year\":\"2023\",\"month\":\"09\",\"day\":5,\"schedule\":\"1\"},\"67\":{\"year\":\"2023\",\"month\":\"09\",\"day\":6,\"schedule\":\"1\"},\"68\":{\"year\":\"2023\",\"month\":\"09\",\"day\":7,\"schedule\":\"1\"},\"69\":{\"year\":\"2023\",\"month\":\"09\",\"day\":8,\"schedule\":\"1\"},\"70\":{\"year\":\"2023\",\"month\":\"09\",\"day\":9,\"schedule\":\"1\"},\"71\":{\"year\":\"2023\",\"month\":\"09\",\"day\":10,\"schedule\":\"1\"},\"72\":{\"year\":\"2023\",\"month\":\"09\",\"day\":11,\"schedule\":\"1\"},\"73\":{\"year\":\"2023\",\"month\":\"09\",\"day\":12,\"schedule\":\"1\"},\"74\":{\"year\":\"2023\",\"month\":\"09\",\"day\":13,\"schedule\":\"1\"},\"75\":{\"year\":\"2023\",\"month\":\"09\",\"day\":14,\"schedule\":\"1\"},\"76\":{\"year\":\"2023\",\"month\":\"09\",\"day\":15,\"schedule\":\"1\"},\"77\":{\"year\":\"2023\",\"month\":\"09\",\"day\":16,\"schedule\":\"1\"},\"78\":{\"year\":\"2023\",\"month\":\"09\",\"day\":17,\"schedule\":\"1\"},\"79\":{\"year\":\"2023\",\"month\":\"09\",\"day\":18,\"schedule\":\"1\"},\"80\":{\"year\":\"2023\",\"month\":\"09\",\"day\":19,\"schedule\":\"1\"},\"81\":{\"year\":\"2023\",\"month\":\"09\",\"day\":20,\"schedule\":\"1\"},\"82\":{\"year\":\"2023\",\"month\":\"09\",\"day\":21,\"schedule\":\"1\"},\"83\":{\"year\":\"2023\",\"month\":\"09\",\"day\":22,\"schedule\":\"1\"},\"84\":{\"year\":\"2023\",\"month\":\"09\",\"day\":23,\"schedule\":\"1\"},\"85\":{\"year\":\"2023\",\"month\":\"09\",\"day\":24,\"schedule\":\"1\"},\"86\":{\"year\":\"2023\",\"month\":\"09\",\"day\":25,\"schedule\":\"1\"},\"87\":{\"year\":\"2023\",\"month\":\"09\",\"day\":26,\"schedule\":\"1\"},\"88\":{\"year\":\"2023\",\"month\":\"09\",\"day\":27,\"schedule\":\"1\"},\"89\":{\"year\":\"2023\",\"month\":\"09\",\"day\":28,\"schedule\":\"1\"},\"90\":{\"year\":\"2023\",\"month\":\"09\",\"day\":29,\"schedule\":\"1\"},\"91\":{\"year\":\"2023\",\"month\":\"09\",\"day\":30,\"schedule\":\"1\"}}', 1, 1, '2023-07-12 04:23:41', '2023-07-12 04:23:41'),
(4, 1, '2023-10-01', 1, 2023, 10, '{\"92\":{\"year\":\"2023\",\"month\":\"10\",\"day\":1,\"schedule\":\"1\"},\"93\":{\"year\":\"2023\",\"month\":\"10\",\"day\":2,\"schedule\":\"1\"},\"94\":{\"year\":\"2023\",\"month\":\"10\",\"day\":3,\"schedule\":\"1\"},\"95\":{\"year\":\"2023\",\"month\":\"10\",\"day\":4,\"schedule\":\"1\"},\"96\":{\"year\":\"2023\",\"month\":\"10\",\"day\":5,\"schedule\":\"1\"},\"97\":{\"year\":\"2023\",\"month\":\"10\",\"day\":6,\"schedule\":\"1\"},\"98\":{\"year\":\"2023\",\"month\":\"10\",\"day\":7,\"schedule\":\"1\"},\"99\":{\"year\":\"2023\",\"month\":\"10\",\"day\":8,\"schedule\":\"1\"},\"100\":{\"year\":\"2023\",\"month\":\"10\",\"day\":9,\"schedule\":\"1\"},\"101\":{\"year\":\"2023\",\"month\":\"10\",\"day\":10,\"schedule\":\"1\"},\"102\":{\"year\":\"2023\",\"month\":\"10\",\"day\":11,\"schedule\":\"1\"},\"103\":{\"year\":\"2023\",\"month\":\"10\",\"day\":12,\"schedule\":\"1\"},\"104\":{\"year\":\"2023\",\"month\":\"10\",\"day\":13,\"schedule\":\"1\"},\"105\":{\"year\":\"2023\",\"month\":\"10\",\"day\":14,\"schedule\":\"1\"},\"106\":{\"year\":\"2023\",\"month\":\"10\",\"day\":15,\"schedule\":\"1\"},\"107\":{\"year\":\"2023\",\"month\":\"10\",\"day\":16,\"schedule\":\"1\"},\"108\":{\"year\":\"2023\",\"month\":\"10\",\"day\":17,\"schedule\":\"1\"},\"109\":{\"year\":\"2023\",\"month\":\"10\",\"day\":18,\"schedule\":\"1\"},\"110\":{\"year\":\"2023\",\"month\":\"10\",\"day\":19,\"schedule\":\"1\"},\"111\":{\"year\":\"2023\",\"month\":\"10\",\"day\":20,\"schedule\":\"1\"},\"112\":{\"year\":\"2023\",\"month\":\"10\",\"day\":21,\"schedule\":\"1\"},\"113\":{\"year\":\"2023\",\"month\":\"10\",\"day\":22,\"schedule\":\"1\"},\"114\":{\"year\":\"2023\",\"month\":\"10\",\"day\":23,\"schedule\":\"1\"},\"115\":{\"year\":\"2023\",\"month\":\"10\",\"day\":24,\"schedule\":\"1\"},\"116\":{\"year\":\"2023\",\"month\":\"10\",\"day\":25,\"schedule\":\"1\"},\"117\":{\"year\":\"2023\",\"month\":\"10\",\"day\":26,\"schedule\":\"1\"},\"118\":{\"year\":\"2023\",\"month\":\"10\",\"day\":27,\"schedule\":\"1\"},\"119\":{\"year\":\"2023\",\"month\":\"10\",\"day\":28,\"schedule\":\"1\"},\"120\":{\"year\":\"2023\",\"month\":\"10\",\"day\":29,\"schedule\":\"1\"},\"121\":{\"year\":\"2023\",\"month\":\"10\",\"day\":30,\"schedule\":\"1\"},\"122\":{\"year\":\"2023\",\"month\":\"10\",\"day\":31,\"schedule\":\"1\"}}', 1, 1, '2023-07-12 04:23:41', '2023-07-12 04:23:41'),
(5, 1, '2023-11-01', 1, 2023, 11, '{\"123\":{\"year\":\"2023\",\"month\":\"11\",\"day\":1,\"schedule\":\"1\"},\"124\":{\"year\":\"2023\",\"month\":\"11\",\"day\":2,\"schedule\":\"1\"},\"125\":{\"year\":\"2023\",\"month\":\"11\",\"day\":3,\"schedule\":\"1\"},\"126\":{\"year\":\"2023\",\"month\":\"11\",\"day\":4,\"schedule\":\"1\"},\"127\":{\"year\":\"2023\",\"month\":\"11\",\"day\":5,\"schedule\":\"1\"},\"128\":{\"year\":\"2023\",\"month\":\"11\",\"day\":6,\"schedule\":\"1\"},\"129\":{\"year\":\"2023\",\"month\":\"11\",\"day\":7,\"schedule\":\"1\"},\"130\":{\"year\":\"2023\",\"month\":\"11\",\"day\":8,\"schedule\":\"1\"},\"131\":{\"year\":\"2023\",\"month\":\"11\",\"day\":9,\"schedule\":\"1\"},\"132\":{\"year\":\"2023\",\"month\":\"11\",\"day\":10,\"schedule\":\"1\"},\"133\":{\"year\":\"2023\",\"month\":\"11\",\"day\":11,\"schedule\":\"1\"},\"134\":{\"year\":\"2023\",\"month\":\"11\",\"day\":12,\"schedule\":\"1\"},\"135\":{\"year\":\"2023\",\"month\":\"11\",\"day\":13,\"schedule\":\"1\"},\"136\":{\"year\":\"2023\",\"month\":\"11\",\"day\":14,\"schedule\":\"1\"},\"137\":{\"year\":\"2023\",\"month\":\"11\",\"day\":15,\"schedule\":\"1\"},\"138\":{\"year\":\"2023\",\"month\":\"11\",\"day\":16,\"schedule\":\"1\"},\"139\":{\"year\":\"2023\",\"month\":\"11\",\"day\":17,\"schedule\":\"1\"},\"140\":{\"year\":\"2023\",\"month\":\"11\",\"day\":18,\"schedule\":\"1\"},\"141\":{\"year\":\"2023\",\"month\":\"11\",\"day\":19,\"schedule\":\"1\"},\"142\":{\"year\":\"2023\",\"month\":\"11\",\"day\":20,\"schedule\":\"1\"},\"143\":{\"year\":\"2023\",\"month\":\"11\",\"day\":21,\"schedule\":\"1\"},\"144\":{\"year\":\"2023\",\"month\":\"11\",\"day\":22,\"schedule\":\"1\"},\"145\":{\"year\":\"2023\",\"month\":\"11\",\"day\":23,\"schedule\":\"1\"},\"146\":{\"year\":\"2023\",\"month\":\"11\",\"day\":24,\"schedule\":\"1\"},\"147\":{\"year\":\"2023\",\"month\":\"11\",\"day\":25,\"schedule\":\"1\"},\"148\":{\"year\":\"2023\",\"month\":\"11\",\"day\":26,\"schedule\":\"1\"},\"149\":{\"year\":\"2023\",\"month\":\"11\",\"day\":27,\"schedule\":\"1\"},\"150\":{\"year\":\"2023\",\"month\":\"11\",\"day\":28,\"schedule\":\"1\"},\"151\":{\"year\":\"2023\",\"month\":\"11\",\"day\":29,\"schedule\":\"1\"},\"152\":{\"year\":\"2023\",\"month\":\"11\",\"day\":30,\"schedule\":\"1\"}}', 1, 1, '2023-07-12 04:23:41', '2023-07-12 04:23:41'),
(6, 1, '2023-12-01', 1, 2023, 12, '{\"153\":{\"year\":\"2023\",\"month\":\"12\",\"day\":1,\"schedule\":\"1\"},\"154\":{\"year\":\"2023\",\"month\":\"12\",\"day\":2,\"schedule\":\"1\"},\"155\":{\"year\":\"2023\",\"month\":\"12\",\"day\":3,\"schedule\":\"1\"},\"156\":{\"year\":\"2023\",\"month\":\"12\",\"day\":4,\"schedule\":\"1\"},\"157\":{\"year\":\"2023\",\"month\":\"12\",\"day\":5,\"schedule\":\"1\"},\"158\":{\"year\":\"2023\",\"month\":\"12\",\"day\":6,\"schedule\":\"1\"},\"159\":{\"year\":\"2023\",\"month\":\"12\",\"day\":7,\"schedule\":\"1\"},\"160\":{\"year\":\"2023\",\"month\":\"12\",\"day\":8,\"schedule\":\"1\"},\"161\":{\"year\":\"2023\",\"month\":\"12\",\"day\":9,\"schedule\":\"1\"},\"162\":{\"year\":\"2023\",\"month\":\"12\",\"day\":10,\"schedule\":\"1\"},\"163\":{\"year\":\"2023\",\"month\":\"12\",\"day\":11,\"schedule\":\"1\"},\"164\":{\"year\":\"2023\",\"month\":\"12\",\"day\":12,\"schedule\":\"1\"},\"165\":{\"year\":\"2023\",\"month\":\"12\",\"day\":13,\"schedule\":\"1\"},\"166\":{\"year\":\"2023\",\"month\":\"12\",\"day\":14,\"schedule\":\"1\"},\"167\":{\"year\":\"2023\",\"month\":\"12\",\"day\":15,\"schedule\":\"1\"},\"168\":{\"year\":\"2023\",\"month\":\"12\",\"day\":16,\"schedule\":\"1\"},\"169\":{\"year\":\"2023\",\"month\":\"12\",\"day\":17,\"schedule\":\"1\"},\"170\":{\"year\":\"2023\",\"month\":\"12\",\"day\":18,\"schedule\":\"1\"},\"171\":{\"year\":\"2023\",\"month\":\"12\",\"day\":19,\"schedule\":\"1\"},\"172\":{\"year\":\"2023\",\"month\":\"12\",\"day\":20,\"schedule\":\"1\"},\"173\":{\"year\":\"2023\",\"month\":\"12\",\"day\":21,\"schedule\":\"1\"},\"174\":{\"year\":\"2023\",\"month\":\"12\",\"day\":22,\"schedule\":\"1\"},\"175\":{\"year\":\"2023\",\"month\":\"12\",\"day\":23,\"schedule\":\"1\"},\"176\":{\"year\":\"2023\",\"month\":\"12\",\"day\":24,\"schedule\":\"1\"},\"177\":{\"year\":\"2023\",\"month\":\"12\",\"day\":25,\"schedule\":\"1\"},\"178\":{\"year\":\"2023\",\"month\":\"12\",\"day\":26,\"schedule\":\"1\"},\"179\":{\"year\":\"2023\",\"month\":\"12\",\"day\":27,\"schedule\":\"1\"},\"180\":{\"year\":\"2023\",\"month\":\"12\",\"day\":28,\"schedule\":\"1\"},\"181\":{\"year\":\"2023\",\"month\":\"12\",\"day\":29,\"schedule\":\"1\"},\"182\":{\"year\":\"2023\",\"month\":\"12\",\"day\":30,\"schedule\":\"1\"},\"183\":{\"year\":\"2023\",\"month\":\"12\",\"day\":31,\"schedule\":\"1\"}}', 1, 1, '2023-07-12 04:23:41', '2023-07-12 04:23:41'),
(7, 7, '2023-07-01', 1, 2023, 7, '[{\"year\":\"2023\",\"month\":\"07\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":6,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":7,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":8,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":9,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":10,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":11,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":12,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":13,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":14,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":15,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":16,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":17,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":18,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":19,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":20,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":21,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":22,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":23,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":24,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":25,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":26,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":27,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":28,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":29,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":30,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"07\",\"day\":31,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 07:34:21', '2023-07-12 07:34:21'),
(8, 7, '2023-08-01', 1, 2023, 8, '[{\"year\":\"2023\",\"month\":\"08\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":6,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":7,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":8,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":9,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":10,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":11,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":12,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":13,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":14,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":15,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":16,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":17,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":18,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":19,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":20,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":21,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":22,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":23,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":24,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":25,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":26,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":27,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":28,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":29,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":30,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"08\",\"day\":31,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 07:34:21', '2023-07-12 07:34:21'),
(9, 7, '2023-09-01', 1, 2023, 9, '[{\"year\":\"2023\",\"month\":\"09\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":6,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":7,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":8,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":9,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":10,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":11,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":12,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":13,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":14,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":15,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":16,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":17,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":18,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":19,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":20,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":21,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":22,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":23,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":24,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":25,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":26,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":27,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":28,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":29,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"09\",\"day\":30,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 07:34:21', '2023-07-12 07:34:21'),
(10, 7, '2023-10-01', 1, 2023, 10, '[{\"year\":\"2023\",\"month\":\"10\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":6,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":7,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":8,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":9,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":10,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":11,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":12,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":13,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":14,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":15,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":16,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":17,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":18,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":19,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":20,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":21,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":22,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":23,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":24,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":25,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":26,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":27,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":28,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":29,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":30,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"10\",\"day\":31,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 07:34:21', '2023-07-12 07:34:21'),
(11, 7, '2023-11-01', 1, 2023, 11, '[{\"year\":\"2023\",\"month\":\"11\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":6,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":7,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":8,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":9,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":10,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":11,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":12,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":13,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":14,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":15,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":16,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":17,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":18,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":19,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":20,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":21,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":22,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":23,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":24,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":25,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":26,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":27,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":28,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":29,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"11\",\"day\":30,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 07:34:21', '2023-07-12 07:34:21'),
(12, 7, '2023-12-01', 1, 2023, 12, '[{\"year\":\"2023\",\"month\":\"12\",\"day\":1,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":2,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":3,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":4,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":5,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":6,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":7,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":8,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":9,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":10,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":11,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":12,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":13,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":14,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":15,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":16,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":17,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":18,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":19,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":20,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":21,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":22,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":23,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":24,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":25,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":26,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":27,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":28,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":29,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":30,\"schedule\":\"1\"},{\"year\":\"2023\",\"month\":\"12\",\"day\":31,\"schedule\":\"1\"}]', 1, 1, '2023-07-12 07:34:21', '2023-07-12 07:34:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_changeof_schedules`
--
ALTER TABLE `hr_changeof_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_default_schedules`
--
ALTER TABLE `hr_default_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_employee_statuses`
--
ALTER TABLE `hr_employee_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_holidays`
--
ALTER TABLE `hr_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leaves`
--
ALTER TABLE `hr_leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leavetypes`
--
ALTER TABLE `hr_leavetypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leave_adjustments`
--
ALTER TABLE `hr_leave_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leave_adjustment_detail`
--
ALTER TABLE `hr_leave_adjustment_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leave_applications`
--
ALTER TABLE `hr_leave_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leave_earning_adjustment_detail`
--
ALTER TABLE `hr_leave_earning_adjustment_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leave_parameter`
--
ALTER TABLE `hr_leave_parameter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_leave_parameter_detail`
--
ALTER TABLE `hr_leave_parameter_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_occupation_levels`
--
ALTER TABLE `hr_occupation_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_official_works`
--
ALTER TABLE `hr_official_works`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_offsets`
--
ALTER TABLE `hr_offsets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_offset_hours`
--
ALTER TABLE `hr_offset_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_salary_grades`
--
ALTER TABLE `hr_salary_grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_salary_grade_steps`
--
ALTER TABLE `hr_salary_grade_steps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_work_schedules`
--
ALTER TABLE `hr_work_schedules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_changeof_schedules`
--
ALTER TABLE `hr_changeof_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hr_default_schedules`
--
ALTER TABLE `hr_default_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_employee_statuses`
--
ALTER TABLE `hr_employee_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_holidays`
--
ALTER TABLE `hr_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_leaves`
--
ALTER TABLE `hr_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hr_leavetypes`
--
ALTER TABLE `hr_leavetypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hr_leave_adjustments`
--
ALTER TABLE `hr_leave_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hr_leave_adjustment_detail`
--
ALTER TABLE `hr_leave_adjustment_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hr_leave_applications`
--
ALTER TABLE `hr_leave_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_leave_earning_adjustment_detail`
--
ALTER TABLE `hr_leave_earning_adjustment_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hr_leave_parameter`
--
ALTER TABLE `hr_leave_parameter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_leave_parameter_detail`
--
ALTER TABLE `hr_leave_parameter_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hr_occupation_levels`
--
ALTER TABLE `hr_occupation_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_official_works`
--
ALTER TABLE `hr_official_works`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hr_offsets`
--
ALTER TABLE `hr_offsets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hr_offset_hours`
--
ALTER TABLE `hr_offset_hours`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_salary_grades`
--
ALTER TABLE `hr_salary_grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_salary_grade_steps`
--
ALTER TABLE `hr_salary_grade_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_work_schedules`
--
ALTER TABLE `hr_work_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
