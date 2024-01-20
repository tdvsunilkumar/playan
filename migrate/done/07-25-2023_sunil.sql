-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2023 at 10:35 AM
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
-- Database: `playan`
--

-- --------------------------------------------------------

--
-- Table structure for table `rpt_delinquents`
--

CREATE TABLE `rpt_delinquents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rp_code` bigint(20) DEFAULT NULL COMMENT 'Ref-Table: rpt_properties.id',
  `year` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rp_property_code` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: rpt_properties.rp_property_code',
  `cb_code` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: rpt_cto_billing.id',
  `sub_amount` decimal(20,2) DEFAULT NULL,
  `penalty_amount` decimal(20,2) DEFAULT NULL,
  `total_amount` decimal(20,2) DEFAULT NULL,
  `payment_status` int(11) DEFAULT 0,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Raf-Table: cto_top_transactions.transaction_no',
  `payment_date` date DEFAULT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0 COMMENT 'This flag will update from user through email',
  `acknowledged_date` datetime DEFAULT NULL COMMENT 'This date will update from user through email',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rpt_delinquents`
--

INSERT INTO `rpt_delinquents` (`id`, `rp_code`, `year`, `rp_property_code`, `cb_code`, `sub_amount`, `penalty_amount`, `total_amount`, `payment_status`, `transaction_no`, `payment_date`, `is_approved`, `acknowledged_date`, `created_at`, `updated_at`) VALUES
(54, 79, '2020', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:01', NULL),
(55, 79, '2021', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:01', NULL),
(56, 79, '2022', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:01', NULL),
(57, 79, '2023', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:01', NULL),
(58, 80, '2020', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(59, 80, '2021', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(60, 80, '2022', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(61, 80, '2023', 10110, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(62, 81, '2023', 10113, 0, '729.00', '524.88', '1253.88', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(63, 82, '2020', 10111, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(64, 82, '2021', 10111, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(65, 82, '2022', 10111, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(66, 82, '2023', 10111, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(67, 86, '2020', 10417, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(68, 86, '2021', 10417, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(69, 86, '2022', 10417, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(70, 86, '2023', 10417, 0, '0.00', '0.00', '0.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(71, 88, '2020', 11060421, 0, '1.70', '1.22', '2.93', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(72, 88, '2021', 11060421, 0, '1.70', '1.22', '2.93', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(73, 88, '2022', 11060421, 0, '1.70', '1.22', '2.93', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(74, 88, '2023', 11060421, 0, '1.70', '1.22', '2.93', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(75, 93, '2020', 11060426, 0, '0.82', '0.59', '1.41', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(76, 93, '2021', 11060426, 0, '0.82', '0.59', '1.41', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(77, 93, '2022', 11060426, 0, '0.82', '0.59', '1.41', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(78, 93, '2023', 11060426, 0, '0.82', '0.59', '1.41', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(79, 94, '2020', 11060427, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(80, 94, '2021', 11060427, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(81, 94, '2022', 11060427, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(82, 94, '2023', 11060427, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(83, 95, '2020', 11060428, 0, '11250.00', '8100.00', '19350.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(84, 95, '2021', 11060428, 0, '11250.00', '8100.00', '19350.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(85, 95, '2022', 11060428, 0, '11250.00', '8100.00', '19350.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(86, 95, '2023', 11060428, 0, '11250.00', '8100.00', '19350.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(87, 96, '2020', 11060429, 0, '562.50', '405.00', '967.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(88, 96, '2021', 11060429, 0, '562.50', '405.00', '967.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(89, 96, '2022', 11060429, 0, '562.50', '405.00', '967.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(90, 96, '2023', 11060429, 0, '562.50', '405.00', '967.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(91, 97, '2020', 11060430, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(92, 97, '2021', 11060430, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(93, 97, '2022', 11060430, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(94, 97, '2023', 11060430, 0, '1125.00', '810.00', '1935.00', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(95, 98, '2020', 11060431, 0, '6187.50', '4455.00', '10642.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(96, 98, '2021', 11060431, 0, '6187.50', '4455.00', '10642.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(97, 98, '2022', 11060431, 0, '6187.50', '4455.00', '10642.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL),
(98, 98, '2023', 11060431, 0, '6187.50', '4455.00', '10642.50', 0, '0', NULL, 0, NULL, '2023-07-25 07:04:02', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rpt_delinquents`
--
ALTER TABLE `rpt_delinquents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rpt_delinquents`
--
ALTER TABLE `rpt_delinquents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
