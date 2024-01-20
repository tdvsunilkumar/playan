-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2023 at 10:44 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news`
--

-- --------------------------------------------------------

--
-- Table structure for table `cto_accounts_receivable_setups`
--

CREATE TABLE `cto_accounts_receivable_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pk_id` int(10) UNSIGNED NOT NULL COMMENT 'Ref-Table: rpt_property_kinds.id',
  `ars_category` int(10) UNSIGNED NOT NULL COMMENT '1=basic tax, 2=special education tax, 3=socialize housing tax',
  `ars_fund_id` int(10) UNSIGNED NOT NULL COMMENT 'Ref-Table: acctg_fund_codes.id',
  `gl_id` int(10) UNSIGNED NOT NULL COMMENT 'Ref-Table: acctg_account_general_ledgers.id',
  `sl_id` int(10) UNSIGNED NOT NULL COMMENT 'Ref-Table: acctg_account_subsidiary_ledgers.id',
  `ars_remarks` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=Cancelled, 1=Active',
  `created_by` int(10) UNSIGNED NOT NULL COMMENT 'reference hr_employee_id of the system who registered the details',
  `updated_by` int(10) UNSIGNED NOT NULL COMMENT 'reference hr_employee_id of the system who update the details',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_accounts_receivable_setups`
--
ALTER TABLE `cto_accounts_receivable_setups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_accounts_receivable_setups`
--
ALTER TABLE `cto_accounts_receivable_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
