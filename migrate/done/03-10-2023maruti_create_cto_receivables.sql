-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2023 at 08:28 AM
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
-- Table structure for table `cto_receivables`
--

CREATE TABLE `cto_receivables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL COMMENT 'cemetery, housing,  rental, real-property,All',
  `application_id` int(11) NOT NULL COMMENT 'cemetery, housing,  rental App id',
  `fund_code_id` int(11) NOT NULL COMMENT 'ref-Table : acctg_account_fund_code.id',
  `gl_account_id` int(11) NOT NULL COMMENT 'ref-Table : acctg_account_general_ledgers.id',
  `sl_account_id` int(11) NOT NULL COMMENT 'ref-Table : acctg_account_subsidiary_ledgers.id',
  `description` varchar(255) NOT NULL COMMENT 'cto_cashier.or_no',
  `top_no` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `amount_type` varchar(255) NOT NULL COMMENT 'penalty, interest, revenue, Monthly',
  `amount_due` double(14,2) NOT NULL COMMENT 'Amount Due',
  `amount_basic` double(14,2) NOT NULL COMMENT 'Basic Due',
  `amount_set` double(14,2) NOT NULL COMMENT 'Amount Set',
  `amount_socialize` double(14,2) NOT NULL COMMENT 'Amount Due',
  `amount_pay` double(14,2) NOT NULL COMMENT 'ref-Table : cto_cashier_details.total_amount',
  `remaining_amount` double(14,2) NOT NULL COMMENT 'Amount Remaining',
  `cashier_id` int(11) NOT NULL,
  `or_no` varchar(255) NOT NULL COMMENT 'Or No',
  `or_date` date NOT NULL COMMENT 'Or Date',
  `is_paid` int(11) NOT NULL,
  `is_active` int(11) NOT NULL COMMENT '0 = Inactive, 1 = Active',
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_receivables`
--
ALTER TABLE `cto_receivables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_receivables`
--
ALTER TABLE `cto_receivables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
