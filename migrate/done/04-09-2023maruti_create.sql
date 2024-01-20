-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2023 at 04:53 AM
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
-- Table structure for table `cto_accounts_receivables`
--

CREATE TABLE `cto_accounts_receivables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ar_year` int(11) NOT NULL COMMENT 'current year',
  `ar_month` int(11) NOT NULL COMMENT 'Current Month',
  `ar_no` int(11) NOT NULL COMMENT 'ar no',
  `ar_control_no` int(11) NOT NULL COMMENT 'Combination of(ay_year-ar_no)',
  `ar_date` date NOT NULL COMMENT 'date',
  `top_transaction_id` int(11) NOT NULL COMMENT 'Ref-Table: cto_top_transactions.id',
  `payee_type` int(11) NOT NULL COMMENT '1=Client(table:clients), 2=Citizen(table:citizens)',
  `taxpayer_id` int(11) NOT NULL COMMENT 'Taxpayers and Citizen ID reference number',
  `pcs_id` int(11) NOT NULL COMMENT 'Ref-Table: cto_payment_cashier_system.id)',
  `rp_property_code` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: rp_properties.rp_property_code. this is unique',
  `rp_code` bigint(20) UNSIGNED NOT NULL COMMENT 'Ref-Table: rp_properties.id. this is dynamic and the most recent rpt_code',
  `pk_id` int(11) NOT NULL COMMENT 'Ref-Table: rpt_property_kinds.id',
  `rvy_revision_year_id` int(11) NOT NULL COMMENT 'Ref-Table: rpt_revision_year.id',
  `brgy_code_id` int(11) NOT NULL COMMENT 'Ref-Table: barangays.id',
  `rp_assessed_value` double(14,5) NOT NULL COMMENT 'Total Assessed Values for both the Land, Building & Properties',
  `rp_basic_amount` double(14,5) NOT NULL COMMENT 'Tax, Fees & Other Charges',
  `rp_sef_amount` double(14,5) NOT NULL COMMENT 'Tax, Fees & Other Charges',
  `rp_sht_amount` double(14,5) NOT NULL COMMENT 'Tax, Fees & Other Charges',
  `rp_last_cashier_id` int(11) NOT NULL COMMENT 'the latest cashier ID',
  `status` int(11) NOT NULL COMMENT 'when the amounts(like rp_basic_amount, rp_sef_amount and rp_sht_amount) already set to ZERO',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cto_accounts_receivables`
--

INSERT INTO `cto_accounts_receivables` (`id`, `ar_year`, `ar_month`, `ar_no`, `ar_control_no`, `ar_date`, `top_transaction_id`, `payee_type`, `taxpayer_id`, `pcs_id`, `rp_property_code`, `rp_code`, `pk_id`, `rvy_revision_year_id`, `brgy_code_id`, `rp_assessed_value`, `rp_basic_amount`, `rp_sef_amount`, `rp_sht_amount`, `rp_last_cashier_id`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2023, 8, 1, 123, '2023-09-02', 11, 1, 12, 2, 57, 1013, 2, 12, 27, 100.00000, 100.00000, 10.00000, 10.00000, 1, 1, 1, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cto_accounts_receivables`
--
ALTER TABLE `cto_accounts_receivables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cto_accounts_receivables`
--
ALTER TABLE `cto_accounts_receivables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
