-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2023 at 03:23 AM
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
-- Table structure for table `cto_cashier_real_properties`
--

CREATE TABLE `cto_cashier_real_properties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cashier_year` int(11) DEFAULT NULL,
  `cashier_month` int(11) DEFAULT NULL,
  `cashier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `top_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tfoc_is_applicable` int(11) DEFAULT NULL,
  `cb_code` bigint(20) UNSIGNED DEFAULT NULL,
  `rp_code` bigint(20) UNSIGNED DEFAULT NULL,
  `pk_code` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rp_tax_declaration_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cb_billing_mode` tinyint(4) DEFAULT NULL COMMENT '0=for Single Property Billing,1=for Multiplie Property Billing',
  `cb_control_no` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_cashier_real_properties`
--
ALTER TABLE `cto_cashier_real_properties`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_cashier_real_properties`
--
ALTER TABLE `cto_cashier_real_properties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
