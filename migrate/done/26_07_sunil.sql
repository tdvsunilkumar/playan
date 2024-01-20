-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2023 at 07:21 AM
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
  `year` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rp_code` bigint(20) DEFAULT NULL,
  `rp_property_code` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: rpt_properties.rp_property_code',
  `cb_code` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: rpt_cto_billing.id',
  `basic_amount` decimal(20,2) DEFAULT NULL,
  `sef_amount` decimal(20,2) DEFAULT NULL,
  `sh_amount` decimal(20,2) DEFAULT NULL,
  `basic_penalty` decimal(20,2) DEFAULT NULL,
  `sef_penalty` decimal(20,2) DEFAULT NULL,
  `sh_penalty` decimal(20,2) DEFAULT NULL,
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
