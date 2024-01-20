-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2023 at 11:33 AM
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
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `busn_id` int(10) UNSIGNED DEFAULT NULL,
  `rp_property_code` int(10) UNSIGNED DEFAULT NULL,
  `rp_code` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `bill_year` int(10) UNSIGNED NOT NULL,
  `bill_month` int(10) UNSIGNED NOT NULL,
  `bill_due_date` date NOT NULL,
  `app_code` int(10) UNSIGNED NOT NULL,
  `pm_id` int(10) UNSIGNED NOT NULL,
  `pap_id` int(10) UNSIGNED NOT NULL,
  `particulars` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `total_paid_amount` decimal(10,2) NOT NULL,
  `or_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `or_date` date DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `is_synced` tinyint(1) NOT NULL DEFAULT 0,
  `payment_taransaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `merchant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txnstatus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transactiontype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txnmessage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tokentype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cardholder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issuingbank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cardnomask` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cardexp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cardtype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settletaid` int(10) UNSIGNED NOT NULL,
  `tid` int(10) UNSIGNED DEFAULT NULL,
  `currencycode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_response` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_approved` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`id`, `department_id`, `busn_id`, `rp_property_code`, `rp_code`, `client_id`, `bill_year`, `bill_month`, `bill_due_date`, `app_code`, `pm_id`, `pap_id`, `particulars`, `total_amount`, `total_paid_amount`, `or_no`, `or_date`, `transaction_no`, `attachement`, `payment_status`, `payment_date`, `is_synced`, `payment_taransaction_id`, `merchant`, `txnstatus`, `paymentid`, `transactiontype`, `authcode`, `txnmessage`, `token`, `tokentype`, `cardholder`, `issuingbank`, `cardnomask`, `cardexp`, `cardtype`, `settletaid`, `tid`, `currencycode`, `payment_response`, `is_approved`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 11, 1, 1, 1, 154, 2023, 12, '2023-11-28', 1, 1, 1, '1', '1000.00', '900.00', NULL, NULL, '11', NULL, 'Success', '2023-11-27', 1, '1988888818111', NULL, NULL, '11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, NULL, NULL, NULL, 1, '2023-11-27 05:50:09', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
